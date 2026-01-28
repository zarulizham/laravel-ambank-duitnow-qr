<?php

namespace ZarulIzham\DuitNowQR\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ZarulIzham\DuitNowQR\Data\Callback;

class CallbackMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function data($key = null, $default = null)
    {
        return Callback::from($this->all());
    }
}
