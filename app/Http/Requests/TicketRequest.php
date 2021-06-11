<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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

    public function storeRules() {
        return [
            "ticket_type" => "required|integer",
            "ticket_request_type" => "required|integer",
            "description_supplies" => "nullable",
            "equipment_category_id" => "nullable|integer",
            "description_equipment" => "nullable",
            "quantity" => "nullable|numeric",
            "equipment_id" => "nullable|integer",
            "description_malfunction" => "nullable"
        ];
    }

    public function updateRules() {
        return [
            "officer_id" => "nullable|integer",
            "HR_id" => "nullable|integer",
            "HR_approval" => "nullable|integer",
            "officer_approval" => "nullable|integer"
        ];
    }

    public function validated()
    {
        $validated = $this->validate($this->rules());
        return $validated;
    }
}
