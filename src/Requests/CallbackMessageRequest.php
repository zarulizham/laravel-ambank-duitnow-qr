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

    public function data()
    {
        return Callback::from($this->all());
    }
}
