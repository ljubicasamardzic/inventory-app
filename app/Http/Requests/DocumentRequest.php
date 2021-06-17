<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->method() == "POST")
            return $this->storeRules();
        elseif($this->method() == "PUT" || $this->method() == "PATCH")
            return $this->updateRules();
    }

    public function storeRules(){
        return [
            'user_id' => 'required|integer',
            'date' => 'required|date|before_or_equal:today'
        ];
    }

    public function updateRules(){
        return [
            'user_id' => 'required|integer',
            'date' => 'required|date|before_or_equal:today'
        ];
    }

    public function validated()
    {
        return $this->validate($this->rules());
    }

}
