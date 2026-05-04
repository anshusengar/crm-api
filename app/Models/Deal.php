<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
protected $fillable = [
    'lead_id',
    'value',
    'stage',
    'expected_close_date'
];

public function lead()
{
    return $this->belongsTo(Lead::class);
}

public function notes()
{
    return $this->hasMany(Note::class);
}
}
