<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();
        \DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Casino Admin',
                'lastname' => 'Admin',
                'username' => 'casinoadmin',
                'email' => 'team@technocratshorizons.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => '$2y$10$ISYmbg9JyhkqZeoZx0KFXO1wg9EBfI0yniaHNt6KeSwKwh9YS8Pq2', //admin@123
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);

        //add admin roles in model_has_roles table
        \DB::table('model_has_roles')->delete();
        \DB::table('model_has_roles')->insert([
            'role_id'=>1,
            'model_type'=>'App\Models\User',
            'model_id'=>1
        ]);
    }
}
