<?php

namespace App\Rules;

use App\Models\SerialNumber;
use Illuminate\Contracts\Validation\Rule;

class UniqueSerialNoRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($equipment_id)
    {
        $this->equipment_id = $equipment_id;
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
        $serial_nums = SerialNumber::query()->where('equipment_id', $this->equipment_id)->pluck('serial_number')->all();
        return !in_array($value, $serial_nums);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The serial number must be unique.';
    }
}
