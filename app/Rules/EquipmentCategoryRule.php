<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Ticket;

class EquipmentCategoryRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($ticket_type, $ticket_request_type)
    {
        $this->ticket_type = $ticket_type;
        $this->ticket_request_type = $ticket_request_type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->ticket_type == Ticket::NEW_EQUIPMENT && $this->ticket_request_type == Ticket::EQUIPMENT_REQUEST) {
            return $value != null && is_int((int)$value);
        } else return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please choose an equipment category.';
    }
}
