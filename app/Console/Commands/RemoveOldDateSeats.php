<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RemoveOldDateSeats extends Command
{
    protected $signature = 'seats:remove-old';
    protected $description = 'Remove seat records for today';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::today()->format('Y-m-d');
        DB::table('date_seats')->where('date', $today)->delete();
        $this->info('Old seat records removed');
    }
}
