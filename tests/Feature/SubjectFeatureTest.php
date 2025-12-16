<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SubjectFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_vicerrector_can_view_subjects_page()
    {
        // Crear roles si no existen
        $vicerrectorRole = Role::firstOrCreate(['name' => 'vicerrector']);

        // Crear un usuario y asignarle el rol de vicerrector
        $vicerrector = User::factory()->create();
        $vicerrector->assignRole($vicerrectorRole);

        // Crear algunos subjects para la prueba
        $subject1 = Subject::create(['name' => 'Matemáticas']);
        $subject2 = Subject::create(['name' => 'Historia']);

        // Autenticar como el vicerrector
        $this->actingAs($vicerrector);

        // Acceder a la página de índice de subjects
        $response = $this->get(route('subjects.index'));

        // Verificar que la respuesta es exitosa
        $response->assertStatus(200);

        // Verificar que la vista correcta fue retornada
        $response->assertViewIs('subjects.index');

        // Verificar que la vista tiene la variable 'subjects' y contiene los datos correctos
        $response->assertViewHas('subjects', function ($subjects) use ($subject1, $subject2) {
            return $subjects->contains($subject1) && $subjects->contains($subject2);
        });

        // Verificar que el nombre de los subjects se muestra en la página
        $response->assertSee($subject1->name);
        $response->assertSee($subject2->name);
    }
}
