<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrganizationInviteJob;
use App\Models\OrganizationInvite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Throwable;

class InviteController extends Controller
{
    public function index(): View
    {
        $invites = OrganizationInvite::query()
            ->latest()
            ->paginate(30);

        return view('admin.invites.index', compact('invites'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'emails' => ['required', 'string'],
            'role' => ['required', 'string', 'in:voter,officer,admin'],
            'expires_in_days' => ['nullable', 'integer', 'min:1', 'max:30'],
        ]);

        $emails = $this->extractEmails($validated['emails']);
        if ($emails->isEmpty()) {
            return back()->withErrors(['emails' => 'Please provide at least one valid email address.']);
        }

        $expiresAt = now()->addDays((int) ($validated['expires_in_days'] ?? 7));
        $createdCount = 0;
        $sentCount = 0;
        $failedCount = 0;

        foreach ($emails as $email) {
            $exists = OrganizationInvite::query()
                ->pending()
                ->where('email', $email)
                ->exists();

            if ($exists) {
                continue;
            }

            $invite = OrganizationInvite::create([
                'email' => $email,
                'role' => $validated['role'],
                'invited_by' => auth()->id(),
                'expires_at' => $expiresAt,
            ]);

            $createdCount++;

            try {
                SendOrganizationInviteJob::dispatchSync($invite->id);
                $sentCount++;
            } catch (Throwable $e) {
                $failedCount++;
                $invite->forceFill([
                    'send_error' => str($e->getMessage())->limit(1000)->toString(),
                ])->save();
            }
        }

        if ($failedCount > 0) {
            return back()->with('error', "Created {$createdCount} invite(s). {$sentCount} sent, {$failedCount} failed (see Error column).");
        }

        return back()->with('success', "Created {$createdCount} invite(s). {$sentCount} sent.");
    }

    public function resend(OrganizationInvite $invite): RedirectResponse
    {
        if ($invite->accepted_at) {
            return back()->with('error', 'Invite already accepted.');
        }

        $invite->expires_at = now()->addDays(7);
        $invite->send_error = null;
        $invite->save();

        try {
            SendOrganizationInviteJob::dispatchSync($invite->id);

            return back()->with('success', 'Invite refreshed and sent.');
        } catch (Throwable $e) {
            $invite->forceFill([
                'send_error' => str($e->getMessage())->limit(1000)->toString(),
            ])->save();

            return back()->with('error', 'Invite refreshed, but sending failed. Check Error column.');
        }
    }

    private function extractEmails(string $raw): Collection
    {
        return collect(preg_split('/[\s,;]+/', trim($raw)) ?: [])
            ->filter()
            ->map(fn ($email) => strtolower(trim($email)))
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values();
    }
}
