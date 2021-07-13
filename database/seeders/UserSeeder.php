<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // FirstOrCreate allows you to check if the user already exists, and if it does not exist, it is created.
        $admin = User::firstOrCreate([
            'login' => 'admin@admin.com',
            'first_name' => 'Admin',
            'last_name' => 'Adminstrateur',
            'type' => 'admin'
        ]);

        if (!$admin->mdp) {
            $admin->update([
                'mdp' => Hash::make('admin')
            ]);
        }


        if (User::where('type', 'student')->count() < 3) {
            for ($i = 0; $i < 3; $i++) {
                User::create([
                    'login' => "student$i@student.com",
                    'first_name' => 'Student' . $i,
                    'last_name' => "$i - $i",
                    'type' => 'student',
                    'mdp' => Hash::make('student')
                ]);
            }
        }

        if (User::where('type', 'instructor')->count() < 3) {
            for ($i = 0; $i < 3; $i++) {
                User::create([
                    'login' => "instructor$i@instructor.com",
                    'first_name' => 'Instructor' . $i,
                    'last_name' => "$i - $i",
                    'type' => 'instructor',
                    'mdp' => Hash::make('instructor')
                ]);
            }
        }

        //User::factory(5)->create();
    }
}
