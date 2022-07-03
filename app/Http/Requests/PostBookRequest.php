<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // if (Auth::check() && Auth::user()->is_admin) {
        //     return true;
        // }
        
        // return false;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // @TODO implement
        return [
            "isbn"              => "required|numeric|unique:books,isbn|digits:13",
            "title"             => "required",
            "description"       => "required",
            "authors"           => 'required|array',
            "authors.*"         => "exists:authors,id",
            "published_year"    => "required|numeric|min:1900|max:2020"
        ];
    }
}
