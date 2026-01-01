<?php

namespace App\Jobs;

use App\Services\FonnteService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessWhatsappMessage implements ShouldQueue
{
    use Queueable;

    public $target;
    public $message;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    public function __construct(string $target, string $message)
    {
        $this->target = $target;
        $this->message = $message;
    }

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [10, 30, 60, 120, 300];
    }

    /**
     * Execute the job.
     */
    public function handle(FonnteService $fonnteService): void
    {
        try {
            $response = $fonnteService->send($this->target, $this->message);

            if (isset($response['status']) && $response['status'] === false) {
                 Log::warning("ProcessWhatsappMessage: Fonnte API returned false status", [
                     'target' => $this->target,
                     'response' => $response
                 ]);
            }
        } catch (\Exception $e) {
            Log::error("ProcessWhatsappMessage: Job Failed", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
