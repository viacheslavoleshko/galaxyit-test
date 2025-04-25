<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Mail\DriverPensionerMail;
use Illuminate\Support\Facades\Mail;

class DriversPensionersNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:drivers-pensioners-notification';

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
        $driversPensioners = User::role('driver')->whereDate('birth_date', '<=', now()->subYears(65))->get();
        info('Found ' . $driversPensioners->count() . ' drivers pensioners');
        $admin = User::role('admin')->first();
        
        foreach ($driversPensioners as $driver) {
            info('Sending admin ' . $admin->firstname  . ' ' . $admin->lastname . ' notification email about driver pensioner: ' . $driver->firstname . ' ' . $driver->lastname);
            Mail::to($admin->email)->send(new DriverPensionerMail($driver));
            $driver->delete();
        }
    }
}
