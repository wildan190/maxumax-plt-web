<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixJerseyTypeColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:jersey-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix jersey_type column to allow null values';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('Checking jersey_type column in preorders table...');
            
            // Check if column exists
            if (!Schema::hasColumn('preorders', 'jersey_type')) {
                $this->error('jersey_type column does not exist in preorders table');
                return Command::FAILURE;
            }

            // Try to modify the column to be nullable
            DB::statement('ALTER TABLE preorders MODIFY jersey_type VARCHAR(255) NULL');
            
            $this->info('Successfully made jersey_type column nullable in preorders table');
            
            // Set any existing null values to 'Standard'
            $updated = DB::table('preorders')
                ->whereNull('jersey_type')
                ->update(['jersey_type' => 'Standard']);
            
            if ($updated > 0) {
                $this->info("Updated {$updated} records with null jersey_type to 'Standard'");
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to fix jersey_type column: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}