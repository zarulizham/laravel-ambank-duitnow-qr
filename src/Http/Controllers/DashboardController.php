<?php

namespace ZarulIzham\DuitNowQR\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use ZarulIzham\DuitNowQR\Models\DuitNowQRPayment;
use ZarulIzham\DuitNowQR\Models\DuitNowQRTransaction;

class DashboardController
{
    public function asset(string $file)
    {
        $basePath = realpath(__DIR__.'/../../../resources/vuejs/dashboard');

        abort_if(! $basePath, 404);

        $normalized = ltrim($file, '/');
        abort_if(Str::contains($normalized, ['..', '\\']), 404);

        $absolutePath = realpath($basePath.DIRECTORY_SEPARATOR.$normalized);

        abort_if(! $absolutePath, 404);
        abort_unless(Str::startsWith($absolutePath, $basePath), 404);
        abort_unless(Str::endsWith($absolutePath, '.vuejs'), 404);

        return response()->file($absolutePath, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
            'Cache-Control' => 'public, max-age=300',
        ]);
    }

    public function index(): View
    {
        return view()->file(__DIR__.'/../../../resources/views/dashboard.blade.php');
    }

    public function transactions(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 15), 1), 100);

        $transactions = DuitNowQRTransaction::query()
            ->withCount('payments')
            ->latest()
            ->paginate($perPage, [
                'id',
                'source_reference_number',
                'transaction_status',
                'amount',
                'reference_id',
                'reference_type',
                'created_at',
            ])
            ->withQueryString();

        return response()->json([
            'data' => $transactions->items(),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'from' => $transactions->firstItem(),
                'to' => $transactions->lastItem(),
            ],
        ]);
    }

    public function showTransaction(DuitNowQRTransaction $transaction): JsonResponse
    {
        $transaction->load([
            'payments' => function ($query) {
                $query->latest();
            },
        ]);

        return response()->json(['data' => $transaction]);
    }

    public function payments(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 15), 1), 100);

        $payments = DuitNowQRPayment::query()
            ->with('transaction:id,source_reference_number,transaction_status,amount,created_at')
            ->when($request->filled('biz_id'), function ($query) use ($request) {
                $query->where('biz_id', $request->string('biz_id')->toString());
            })
            ->when($request->filled('end_id'), function ($query) use ($request) {
                $query->where('end_id', $request->string('end_id')->toString());
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'data' => $payments->items(),
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total(),
                'from' => $payments->firstItem(),
                'to' => $payments->lastItem(),
            ],
        ]);
    }
}
