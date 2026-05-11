<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncSubmissionStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-submission-statuses';

    protected $description = 'Sincronize assessment statuses based on submission timelines';

    public function handle()
    {
        $this->info('Starting status synchronization...');

        $timelines = \App\Models\SubmissionTimeline::all();
        $now = \Illuminate\Support\Carbon::now();

        foreach ($timelines as $timeline) {
            $this->comment("Processing timeline for year: {$timeline->tahun_periode}");

            // 1. Auto-Lock (Transition to SUBMITTED)
            if (($timeline->closes_at && $now->gt($timeline->closes_at)) || $timeline->is_locked) {
                $affected = \App\Models\Assessment::where('tahun_periode', $timeline->tahun_periode)
                    ->whereIn('status', ['ACTIVE', 'IN_PROGRESS'])
                    ->update(['status' => 'SUBMITTED']);
                
                if ($affected > 0) {
                    $this->info("Locked {$affected} submissions to SUBMITTED for year {$timeline->tahun_periode}.");
                }
            }

            // 2. Auto-Publish (Transition to PUBLISHED)
            if ($timeline->results_published_at && $now->gt($timeline->results_published_at)) {
                $affected = \App\Models\Assessment::where('tahun_periode', $timeline->tahun_periode)
                    ->where('status', 'GRADED')
                    ->update(['status' => 'PUBLISHED']);

                if ($affected > 0) {
                    $this->info("Published {$affected} submissions to PUBLISHED for year {$timeline->tahun_periode}.");
                }
            }
        }

        $this->info('Synchronization completed.');
    }
}
