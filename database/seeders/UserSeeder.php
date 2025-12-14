<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear roles si no existen
        $docenteRole = Role::firstOrCreate(['name' => 'docente']);
        $secretariaRole = Role::firstOrCreate(['name' => 'secretaria']);
        $vicerrectorRole = Role::firstOrCreate(['name' => 'vicerrector']);

        // Crear usuario docente
        $docente = User::create([
            'name' => 'Usuario Docente',
            'email' => 'docente@example.com',
            'password' => bcrypt('password'),
        ]);
        $docente->assignRole($docenteRole);

        // Crear usuario secretaria
        $secretaria = User::create([
            'name' => 'Usuario Secretaria',
            'email' => 'secretaria@example.com',
            'password' => bcrypt('password'),
        ]);
        $secretaria->assignRole($secretariaRole);

        // Crear usuario vicerrector
        $vicerrector = User::create([
            'name' => 'Usuario Vicerrector',
            'email' => 'vicerrector@example.com',
            'password' => bcrypt('password'),
        ]);
        $vicerrector->assignRole($vicerrectorRole);
    }
}
