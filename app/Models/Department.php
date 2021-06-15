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
}
