<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'searchQuery' => 'required|string', // The search query is optional and should be a string
            'source' => 'nullable|string', // The source is optional and should be a string
            'date' => 'nullable|string', // The date is optional and should be a string
            'category' => 'nullable|string', // The category is optional and should be a string
        ];
    }
}
