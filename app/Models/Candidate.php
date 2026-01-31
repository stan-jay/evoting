<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    //

    protected $fillable = [
    'position_id',
    'name',
    'manifesto',
    'photo',
    'status',
];

    public function position()
{
    return $this->belongsTo(Position::class);
}

}
