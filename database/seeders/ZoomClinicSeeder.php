<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ZoomClinicSeeder extends Seeder
{
    private const IST = 'Asia/Kolkata';

    /**
     * Seed sample zoom clinics on Tuesdays & Thursdays only (6:30–7:30 PM IST).
     * Admin enters IST; stored as UTC. See docs/knowledge-graph.md §9.
     */
    public function run(): void
    {
        $sessions = [
            [
                'title' => 'Live Website Reviews & Open Q&A',
                'agenda' => 'Live Website Reviews & Open Q&A',
                'description' => 'Stuck on small things? Drop into our live Zoom session to get free help and learn Brilliant Directories.',
                'is_featured' => 1,
                'sort_order' => 1,
            ],
            [
                'title' => 'Open Q&A — Brilliant Directories Basics',
                'agenda' => 'Open Q&A — Brilliant Directories Basics',
                'description' => 'Bring your dashboard questions, widget tweaks, and “why isn’t this working?” moments. Our devs answer live.',
                'is_featured' => 0,
                'sort_order' => 2,
            ],
            [
                'title' => 'Member Dashboard Troubleshooting',
                'agenda' => 'Member Dashboard Troubleshooting',
                'description' => 'Profile completion flows, checklist widgets, and member onboarding fixes — live with our BD developers.',
                'is_featured' => 0,
                'sort_order' => 3,
            ],
            [
                'title' => 'Widget & Custom CSS Clinic',
                'agenda' => 'Widget & Custom CSS Clinic',
                'description' => 'Homepage blocks, custom CSS overrides, and widget placement — show your screen, we’ll guide you through it.',
                'is_featured' => 0,
                'sort_order' => 4,
            ],
            [
                'title' => 'Search & Filter Setup Help',
                'agenda' => 'Search & Filter Setup Help',
                'description' => 'Category filters, map search, and discovery UX — get your directory findability sorted in one session.',
                'is_featured' => 0,
                'sort_order' => 5,
            ],
            [
                'title' => 'Email Template & Automation Fixes',
                'agenda' => 'Email Template & Automation Fixes',
                'description' => 'Transactional emails, drip logic, and notification templates — small fixes that save hours of guesswork.',
                'is_featured' => 0,
                'sort_order' => 6,
            ],
        ];

        $dates = $this->nextTueThuDates(count($sessions));

        DB::table('bdgs_zoom_clinic_registrations')->delete();
        DB::table('bdgs_zoom_clinics')->delete();

        $clinicIds = [];

        foreach ($sessions as $index => $session) {
            $istDate = $dates[$index];
            $slug = Str::slug($session['title']).'-'.$istDate->format('M-j-Y');

            $startsAt = $istDate->copy()->setTime(18, 30, 0)->utc();
            $endsAt = $istDate->copy()->setTime(19, 30, 0)->utc();
            $bufferEndsAt = $istDate->copy()->setTime(20, 30, 0)->utc();

            $clinicIds[$index] = DB::table('bdgs_zoom_clinics')->insertGetId([
                'slug' => strtolower($slug),
                'title' => $session['title'],
                'agenda' => $session['agenda'],
                'description' => $session['description'],
                'session_starts_at' => $startsAt,
                'session_ends_at' => $endsAt,
                'buffer_ends_at' => $bufferEndsAt,
                'source_timezone' => self::IST,
                'format_note' => '60-min open Q&A with our devs',
                'zoom_meeting_url' => '',
                'max_capacity' => null,
                'status' => 'scheduled',
                'is_published' => 1,
                'is_featured' => $session['is_featured'],
                'sort_order' => $session['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ], 'clinic_id');
        }

        $registrations = [
            [0, 'Sarah Mitchell', 'sarah.m@example.com', 'https://example-directory.com', 'Homepage widget alignment'],
            [0, 'James Okonkwo', 'j.okonkwo@example.com', '', 'Member signup flow'],
            [0, 'Priya Sharma', 'priya.s@example.com', 'https://localpros.example', ''],
            [0, 'Marcus Chen', 'marcus.c@example.com', '', 'Search filters not showing'],
            [0, 'Emily Rodriguez', 'emily.r@example.com', 'https://findexperts.example', 'Custom CSS on join page'],
            [1, 'David Park', 'david.p@example.com', '', 'Category structure'],
            [1, 'Anna Kowalski', 'anna.k@example.com', 'https://bizlist.example', ''],
            [1, 'Tom Hughes', 'tom.h@example.com', '', 'Payment gateway setup'],
            [2, 'Lisa Nguyen', 'lisa.n@example.com', 'https://proconnect.example', 'Profile checklist widget'],
            [2, 'Robert Walsh', 'rob.w@example.com', '', ''],
            [2, 'Nina Patel', 'nina.p@example.com', 'https://membershub.example', 'Incomplete profile reminders'],
            [3, 'Chris Anderson', 'chris.a@example.com', '', 'Hero section CSS'],
            [3, 'Maria Santos', 'maria.s@example.com', 'https://styledir.example', ''],
            [4, 'Kevin O\'Brien', 'kevin.ob@example.com', 'https://mapfind.example', 'Map radius search'],
            [4, 'Yuki Tanaka', 'yuki.t@example.com', '', ''],
            [4, 'Helen Brooks', 'helen.b@example.com', 'https://filterpro.example', 'Multi-select filters'],
            [4, 'Ahmed Hassan', 'ahmed.h@example.com', '', ''],
            [5, 'Jennifer Lee', 'jen.lee@example.com', 'https://notifydir.example', 'Welcome email series'],
            [5, 'Paul Morrison', 'paul.m@example.com', '', ''],
        ];

        foreach ($registrations as [$clinicIndex, $name, $email, $directoryUrl, $helpTopic]) {
            DB::table('bdgs_zoom_clinic_registrations')->insert([
                'clinic_id' => $clinicIds[$clinicIndex],
                'name' => $name,
                'email' => $email,
                'directory_url' => $directoryUrl,
                'help_topic' => $helpTopic,
                'registrant_timezone' => 'America/New_York',
                'status' => 'confirmed',
                'registered_at' => now()->subDays(random_int(1, 14)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * @return array<int, Carbon>
     */
    private function nextTueThuDates(int $count): array
    {
        $dates = [];
        $cursor = Carbon::now(self::IST)->startOfDay();

        while (count($dates) < $count) {
            if ($cursor->isTuesday() || $cursor->isThursday()) {
                $dates[] = $cursor->copy();
            }
            $cursor->addDay();
        }

        return $dates;
    }
}
