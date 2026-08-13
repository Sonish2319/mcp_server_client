<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'content' => [
                'required',
                'string',
            ],

            'author' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}