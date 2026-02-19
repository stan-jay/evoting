<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidate extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
    'organization_id',
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

public function organization(): BelongsTo
{
    return $this->belongsTo(Organization::class);
}

}
