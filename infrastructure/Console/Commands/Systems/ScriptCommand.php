<?php

namespace Infrastructure\Console\Commands\Systems;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ScriptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'script';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command update table database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $script = DB::table('scripts')->pluck('file_name')->toArray();
        $files  = glob(base_path('scripts') . '/*.*');
        if (!$files) {
            return;
        }
        foreach ($files as $file) {
            $explode    = explode('/', $file);
            $file_name  = end($explode);
            if (!in_array($file_name, $script)) {
                $sql = file_get_contents($file);
                DB::unprepared($sql);
                $date       = date('Y-m-d H:i:s');
                DB::table('scripts')->insert([
                    'id'          => uuid(),
                    'file_name'  => $file_name,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }
        }
        return show_cli('Scan folder script & Run done!');
    }
}
