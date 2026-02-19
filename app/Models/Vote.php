<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
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

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
