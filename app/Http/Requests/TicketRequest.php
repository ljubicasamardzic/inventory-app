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

    public function validated()
    {
        $validated = $this->validate($this->rules());
        return $validated;
    }
}
