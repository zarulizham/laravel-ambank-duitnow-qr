<?php

namespace ZarulIzham\DuitNowQR\Exceptions;

use Exception;

class BadRequest extends Exception
{
    public $code;

    public $message;

    public function __construct($message = "", $code = 0) {
        $this->code = $code;
        $this->message = $message;
    }
     /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return false;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'message' => __($this->message),
        ], 400);
    }
}
