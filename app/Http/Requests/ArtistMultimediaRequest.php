<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArtistMultimediaRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'artist_id'   => 'required|exists:artists,id',
            'external_id' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'artist_id.required'   => 'artist_id is required',
            'artist_id.exists'     => 'Artist does not exist',
            'external_id.required' => 'external_id is required',
        ];
    }

}
