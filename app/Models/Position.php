<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    //
    protected $fillable = [
    'election_id',
    'name',
];

    public function election()
{
    return $this->belongsTo(Election::class);
}

public function position()
{
    return $this->belongsTo(Position::class);
}


public function candidates()
{
    return $this->hasMany(Candidate::class);
}

}
