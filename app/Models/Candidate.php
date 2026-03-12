<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

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

        $photo = str_replace('\\', '/', trim((string) $this->photo));
        if ($photo === '') {
            return null;
        }

        if (Str::startsWith($photo, ['http://', 'https://'])) {
            return $photo;
        }

        return route('media.candidates.photo', $this);
    }
}
