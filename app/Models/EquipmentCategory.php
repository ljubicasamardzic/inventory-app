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

    public function scopeIds($query) {
        return $query->pluck('id');
    }

    
    public static function searchIds($request) {
        if ($request->category_ids != null) {
            return $request->category_ids;
        } else {
            return EquipmentCategory::query()->ids()->toArray();
        }
    }
}
