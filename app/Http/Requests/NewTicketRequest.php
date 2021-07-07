<?php

namespace App\Http\Requests;

use App\Rules\DescriptionSuppliesRule;
use App\Rules\QuantityRule;
use App\Rules\EquipmentCategoryRule;
use App\Rules\DescriptionEquipmentRule;
use App\Rules\DocumentItemRule;
use App\Rules\DescriptionMalfunctionRule;

use Illuminate\Foundation\Http\FormRequest;

class NewTicketRequest extends FormRequest
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
            'ticket_type' => 'required|integer',
            'ticket_request_type' => 'required|integer',
            'description_supplies' => [new DescriptionSuppliesRule($this->ticket_type, $this->ticket_request_type)],
            'quantity' => [new QuantityRule($this->ticket_type, $this->ticket_request_type)],
            'equipment_category_id' => [new EquipmentCategoryRule($this->ticket_type, $this->ticket_request_type)],
            'description_equipment' => [new DescriptionEquipmentRule($this->ticket_type, $this->ticket_request_type)], 
            'document_item_id' => [new DocumentItemRule($this->ticket_type, $this->ticket_request_type)],
            'description_malfunction' => [new DescriptionMalfunctionRule($this->ticket_type, $this->ticket_request_type)]
        ];
    }

    public function updateRules() {

    }
}
