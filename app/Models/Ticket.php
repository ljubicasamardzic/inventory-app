<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $dates = ['deadline', 'date_finished', 'created_at', 'updated_at'];

    const PER_PAGE = 10;

    // protected $primary_key = 'id';
    /** TICKET TYPES **/
    const NEW_EQUIPMENT = 1;
    const REPAIR_EQUIPMENT = 2;

    /** TICKET REQUEST TYPES **/
    const EQUIPMENT_REQUEST = 1;
    const OFFICE_SUPPLIES_REQUEST = 2;

    /** TICKET STATUSES **/
    const UNPROCESSED = 1;
    const IN_PROGRESS = 2;
    const WAITING_FOR_EQUIPMENT = 3;
    const PROCESSED = 4;

    // REQUEST STATUSES
    const PENDING = 1;
    const APPROVED = 2;
    const REJECTED = 3;

    // EQUIPMENT RESERVATIONS
    const RESERVED = 1;

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function officer(){
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function reservation() {
        return $this->hasOne(Reservation::class);
    }

    public function HR() {
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

    public function serial_number() {
        return $this->belongsTo(SerialNumber::class, 'serial_number_id');
    }

    public function equipment() {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function document_item() {
        return $this->belongsTo(DocumentItem::class, 'document_item_id');
    }

    public function equipment_category() {
        return $this->belongsTo(EquipmentCategory::class);
    }

    public function scopeOpen($query){
        return $query->where('status_id', '!=', Ticket::PROCESSED)->get();
    }

    public function scopeOpenUserTickets($query) {
        return $query->where('user_id', auth()->user()->id)
                        ->where('status_id', '!=', Ticket::PROCESSED);
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

    public function isNewEquipmentRequest() {
        return $this->isNewItemsRequest() && $this->isEquipmentRequest();
    }

    public function isOfficerApprovedOrderNewRequest() {
        return $this->officer_approval == Ticket::APPROVED && $this->isNewItemsRequest() && $this->equipment_id == null;
    }

    public function isHRApproved() {
        return $this->HR_approval == Ticket::APPROVED;
    }

    public function isOfficerApproved() {
        return $this->officer_approval == Ticket::APPROVED;
    }

    public function isWaitingForEquipment() {
        return $this->status_id == Ticket::WAITING_FOR_EQUIPMENT;
    }

    public function scopeEquipmentRequests($query) {
        return $query->where('ticket_request_type', Ticket::EQUIPMENT_REQUEST);
    }

    public function scopeSuppliesRequests($query) {
        return $query->where('ticket_request_type', Ticket::OFFICE_SUPPLIES_REQUEST);
    }

    public function scopeReadyForHR($query) {
        return $query->where('officer_approval', '!=', Ticket::PENDING);
    }

    public function scopeSearch($query, $request) {
        return $query->join('users', 'users.id', '=', 'tickets.user_id')
                ->leftJoin('users as u', 'u.id', '=', 'tickets.officer_id')
                ->leftJoin('users as HR', 'HR.id', '=', 'tickets.HR_id')
                ->leftJoin('request_statuses as req_st', 'req_st.id', '=', 'tickets.HR_approval')
                ->leftJoin('request_statuses as rs', 'rs.id', '=', 'tickets.officer_approval')
                ->where(function($query) use ($request) {
                    $query->when($request->search_text, function($query) use($request) { 
                        $term = strtolower($request->search_text);
                        $query->whereRaw("lower(users.name) LIKE '%{$term}%'")
                                ->orWhereRaw("lower(u.name) LIKE '%{$term}%'")
                                ->orWhereRaw("lower(req_st.name) LIKE '%{$term}%'")
                                ->orWhereRaw("lower(rs.name) LIKE '%{$term}%'");
                    });
                })
                ->when($request->ticket_type_id, function($query) use ($request) {
                    $query->where("ticket_type", "=", $request->ticket_type_id);
                })
                ->when($request->search_status_id, function($query) use($request) { 
                    $query->where("status_id", "=", $request->search_status_id);
                })
                // option mine finds both tickets where the user in question is admin or user
                // this way admins can more easily browse through the tickets they sent within one table 
                ->where(function($query) use ($request) {
                    $query->when($request->search_checkbox, function($query) use($request) {
                        $query->where("officer_id", "=", $request->search_checkbox)
                                ->orWhere("HR_id", "=", $request->search_checkbox)
                                ->orWhere("user_id", "=", $request->search_checkbox);
                    });
                })
                ->select('tickets.*');
    }

    public function scopeProcessedRepairTickets($query, $request) {
        return $query->join('document_items', 'document_items.id', '=', 'tickets.document_item_id')
                        ->join('equipment', 'equipment.id', '=', 'document_items.equipment_id')
                        ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment.equipment_category_id')
                        ->where('ticket_type', '=', Ticket::REPAIR_EQUIPMENT)
                        ->where('status_id', '=', Ticket::PROCESSED)
                        ->where('HR_approval', '=', Ticket::APPROVED)
                        ->where(function($query) use ($request) {
                            $query->when($request->equipment_search, function($query) use ($request) {
                                $term = strtolower($request->equipment_search);
                                $query->whereRaw("lower(equipment.name) LIKE '%{$term}%'")
                                        ->orWhereRaw("lower(equipment_categories.name) LIKE '%{$term}%'");
                            });
                        })
                        ->select('tickets.*')
                        ->paginate(Ticket::PER_PAGE);
    }
    
    public function createDocument() {
        return Document::create([
            'user_id' => $this->user_id,
            'admin_id' => $this->officer_id,
            'date' => Carbon::now()->timestamp
        ]);
    }

    public function createDocumentItem($document, $equipment_id, $serial_number_id) {
        return DocumentItem::create([
            'document_id' => $document->id,
            'equipment_id' => $equipment_id,
            'serial_number_id' => $serial_number_id            
        ]);
    }
    
}
