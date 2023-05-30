<?php

namespace App\Http\Requests;

use App\Services\News\FetchNewsService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePreferencesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'preferences.category' => ['required', 'array'],
            'preferences.category.*' => "integer",
            'preferences.author' => ['required', 'array'],
            'preferences.source' => ['required', 'array'],
        ];
    }
}
