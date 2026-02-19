<?php

namespace App\Models\Concerns;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToOrganization
{
    public static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            if ($builder->getModel() instanceof User) {
                return;
            }

            $user = Auth::user();

            if (! $user || $user->role === 'super_admin') {
                return;
            }

            if (! $user->organization_id) {
                return;
            }

            $builder->where($builder->getModel()->getTable() . '.organization_id', $user->organization_id);
        });

        static::creating(function ($model) {
            $user = Auth::user();

            if (! empty($model->organization_id)) {
                return;
            }

            if ($user && $user->role !== 'super_admin') {
                $model->organization_id = $user->organization_id;
                return;
            }

            $defaultOrganizationId = Organization::query()->value('id');
            if ($defaultOrganizationId) {
                $model->organization_id = $defaultOrganizationId;
            }
        });
    }

    public function scopeForOrganization(Builder $query, int $organizationId): Builder
    {
        return $query->withoutGlobalScope('organization')
            ->where($query->getModel()->getTable() . '.organization_id', $organizationId);
    }
}
