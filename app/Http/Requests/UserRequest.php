<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->method() == "POST")
            return $this->storeRules();
        elseif($this->method() == "PUT" || $this->method() == "PATCH")
            return $this->updateRules();
    }

    public function storeRules(){
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8|max:255',
            'position_id' => 'required|integer',
            'department_id' => 'nullable',
        ];
    }

    public function updateRules(){
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email',
            'password' => 'nullable',
            'position_id' => 'required|integer',
            'department_id' => 'nullable',
        ];
    }

    public function validated()
    {
        $validated = $this->validate($this->rules());
        if($validated['password'])
            $validated['password'] = Hash::make($validated['password']);
        return $validated;
    }
}
