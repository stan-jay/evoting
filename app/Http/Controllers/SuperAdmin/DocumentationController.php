<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentationController extends Controller
{
    public function show(): View
    {
        $readme = file_exists(base_path('README.md'))
            ? file_get_contents(base_path('README.md'))
            : '# Documentation Not Found';

        $html = Str::markdown($readme, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return view('super_admin.docs.show', compact('html'));
    }
}
