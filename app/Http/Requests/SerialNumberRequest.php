<?php

namespace App\Http\Requests;

use App\Rules\UniqueSerialNoRule;
use App\Rules\SerialNumberLengthRule;
use Illuminate\Foundation\Http\FormRequest;

class SerialNumberRequest extends FormRequest
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
        if ($this->method() == 'POST') {
            return $this->storeRules();
        } else if ($this->method() == 'PUT' || $this->method() == 'PATCH') {
            return $this->updateRules();
        }
    }

    public function storeRules() {
        return [
            'equipment_id' => 'required|integer',
            'serial_numbers.*' => [new UniqueSerialNoRule($this->equipment_id), new SerialNumberLengthRule]
        ];
    }

    public function updateRules() {
        return [
            'serial_number' => 'required|alpha_num|min:3',
            'id' => 'required|integer'
        ];
    }

    public function validated()
    {
        return $this->validate($this->rules());
    }
}
