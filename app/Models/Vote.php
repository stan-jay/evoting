<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'user_id',
        'election_id',
        'position_id',
        'candidate_id',
    ];

    // 🔗 Each vote belongs to a candidate
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    // 🔗 Each vote belongs to a position
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // 🔗 Each vote belongs to an election
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    // 🔗 Each vote belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
