<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /** ROLES **/
    const ADMINISTRATOR = 1;
    const USER = 2;
    const SUPPORT_OFFICER = 3;
    const ADMINISTRATIVE_OFFICER = 4;
    const HR = 5;

    protected $fillable = [
        'name',
        'email',
        'password',
        'position_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getDepartmentIdAttribute(){
        return Position::query()->find($this->position_id)->department_id;
    }

    public function getDepartmentNameAttribute(){
        return Department::query()->find($this->department_id)->name;
    }

    public function position(){
        return $this->belongsTo(Position::class);
    }

    public function documents(){
        return $this->hasMany(Document::class);
    }

    public function items(){
        return $this->hasManyThrough(DocumentItem::class, Document::class);
    }

    public function current_items(){
        return $this->hasManyThrough(DocumentItem::class, Document::class)->where('return_date', null);
    }

    // admin u ovom smislu obuhvata sve korisnike koji nisu zaposleni
    public function isAdmin() {
        return in_array($this->role_id, [User::ADMINISTRATOR, User::SUPPORT_OFFICER, User::ADMINISTRATIVE_OFFICER, User::HR]);
    }

    public function isSuperAdmin() {
        return $this->role_id == User::ADMINISTRATOR;
    }

    public function isHR() {
        return $this->role_id == User::HR;
    }

    public function isSupportOfficer() {
        return $this->role_id == User::SUPPORT_OFFICER;
    }

    public function isAdministrativeOfficer() {
        return $this->role_id == User::ADMINISTRATIVE_OFFICER;
    }

    public function isEmployee() {
        return $this->role_id == User::USER;
    }

    public function scopeIds($query) {
        return $query->pluck('id');
    }

    public function notifications()
    {
        // override the standard of returning them by created_at ASC
        return $this->morphMany(DatabaseNotification::class, 'notifiable')
                            ->orderBy('read_at', 'asc')
                            ->orderBy('created_at', 'desc');
    }
    public static function searchIds($request) {
        if ($request->employee_ids != null) {
            return $request->employee_ids;
        } else {
            return User::query()->ids();
        }
    }

}
