<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\DriversSalaryMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DriversSalaryNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:drivers-salary-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $drivers = User::role('driver')->get();

        foreach ($drivers as $driver) {
            info('Sending salary notification email to driver: ' . $driver->firstname . ' ' . $driver->lastname);
            Mail::to($driver->email)->send(new DriversSalaryMail($driver));
        }
    }
}
