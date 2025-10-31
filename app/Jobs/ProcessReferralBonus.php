<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ReferralService;

class ProcessReferralBonus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $amount;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     * @param float $amount
     * @param string $type
     * @return void
     */
    public function __construct($userId, $amount, $type = 'activation')
    {
        $this->userId = $userId;
        $this->amount = $amount;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ReferralService::processReferralBonus($this->userId, $this->amount, $this->type);
    }
}