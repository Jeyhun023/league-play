<?php

namespace App\Http\Requests\Api\Game;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->game && 
            $this->cookie('session') === $this->game->championship->session;
    }

    public function rules(): array
    {
        return [
            'homeScore' => 'required|integer|min:0',
            'awayScore' => 'required|integer|min:0',
        ];
    }
}
