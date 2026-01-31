<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VoteAudit;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = VoteAudit::orderBy('voted_at', 'desc')
            ->paginate(50);

        return view('admin.audit.index', compact('logs'));
    }
}
