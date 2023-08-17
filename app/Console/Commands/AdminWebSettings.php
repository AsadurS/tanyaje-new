<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AdminWebSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Admin:Settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update WebSettings for admin';

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
        DB::table('manage_role')
            ->whereIn('user_types_id',[\App\Models\Core\User::ROLE_SUPER_ADMIN,\App\Models\Core\User::ROLE_NORMAL_ADMIN])
            ->update(['website_setting_view' => 1,'website_setting_update' => 1]);
    }
}
