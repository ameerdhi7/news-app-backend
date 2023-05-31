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

    public function rules(): array
    {
        return [
            'preferences.category' => ['array'],
            'preferences.category.*' => [
                'required',
                Rule::exists('preference_options', 'id')->where('type', 'category')
            ],
            'preferences.author' => ['array'],
            'preferences.author.*' => [
                'required',
                Rule::exists('preference_options', 'id')->where('type', 'author')
            ],
            'preferences.source' => ['array'],
            'preferences.source.*' => [
                'required',
                Rule::exists('preference_options', 'id')->where('type', 'source')
            ],
        ];
    }
}
