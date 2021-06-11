<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $dates = ['deadline', 'date_finished', 'created_at', 'updated_at'];

    /** TICKET TYPES **/
    const NEW_EQUIPMENT = 1;
    const REPAIR_EQUIPMENT = 2;

    /** TICKET REQUEST TYPES **/
    const EQUIPMENT_REQUEST = 1;
    const OFFICE_SUPPLIES_REQUEST = 2;

    /** TICKET STATUSES **/
    const UNPROCESSED = 1;
    const IN_PROGRESS = 2;
    const PROCESSED = 3;

    // REQUEST STATUSES
    const PENDING = 1;
    const APPROVED = 2;
    const REJECTED = 3;

    const dates = ['created_at', 'updated_at'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function officer(){
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function admin() {
        return $this->belongsTo(User::class, 'HR_id');
    }

    public function status(){
        return $this->belongsTo(TicketStatus::class);
    }

    public function officerApproval() {
        return $this->belongsTo(RequestStatus::class, 'officer_approval');
    }

    public function HRApproval() {
        return $this->belongsTo(RequestStatus::class, 'HR_approval');
    }

    public function equipment() {
        return $this->belongsTo(Equipment::class);
    }

    public function equipment_category() {
        return $this->belongsTo(EquipmentCategory::class);
    }

    public function scopeOpen($query){
        return $query->where('status_id', '!=', Ticket::PROCESSED)->get();
    }

    public function getDateAttribute() {
        return $this->created_at->format('d.m.Y');
    }

    public function getFinishedDateAttribute() {
        return $this->date_finished->format('d.m.Y');
    }

    public function getFormattedDeadlineAttribute() {
        return $this->deadline->format('d.m.Y');
    }

    public function isEquipmentRequest() {
        return $this->ticket_request_type == Ticket::EQUIPMENT_REQUEST;
    }

    public function isSuppliesRequest() {
        return $this->ticket_request_type == Ticket::OFFICE_SUPPLIES_REQUEST;
    }

    public function isRepairRequest() {
        return $this->ticket_type == Ticket::REPAIR_EQUIPMENT;
    }

    public function isNewItemsRequest() {
        return $this->ticket_type == Ticket::NEW_EQUIPMENT;
    }

    public function scopeEquipmentRequests($query) {
        return $query->where('ticket_request_type', Ticket::EQUIPMENT_REQUEST)->get();
    }

    public function scopeSuppliesRequests($query) {
        return $query->where('ticket_request_type', Ticket::OFFICE_SUPPLIES_REQUEST)->get();
    }

    public function scopeReadyForHR($query) {
        return $query->where('officer_approval', '!=', Ticket::PENDING)
                        ->where('status_id', '!=', Ticket::PROCESSED)
                        ->get();
    }
    
}
