<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
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
            'equipment_category_id' => 'required|integer',
            'available_quantity' => 'required|integer|min:0',
            'description' => 'nullable'
        ];
    }

    // za sad ista pravila kao i za dodavanje
    public function updateRules(){
        return [
            'name' => 'required|min:3|max:255',
            'equipment_category_id' => 'required|integer',
            'available_quantity' => 'required|integer|min:0',
            'description' => 'nullable'
        ];
    }

    public function validated()
    {
        /* dodatna obrada zahtjeva ide ovdje */
        /* npr. upload slike ili hash-iranje password-a */
        return $this->validate($this->rules());
    }
}
