<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeagueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // if($this->method() == 'GET' || $this->method() == 'DELETE'){
        //     return [];
        // }

        $default_rule = [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
        $extra_rule = [];
        if($this->method() == 'PUT')
        {
            $extra_rule = [
                'id' => 'required|exists:leagues,id'
            ];

        }

        return array_merge($default_rule, $extra_rule);
    }
}
