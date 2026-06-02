<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_public_catalog(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('katalog.index'));
    }
}
