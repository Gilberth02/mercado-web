<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol; // <-- Importante

class SembradorRol extends Seeder // <-- Tu nombre de clase
{
    
    public function run(): void
    {
        // Creamos los roles basicos
        Rol::create(['nombre' => 'cliente']);
        Rol::create(['nombre' => 'vendedor']);
        Rol::create(['nombre' => 'repartidor']);
        Rol::create(['nombre' => 'admin']);
    }
}
