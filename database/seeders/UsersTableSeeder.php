<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Traits\TraitSeeder;
use App\Models\User as Model;

class UsersTableSeeder extends Seeder
{
    use TraitSeeder;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->smartySeeder(new Model, [
            [
                'id' => 1, 
                'first_name' => 'Mateus', 
                'last_name' => 'Guizelini', 
                'email' => 'mateus.guizelini@hotmail.com', 
                'password' => Hash::make('123456'),
                'administrator' => true,
                'cpf' => '34997299898',
                'position' => 'Gestor',
                'date_of_birth' => '1986-10-22',
                'cep' => '11030151',
                'address' => 'Rua Republica do Equador',
                'number' => '127',
                'complement' => 'Torre 1 apto 231',
                'district' => 'Ponta da Praia',
                'city' => 'Santos',
                'state' => 'SP',
                'manager_id' => null,
            ],
        ]);
    }
}
