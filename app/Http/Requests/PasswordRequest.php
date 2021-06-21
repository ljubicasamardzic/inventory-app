<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class PasswordRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required|integer',
            'old_password' => 'required|password',
            'new_password' => 'required|same:repeated_password|min:8|max:255',
            'repeated_password' => 'required|min:8|max:255' 
        ];
    }

    public function validated() {
        $validated = $this->validate($this->rules());
        $validated['new_password'] = Hash::make($validated['new_password']);
        return $validated;
    }
}
