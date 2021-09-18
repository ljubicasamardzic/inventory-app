<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    const PER_PAGE = 10;
    protected $guarded = [];

    // Reports
    const BY_DEPARTMENT = 1;
    const BY_POSITION = 2;
    const BY_CATEGORY = 3;
    const BY_USER = 4;

    public function category(){
        return $this->belongsTo(EquipmentCategory::class, 'equipment_category_id');
    }

    public function serial_numbers(){
        return $this->hasMany(SerialNumber::class);
    }

    public function document_items() {
        return $this->hasMany(DocumentItem::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function getShortDescriptionAttribute(){
        if(strlen($this->description) < 25) return $this->description;
        else return substr($this->description, 0, 25).'...';
    }

    public function getFullNameAttribute(){
        return $this->category->name." - ".$this->name;
    }

    public function scopeAvailable($query){
        return $query->where('available_quantity', '>', 0);
    }

    public function getAssignedAttribute() {
        return DocumentItem::query()    
                                ->where('equipment_id', $this->id)
                                ->where('return_date', null)
                                ->count();
    }

    public function getTotalQuantityAttribute() {
        return $this->available_quantity + $this->assigned;
    }

    public function getRequiredInputFieldsAttribute() {
        return $this->total_quantity - $this->serial_numbers->count();
    }

    public function scopeIds($query) {
        return $query->pluck('id');
    }

    public static function searchIds($request) {
        if ($request->equipment_ids != null) {
            return $request->equipment_ids;
        } else {
            return Equipment::query()->ids();
        }
    }

    public function getNumberOfReservationsAttribute() {
        return Reservation::query()
                        ->leftJoin('tickets', 'tickets.id', '=', 'reservations.ticket_id')
                        ->where('tickets.equipment_id', '=', $this->id)
                        ->count();
    }
}

