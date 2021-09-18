<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SerialNumberLengthRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

     // make the length at least 3 characters and make it an int 
    public function passes($attribute, $value)
    {
        return strlen((string)$value) >= 3 && is_int((int)$value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The number must be at least three characters.';
    }
}
