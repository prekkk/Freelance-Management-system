<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Insert data into the job_types table
        //  DB::table('job_types')->insert([
        //     [
        //         'name' => 'Job Type 1',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'name' => 'Job Type 2',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'name' => 'Job Type 3',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
            
        // ]);

        // Insert data into the user table
       // \App\Models\User::factory(10)->create();
    
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

     // Insert data into the job_types table
      // \App\Models\CategoryJobType::factory(5)->create();

      // Insert data into the category table
        //\App\Models\Category::factory(8)->create();

        // Insert data into the jobs table
        //\App\Models\Job::factory(10)->create();

    }
}
