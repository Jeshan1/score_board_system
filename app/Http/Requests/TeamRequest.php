<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            
        ];
        // $team = $this->route('team');
        // $teamId = is_object($team) ? $team->id : $team;

        // return [
        //     'name' => 'required|string|max:255|unique:teams,name,' . $teamId,
        //     'league_id' => 'required|exists:leagues,id',
        //     'logo' => 'nullable|string',
        //     'manager_id' => 'required|exists:users,id',
        //     'players' => 'sometimes|array',
        //     'players.*.id' => 'sometimes|exists:players,id',
        //     'players.*.name' => 'sometimes|required|string',
        //     'players.*.number' => 'nullable|integer',
        //     'players.*.position' => 'nullable|string',
        // ];


    }
}
