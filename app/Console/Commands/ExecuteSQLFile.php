<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExecuteSQLFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:execute-sql {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute an SQL file into the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
			$filePath = $this->argument('file');
			$logFile = storage_path('logs/sql_execution.log');

			// Check if the file exists
			if (!File::exists($filePath)) {
					$this->error("File not found: $filePath");
					return 1; // Exit with error
			}

			// Read SQL file content
			$sql = File::get($filePath);

			try {
					// Execute SQL
					DB::unprepared($sql);
					$this->info("SQL file executed successfully: $filePath");
			} catch (\Exception $e) {
					$this->error("Error executing SQL file check log file: ". $logFile );
					File::append($logFile, "[" . now() . "] ERROR: " . $e->getMessage() . "\n");

					return 1; // Exit with error
			}

			return 0; // Exit successfully
    }
}
