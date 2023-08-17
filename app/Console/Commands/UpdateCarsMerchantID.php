<?php

namespace App\Console\Commands;

use App\Models\Core\Cars;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateCarsMerchantID extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sync:car_merchants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Cars Main merchant ID';

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
        Log::info("Start Cars Main Merchant Sync");

        $cars = Cars::whereNotNull('merchant_id')->get();
        $i = 0;
        foreach($cars as $car)
        {
            $merchant_branch = $car->car_merchant;
            if($merchant_branch)
            {
                $car->update(['user_id' => $merchant_branch->user_id]);
                $i++;
            }
        }
        Log::info("Cars Updated: ".$i);
        Log::info("End Cars Main Merchant Sync");
    }
}
