<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Ticket;

class DescriptionSuppliesRule implements Rule
{

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
        if ($this->ticket_type == Ticket::NEW_EQUIPMENT && $this->ticket_request_type == Ticket::OFFICE_SUPPLIES_REQUEST) {
            return $value != null;
        } else return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please enter description.';
    }
}
