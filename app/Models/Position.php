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

    public static function searchIds($request) {
        if ($request->position_ids != null) {
            return $request->position_ids;
        } else {
            return Position::query()->ids();
        }
    }
}
