<?php

namespace App\Console\Commands;

use App\SaleAdvisor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SaleAdvisorInactive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sale_advisor_status:change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'change active sales advisor where  less than ';

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
        SaleAdvisor::where('verified',1)->whereDate('verified_until','<', Carbon::now())
            ->chunk(100, function ($saleAdvisors) {
                foreach ($saleAdvisors as $saleAdvisor) {
                    $saleAdvisor->update([
                         'verified'=>0
                    ]);
                }
            });
    }
}
