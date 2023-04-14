<?php

namespace App\Console\Commands\Supporters;

use Illuminate\Console\Command;

class DeleteFromSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supporters:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Supporters from a site specified by ID';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        if (!is_numeric($id)) {
            $this->error('ID must be numeric');
            return Command::FAILURE;
        }
        $site = \App\Models\Site::find($id);
        if (!$site) {
            $this->error('Site not found');
            return Command::FAILURE;
        }
        if ($this->confirm('Are you sure you want to delete all supporters from site ' . $site->name . '?')) {
            $this->info('Deleting supporters from site ' . $id);
        } else {
            $this->info('Aborting');
            return Command::FAILURE;
        }
        $supporters = \App\Models\Supporter::where('site_id', $id)->get();
        foreach ($supporters as $supporter) {
            $supporter->delete();
        }
        return Command::SUCCESS;
    }
}
