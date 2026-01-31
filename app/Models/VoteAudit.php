<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteAudit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_hash',
        'election_id',
        'position_id',
        'voted_at',
    ];
}
