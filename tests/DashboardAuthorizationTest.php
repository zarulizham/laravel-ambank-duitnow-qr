<?php

use Illuminate\Auth\GenericUser;
use ZarulIzham\DuitNowQR\DuitNowQR;

beforeEach(function () {
    $reflection = new ReflectionClass(DuitNowQR::class);
    $reflection->setStaticPropertyValue('authCallback', null);
});

it('returns 403 for dashboard index when auth callback is not defined', function () {
    $this->get(route('duitnowqr.dashboard.index'))
        ->assertForbidden();
});

it('allows dashboard index when auth callback returns true', function () {
    app(DuitNowQR::class)->auth(function ($request) {
        return $request->user()
            && in_array($request->user()->email, [
                'smad@yahoo.com',
            ]);
    });

    $this->actingAs(new GenericUser([
        'id' => 1,
        'email' => 'smad@yahoo.com',
    ]));

    $this->get(route('duitnowqr.dashboard.index'))
        ->assertOk();
});

it('returns 403 when auth callback returns false', function () {
    app(DuitNowQR::class)->auth(function ($request) {
        return $request->user()
            && in_array($request->user()->email, [
                'smad@yahoo.com',
            ]);
    });

    $this->actingAs(new GenericUser([
        'id' => 1,
        'email' => 'someone@example.com',
    ]));

    $this->get(route('duitnowqr.dashboard.index'))
        ->assertForbidden();
});
