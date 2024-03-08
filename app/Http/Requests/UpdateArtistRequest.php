<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArtistRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'name'  => 'required|string',
            'about' => 'required|string',
            'image' => 'image|mimes:jpg,jpeg,png',
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'name is required',
            'about.required' => 'about is required',
        ];
    }

}
