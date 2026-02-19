<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
    'organization_id',
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

public function organization(): BelongsTo
{
    return $this->belongsTo(Organization::class);
}

}
