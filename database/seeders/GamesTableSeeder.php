<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('games')->delete();
        \DB::table('games')->insert([
            0 => [
                'id' => 1,
                'name' => 'Fire Kiren',
                'image' => 'video-game.png',
                'download_path' => 'http://firekirin.xyz:8580/index.html',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),     
            ],
            1 => [
                'id' => 2,
                'name' => 'Tiger',
                'image' => 'tiger-video.png',
                'download_path' => 'https://tigerishome.com/tiger/#/login',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),  
            ]
        ]);
    }
}