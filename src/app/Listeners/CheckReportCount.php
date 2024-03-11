<?php

namespace App\Listeners;

use App\Events\ReportSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckReportCount
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReportSubmitted $event)
    {
        $reportable = $event->report->reportable;

        // Count unique user reports
        $reportsCount = $reportable->reports()->distinct('user_id')->count('user_id');

        if ($reportsCount > 3) {
            $reportable->delete(); // Soft delete the reportable entity
        }
    }
}
