<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ComputerCrudTest extends TestCase
{
    /** Computadores de ejemplo tal como los devuelve la API de adminSena. */
    private function apiComputers(): array
    {
        return [
            ['id' => 1, 'number' => '21', 'brand' => 'Asus'],
            ['id' => 2, 'number' => '7', 'brand' => 'PC'],
        ];
    }

    /** El listado se dibuja con los datos que devuelve la API. */
    public function test_the_index_lists_the_computers()
    {
        Http::fake([
            'api.test/v1/computers' => Http::response($this->apiComputers(), 200),
        ]);

        $response = $this->get(route('computers.index'));

        $response->assertOk();
        $response->assertViewIs('computers.index');
        $response->assertSee('21');
        $response->assertSee('Asus');
    }

    /** El formulario de creacion se dibuja y trae los campos esperados. */
    public function test_the_create_page_renders_the_form()
    {
        $response = $this->get(route('computer.create'));

        $response->assertOk();
        $response->assertViewIs('computers.create');
        $response->assertSee('name="number"', false);
        $response->assertSee('name="brand"', false);
    }

    /** Un POST valido reenvia los datos a la API y redirige al listado. */
    public function test_it_stores_a_computer()
    {
        Http::fake([
            'api.test/v1/computers' => Http::response(['id' => 3, 'number' => 'SENA-001', 'brand' => 'Lenovo'], 201),
        ]);

        $response = $this->post(route('computer.store'), [
            'number' => 'SENA-001',
            'brand' => 'Lenovo',
        ]);

        $response->assertRedirect(route('computers.index'));
        $response->assertSessionHas('success');

        Http::assertSent(function (Request $request) {
            return $request->url() === 'http://api.test/v1/computers'
                && $request->method() === 'POST'
                && $request['number'] === 'SENA-001'
                && $request['brand'] === 'Lenovo';
        });
    }

    /** Un POST invalido no se envia a la API y devuelve los errores. */
    public function test_it_rejects_invalid_data_without_calling_the_api()
    {
        Http::fake();

        $response = $this->from(route('computer.create'))
            ->post(route('computer.store'), [
                'number' => '',
                'brand' => '',
            ]);

        $response->assertRedirect(route('computer.create'));
        $response->assertSessionHasErrors(['number', 'brand']);
        Http::assertNothingSent();
    }

    /** Los errores de validacion de la API (422) se muestran en el formulario. */
    public function test_it_surfaces_the_api_validation_errors()
    {
        Http::fake([
            'api.test/v1/computers' => Http::response([
                'message' => 'The given data was invalid.',
                'errors' => ['number' => ['El numero ya existe en la API.']],
            ], 422),
        ]);

        $response = $this->from(route('computer.create'))
            ->post(route('computer.store'), [
                'number' => 'SENA-001',
                'brand' => 'Lenovo',
            ]);

        $response->assertRedirect(route('computer.create'));
        $response->assertSessionHasErrors('number');
    }

    /** Si la API esta caida se avisa con un mensaje, no con un error 500. */
    public function test_it_handles_the_api_being_down()
    {
        Http::fake([
            'api.test/v1/*' => Http::response('Servidor no disponible', 500),
        ]);

        $response = $this->get(route('computers.index'));

        $response->assertOk();
        $response->assertSee('No hay computadores registrados', false);
    }

    /** La vista de edicion muestra los valores que devuelve la API. */
    public function test_the_edit_page_shows_the_current_values()
    {
        Http::fake([
            'api.test/v1/computers/1' => Http::response($this->apiComputers()[0], 200),
        ]);

        $response = $this->get(route('computer.edit', 1));

        $response->assertOk();
        $response->assertViewIs('computers.edit');
        $response->assertSee('21');
        $response->assertSee('Asus');
    }

    /** Un PUT valido actualiza el recurso en la API. */
    public function test_it_updates_a_computer()
    {
        Http::fake([
            'api.test/v1/computers/1' => Http::response(['id' => 1, 'number' => 'SENA-002', 'brand' => 'HP'], 200),
        ]);

        $response = $this->put(route('computer.update', 1), [
            'number' => 'SENA-002',
            'brand' => 'HP',
        ]);

        $response->assertRedirect(route('computers.index'));
        $response->assertSessionHas('success');

        Http::assertSent(function (Request $request) {
            return $request->url() === 'http://api.test/v1/computers/1'
                && $request->method() === 'PUT'
                && $request['number'] === 'SENA-002'
                && $request['brand'] === 'HP';
        });
    }

    /** Un PUT invalido no se envia a la API. */
    public function test_it_rejects_invalid_updates_without_calling_the_api()
    {
        Http::fake();

        $response = $this->from(route('computer.edit', 1))
            ->put(route('computer.update', 1), [
                'number' => '',
                'brand' => '',
            ]);

        $response->assertRedirect(route('computer.edit', 1));
        $response->assertSessionHasErrors(['number', 'brand']);
        Http::assertNothingSent();
    }

    /** El DELETE se reenvia a la API. */
    public function test_it_deletes_a_computer()
    {
        Http::fake([
            'api.test/v1/computers/1' => Http::response(['message' => 'Computador eliminado correctamente'], 200),
        ]);

        $response = $this->delete(route('computer.destroy', 1));

        $response->assertRedirect(route('computers.index'));
        $response->assertSessionHas('success');

        Http::assertSent(fn (Request $request) => $request->method() === 'DELETE'
            && $request->url() === 'http://api.test/v1/computers/1');
    }

    /** Si la API no puede borrar (FK), el mensaje de la API se muestra al usuario. */
    public function test_it_shows_the_api_message_when_the_delete_fails()
    {
        Http::fake([
            'api.test/v1/computers/1' => Http::response([
                'message' => 'El computador tiene aprendices asignados y no se puede eliminar.',
            ], 409),
        ]);

        $response = $this->delete(route('computer.destroy', 1));

        $response->assertRedirect(route('computers.index'));
        $response->assertSessionHas('error');
    }
}
