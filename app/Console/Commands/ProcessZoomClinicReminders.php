<?php

namespace App\Console\Commands;

use App\Services\ZoomClinicMailService;
use Illuminate\Console\Command;

class ProcessZoomClinicReminders extends Command
{
    protected $signature = 'zoom-clinics:send-reminders';

    protected $description = 'Retry unsent Zoom Clinic confirmations, then send 24h/1h reminders';

    public function handle(ZoomClinicMailService $mail): int
    {
        $results = $mail->processDueReminders();

        $this->info(
            "Zoom clinic mail — confirmations: {$results['confirmations']}, "
            ."24h: {$results['reminder_24h']}, 1h: {$results['reminder_1h']}"
        );

        return self::SUCCESS;
    }
}
