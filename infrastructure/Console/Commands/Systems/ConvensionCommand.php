<?php

namespace Infrastructure\Console\Commands\Systems;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ConvensionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convension {folder?} {--fix}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command check convension coding';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $folder = $this->argument('folder');
        if (!$folder) {
            $folder = $this->anticipate('Name root folder?', ['api', 'infrastructure']);
        }
        $fix = $this->option('fix');
        if ($fix) {
            echo shell_exec("vendor/bin/phpcbf --standard=phpcs.xml ./" . $folder);
        } else {
            echo shell_exec("vendor/bin/phpcs --standard=phpcs.xml ./" . $folder);
        }
        return show_cli("Scan $folder done!");
    }
}
