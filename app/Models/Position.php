<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function users() {
        return $this->hasMany(User::class, 'position_id');
    }

    public function scopeIds($query) {
        return $query->pluck('id');
    }

    public static function report_results($request) {
        return Position::query()
                                ->leftJoin('users', 'users.position_id', '=', 'positions.id')
                                ->leftJoin('documents', 'documents.user_id', '=', 'users.id')
                                ->leftJoin('document_items', 'document_items.document_id', '=', 'documents.id')
                                ->leftJoin('equipment', 'equipment.id', '=', 'document_items.equipment_id')
                                ->leftJoin('equipment_categories', 'equipment_categories.id', '=', 'equipment.equipment_category_id')
                                ->leftJoin('serial_numbers', 'serial_numbers.id', '=', 'document_items.serial_number_id')
                                ->select('positions.name as position_name','equipment_categories.name as eq_cat_name', 'equipment.name as equip_name', 'serial_numbers.serial_number as sn')
                                ->where('document_items.return_date', '=', null)
                                ->when($request->position_ids != null, function($query) use ($request) {
                                    $query->whereIn('positions.id', $request->position_ids);
                                })
                                ->get();
    }
}
