<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function equipment(){
        return $this->hasMany(Equipment::class);
    }

    public function available_equipment(){
        return $this->hasMany(Equipment::class)->available();
    }
}
