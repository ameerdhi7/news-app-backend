<?php

namespace App\Http\Requests;

use App\Services\News\FetchNewsService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categories' => ['array'],
            'categories.*' => [
                'required',
                Rule::exists('preference_options', 'id')->where('type', 'category')
            ],
            'authors' => ['array'],
            'authors.*' => [
                'required',
                Rule::exists('preference_options', 'id')->where('type', 'author')
            ],
            'sources' => ['array'],
            'sources.*' => [
                'required',
                Rule::exists('preference_options', 'id')->where('type', 'source')
            ],
        ];
    }
}
