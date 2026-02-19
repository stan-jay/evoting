<?php

namespace App\Jobs;

use App\Mail\OrganizationInviteMail;
use App\Models\OrganizationInvite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendOrganizationInviteJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $inviteId)
    {
    }

    public function handle(): void
    {
        $invite = OrganizationInvite::query()
            ->withoutGlobalScope('organization')
            ->with('organization')
            ->find($this->inviteId);

        if (! $invite || $invite->accepted_at) {
            return;
        }

        Mail::to($invite->email)->send(new OrganizationInviteMail($invite));

        $invite->forceFill([
            'invite_sent_at' => now(),
            'send_error' => null,
        ])->save();
    }

    public function failed(Throwable $exception): void
    {
        $invite = OrganizationInvite::query()
            ->withoutGlobalScope('organization')
            ->find($this->inviteId);

        if (! $invite) {
            return;
        }

        $invite->forceFill([
            'send_error' => str($exception->getMessage())->limit(1000)->toString(),
        ])->save();
    }
}
