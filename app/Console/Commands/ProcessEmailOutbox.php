<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class ProcessEmailOutbox extends Command
{
    protected $signature = 'email:process-outbox {--batch=50 : Number of emails to process per run}';

    protected $description = 'Process pending emails in the outbox queue';

    public function handle(EmailService $emailService): int
    {
        $batchSize = (int) $this->option('batch');

        $results = $emailService->processOutbox($batchSize);

        $this->info("Outbox processed: {$results['sent']} sent, {$results['failed']} failed.");

        return self::SUCCESS;
    }
}
