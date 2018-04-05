<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Volunteer;
use Carbon\Carbon;

class VolunteersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate([
            'name' => 'Hans Muster',
            'email' => 'hans.muster@gmail.com',
        ]);
        Volunteer::create([
            'first_name' => 'Hans',
            'last_name' => 'Muster',
            'street' => 'Musterstrasse 1',
            'zip' => '1234',
            'city' => 'Musterort',
            'country' => 'Germany',
            'nationality' => 'German',
            'date_of_birth' => '1995-01-02',
            'gender' => 'male',
            'phone' => '+491234567890',
            'whatsapp' => '+491234567890',
            'skype' => 'hans.muster',
            'professions' => 'Teacher',
            'other_skills' => 'Construction',
            'language_skills' => 'German, English',
            'previous_experience' => 'Teaching German courses in refugee reception centers.',
            'passport_no' => 'C1234567',
            'user_id' => $user->id,
        ])->trips()->create([
            'arrival' => Carbon::now()->subDays(7),
            'departure' => Carbon::now()->addDays(5),
        ]);
    }
}
