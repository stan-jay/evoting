<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    //
    protected $fillable = [
    'title',
    'description',
    'start_time',
    'end_time',
    'status',
];

    public function positions()
{
    return $this->hasMany(Position::class);
}

}
