<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\sheetApiController;

use App\Http\Controllers\completeOrderController;


class UpdateSheetPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:update';

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
        $crawl = new completeOrderController();

        $crawl->get_data_to_pm();

    }
}
