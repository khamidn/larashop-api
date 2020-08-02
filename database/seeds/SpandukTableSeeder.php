<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpandukTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$spanduks = [];
    	$faker = Faker\Factory::create();
    	$spanduk_category = ['iklan', 'pengumuman', 'informasi', 'promo', 'tutorial', 'ucapan selamat', 'dll'];

    	for($i=0; $i<10; $i++){
    		$name = $faker->sentence(mt_rand(3,6));
    		$category = $spanduk_category[mt_rand(0,6)];

    		$spanduks[$i] = [
    			'name' => $name,
    			'creator' => $faker->name,
    			'category' => $category,
    			'status' => 'PUBLISH',
    			'created_at' => Carbon\Carbon::now(),
    		];

    	}

    	DB::table('spanduks')->insert($spanduks);
        
    }
}
