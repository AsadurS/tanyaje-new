<?php

namespace App\Console\Commands;

use App\Models\Core\MerchantBranch;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class GenerateMerchantSlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:merchant_slug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $merchnats = MerchantBranch::where('merchant_name','!=',"")->get();
        foreach($merchnats as $merchnat)
        {
            $merchnat->slug = Str::slug($merchnat->merchant_name,'-');
            $merchnat->save();
        }
    }
}
