<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    protected $sometimes = '';

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        if (request()->method() == 'PUT' || request()->method() == 'PATCH') {
            $this->sometimes = 'sometimes|';
        }
    }

    public function authorize()
    {
        return true;
    }
}
