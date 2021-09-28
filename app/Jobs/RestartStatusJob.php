<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Imtigger\LaravelJobStatus\JobStatus;

class RestartStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $jobId;

    /**
     * Create a new job instance.
     *
     * @param $job
     */
    public function __construct($job)
    {
        $this->jobId = $job;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        update_status_job($this->jobId, JobStatus::STATUS_EXECUTING, 'excel-contractor-portal');
    }
}
