<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
   protected $fillable = [
    'user_id',
    'name',
    'email',
    'phone',
    'source',
    'status'
];

public function user()
{
    return $this->belongsTo(User::class);
}

public function deal()
{
    return $this->hasOne(Deal::class);
}

public function notes()
{
    return $this->hasMany(Note::class);
}
}
