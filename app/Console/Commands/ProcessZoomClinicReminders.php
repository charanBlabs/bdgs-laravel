<?php

namespace App\Console\Commands;

use App\Services\ZoomClinicMailService;
use App\Services\ZoomClinicService;
use Illuminate\Console\Command;

class ProcessZoomClinicReminders extends Command
{
    protected $signature = 'zoom-clinics:send-reminders';

    protected $description = 'Sync clinic lifecycle statuses, retry unsent confirmations, then send 24h/1h reminders';

    public function handle(ZoomClinicMailService $mail, ZoomClinicService $clinics): int
    {
        $statusUpdates = $clinics->syncLifecycleStatuses();
        $results = $mail->processDueReminders();

        $this->info(
            "Zoom clinic mail — status sync: {$statusUpdates}, confirmations: {$results['confirmations']}, "
            ."24h: {$results['reminder_24h']}, 1h: {$results['reminder_1h']}"
        );

        return self::SUCCESS;
    }
}
