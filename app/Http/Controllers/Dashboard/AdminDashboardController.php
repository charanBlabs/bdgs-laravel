<?php



namespace App\Http\Controllers\Dashboard;



use App\Http\Controllers\Controller;

use App\Models\BdgsDataPost;

use App\Models\BdgsInquiry;

use App\Models\User;

use App\Services\EmailService;

use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;

use Illuminate\View\View;



class AdminDashboardController extends Controller

{

    public function overview(): View

    {

        return view('dashboard.admin.overview', [

            'stats' => [

                'users' => User::query()->count(),

                'posts' => BdgsDataPost::query()->count(),

                'published' => BdgsDataPost::query()->where('status', 'published')->count(),

                'inquiries' => BdgsInquiry::query()->count(),

                'new_inquiries' => BdgsInquiry::query()->where('status', 'new')->count(),

            ],

        ]);

    }



    public function inquiries(): View

    {

        return view('dashboard.admin.inquiries.index', [

            'inquiries' => BdgsInquiry::query()->latest('submitted_at')->paginate(25),

        ]);

    }



    public function showInquiry(BdgsInquiry $inquiry): View

    {

        return view('dashboard.admin.inquiries.show', compact('inquiry'));

    }



    public function replyInquiry(Request $request, BdgsInquiry $inquiry, EmailService $emailService): RedirectResponse

    {

        $validated = $request->validate([

            'admin_reply' => ['required', 'string', 'max:4000'],

        ]);



        $inquiry->update([

            'admin_reply' => $validated['admin_reply'],

            'status' => 'replied',

        ]);



        $emailService->send('inquiry-reply', $inquiry->email, [

            'first_name' => strtok($inquiry->name, ' ') ?: $inquiry->name,

            'name' => $inquiry->name,

            'reply' => $validated['admin_reply'],

            'site_name' => config('app.name'),

        ]);



        if ($inquiry->user_id) {

            $user = User::query()->find($inquiry->user_id);

            if ($user) {

                $emailService->notify($user, 'inquiry.reply', 'Reply to your inquiry', $validated['admin_reply']);

            }

        }



        return back()->with('status', 'Reply sent.');

    }

}

