<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CandidatePhotoController extends Controller
{
    public function show(Candidate $candidate): BinaryFileResponse
    {
        $raw = trim((string) ($candidate->photo ?? ''));
        if ($raw === '') {
            abort(404);
        }

        $path = str_replace('\\', '/', ltrim($raw, '/'));
        $resolved = $this->resolvePath($path);

        if (! $resolved) {
            abort(404);
        }

        return response()->file($resolved, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    private function resolvePath(string $path): ?string
    {
        $candidates = [];

        if (Str::startsWith($path, 'uploads/')) {
            $candidates[] = public_path($path);
        }

        if (Str::startsWith($path, 'storage/')) {
            $candidates[] = public_path($path);
            $candidates[] = storage_path('app/public/' . ltrim(Str::after($path, 'storage/'), '/'));
        }

        if (Str::startsWith($path, 'public/')) {
            $afterPublic = ltrim(Str::after($path, 'public/'), '/');
            $candidates[] = storage_path('app/public/' . $afterPublic);
            $candidates[] = public_path($afterPublic);
        }

        $candidates[] = public_path($path);
        $candidates[] = storage_path('app/public/' . $path);
        $candidates[] = storage_path('app/' . $path);
        $candidates[] = public_path('uploads/candidates/' . basename($path));
        $candidates[] = storage_path('app/public/candidates/' . basename($path));

        foreach (array_unique($candidates) as $candidatePath) {
            if (File::exists($candidatePath) && File::isFile($candidatePath)) {
                return $candidatePath;
            }
        }

        return null;
    }
}

