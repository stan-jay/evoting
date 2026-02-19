<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrganizationInvite extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'email',
        'name',
        'role',
        'token',
        'invited_by',
        'expires_at',
        'accepted_at',
        'invite_sent_at',
        'send_error',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'invite_sent_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $invite) {
            if (empty($invite->token)) {
                $invite->token = Str::random(64);
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('accepted_at');
    }

    public function scopeValidToken(Builder $query, string $token): Builder
    {
        return $query->where('token', $token)
            ->whereNull('accepted_at')
            ->where(function (Builder $nested) {
                $nested->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }
}
