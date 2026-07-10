<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdgsActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = BdgsActivityLog::query()->with('user')->latest('created_at');

        if ($action = $request->string('action')->trim()) {
            $query->where('action', $action);
        }

        if ($userId = $request->integer('user_id')) {
            $query->where('user_id', $userId);
        }

        return view('admin.activity.index', [
            'logs' => $query->paginate(50),
            'action' => $action ?? '',
        ]);
    }
}
