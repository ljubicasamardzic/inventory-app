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
            "equipment_category_id" => "nullable|exists:equipment_categories,id",
            "description_equipment" => "nullable",
            "quantity" => "nullable|numeric",
            "equipment_id" => "nullable|exists:equipment,id",
            "description_malfunction" => "nullable",
            "document_item_id" => "nullable|exists:document_items,id"
        ];
    }

    public function updateRules() {
        return [
            "officer_id" => "nullable|exists:users,id",
            "officer_approval" => "nullable|exists:request_statuses,id", 
            "officer_remarks" => "nullable", 
            "HR_id" => "nullable|exists:users,id",
            "HR_approval" => "nullable|exists:request_statuses,id",
            "HR_remarks" => "nullable",
            "equipment_id" => "nullable|integer",
            "serial_number_id" => "nullable|exists:serial_numbers,id",
            "description_supplies" => "nullable",
            "document_item_id" => "nullable|exists:document_items,id",
            "equipment_category_id" => "nullable|exists:equipment_categories,id",
            "description_equipment" => "nullable",
            "quantity" => "nullable|numeric",
            "description_malfunction" => "nullable",
            "document_item_id" => "nullable|exists:document_items,id",
            "final_remarks" => "nullable", 
            "price" => "nullable|numeric|min:0",
            "date_finished" => "nullable|date|before_or_equal:today|after_or_equal:created_at",
            "deadline" => "nullable|date|after_or_equal:today"
        ];
    }

    public function validated()
    {
        $validated = $this->validate($this->rules());
        return $validated;
    }
}
