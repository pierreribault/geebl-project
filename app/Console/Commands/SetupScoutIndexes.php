<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Scout;

class SetupScoutIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup indexes with filterable fields.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->setupEvents();

        return 0;
    }

    private function setupEvents()
    {
        $this->info('Setting up events...');

        Event::search(null)
            ->client()
            ->index(app(Event::class)->searchableUsing())
            ->updateFilterableAttributes([
                'city_id',
            ]);
    }
}
