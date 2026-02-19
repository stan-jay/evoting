# Evoting Platform

Evoting is a multi-tenant Laravel election platform for campuses and organizations.
A single deployment serves many institutions, with strict organization-level data isolation.

## Core Capabilities
- Multi-tenant organization onboarding (super admin)
- Invite-only registration (organization-bound tokens)
- Role-based access: `super_admin`, `admin`, `officer`, `voter`
- Election lifecycle: pending -> active -> closed -> declared
- Result analytics with charts (admin/officer)
- Result publication gating (voters see only when declared)
- PDF and Excel (CSV) exports for closed/declared elections
- Auditable vote logging

## Tenant Isolation Model
All sensitive entities are organization-scoped:
- `users`, `elections`, `positions`, `candidates`, `votes`, `vote_audits`, `organization_invites`

Isolation is enforced by:
1. `organization_id` on core records
2. model-level tenant global scopes
3. role + route authorization

## Dashboard Matrix
- Super Admin Dashboard: organization onboarding, global user intervention, docs
- Admin Dashboard: manage elections/users/candidates/results/invites within organization
- Officer Dashboard: manage positions/candidates and see analytics
- Voter Dashboard: vote and see published results

## Results Rules
- Admin/officer can view analytics while election is `active`, `closed`, or `declared`
- Voters can view results only when election is `declared`
- Admin can publish (`closed -> declared`) and unpublish (`declared -> closed`)

## Candidate Ballot UX
- Ballot remains compact for voters
- Candidate cards show name + image thumbnail
- Manifesto/details are on a linked profile page

## Invite-Only Registration
- New users join via `/register/{token}` only
- Invite token binds user to organization and role
- Invite sending is queued (`SendOrganizationInviteJob`)

## Security and Hardening
### Rate Limits
- Vote submission: `throttle:vote-submit`
- Invite create/resend: `throttle:invite-create`
- Live analytics polling: `throttle:results-live`

### Graceful DB Failure
Database connection failures are converted to a clean `503` page (`DB_UNAVAILABLE`) instead of raw `500`.

### Recommended Runtime
- `APP_ENV=production`
- `APP_DEBUG=false`
- `SESSION_DRIVER=database|redis`
- `QUEUE_CONNECTION=database|redis`
- Run queue worker continuously

## Backups
### Command
```bash
php artisan app:backup-db --retention=14
```
Creates DB backup into `storage/app/backups` and prunes old files.

### Scheduler
- daily env sanity check
- daily DB backup
- election status updater every minute

Run scheduler (production):
```bash
php artisan schedule:work
```

## Environment Sanity Check
### Command
```bash
php artisan app:env-sanity-check
php artisan app:env-sanity-check --strict
```
Checks critical production env settings and mail/db basics.

## Deployment Checklist
1. Set production env values in `.env`
2. Rotate secrets and mail credentials
3. Run migrations:
```bash
php artisan migrate --force
```
4. Build and cache:
```bash
php artisan optimize
```
5. Start workers:
```bash
php artisan queue:work --tries=3
php artisan schedule:work
```
6. Validate health:
- login for each role
- vote flow
- publish/unpublish results
- export PDF/Excel
- invite creation and email sending

## Super Admin Docs Access
In-app documentation is available only through super admin routes:
- `super_admin.docs.show` (`/super-admin/docs`)

## Test Suite
Run all tests:
```bash
php artisan test
```
