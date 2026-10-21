<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * La raiz redirige al panel de computadores.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        Http::fake([
            '*' => Http::response([], 200),
        ]);

        $response = $this->get('/');

        $response->assertOk();
    }
}
