<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function positions(){
        return $this->hasMany(Position::class);
    }

    public function users(){
        return $this->hasManyThrough(User::class, Position::class);
    }

    public function scopeIds($query) {
        return $query->pluck('id');
    }

    public static function searchIds($request) {
        if ($request->department_ids != null) {
            return $request->department_ids;
        }  else {
            return Department::query()->ids();
        }
    }

}
