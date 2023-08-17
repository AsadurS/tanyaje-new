<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Core\Cars;
use Illuminate\Support\Facades\Log;

class GenerateCodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate cars random code on hourly basis';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::channel('schedulejob')->info('Start Generate Code');

        $cars = Cars::all();
        foreach($cars as $car)
        {
            $car->update(['random_code' => rand(0,100000)]);
        }

        Log::channel('schedulejob')->info('End Generate Code');
    }
}
