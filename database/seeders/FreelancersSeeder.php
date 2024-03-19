<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\db;

class FreelancersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Dummy data
        $freelancers = [
            [
                'name' => 'Neha Budha',
                'designation' => 'Math Teacher',
                'email' => 'neha@gmail.com',
                'location' => 'Pokhara',
            ],
            [
                'name' => 'Sirish Rana',
                'designation' => 'Fitness trainer',
                'email' => 'sirish@gmail.com',
                'location' => 'Basundhara',
            ],
            [
                'name' => 'Kushal Magar',
                'designation' => 'Carpenter',
                'email' => 'kushal@gmail.com',
                'location' => 'Bhaisepati',
            ],
            [
                'name' => 'Binaya Shrestha',
                'designation' => 'Gardener',
                'email' => 'binaya@gmail.com',
                'location' => 'Basundhara',
            ],
            [
                'name' => 'Riya Limbu',
                'designation' => 'Makeup artist',
                'email' => 'riya@gmail.com',
                'location' => 'Samakhusi',
            ],
            [
                'name' => 'Lumila Manandhar',
                'designation' => 'caregiver',
                'email' => 'lumila@gmail.com',
                'location' => 'Kalanki',
            ],
        ];

        // Insert records into the freelancers table
        foreach ($freelancers as $freelancer) {
            db::table('freelancers')->insert($freelancer);
        }
    }
}
