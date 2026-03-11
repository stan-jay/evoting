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

    protected $appends = [
        'photo_url',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        // New uploads are saved directly under public/uploads/...
        if (str_starts_with($this->photo, 'uploads/')) {
            return asset($this->photo);
        }

        // Backward compatibility for older records stored on public disk.
        return asset('storage/' . ltrim($this->photo, '/'));
    }
}
