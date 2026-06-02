<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_can_be_accessed_without_login(): void
    {
        $book = Book::create([
            'code' => 'BK101',
            'title' => 'Jaringan Komputer',
            'author' => 'Budi',
            'publisher' => 'IT Press',
            'year' => 2022,
            'category' => 'Teknologi',
            'stock' => 2,
            'description' => 'Dasar jaringan komputer.',
        ]);

        $response = $this->get(route('katalog.index'));

        $response->assertStatus(200);
        $response->assertSee('Katalog Koleksi Perpustakaan');
        $response->assertSee($book->title);
    }

    public function test_public_catalog_detail_can_be_accessed_without_login(): void
    {
        $book = Book::create([
            'code' => 'BK100',
            'title' => 'Arsitektur Aplikasi',
            'author' => 'Ari',
            'publisher' => 'Tech Press',
            'year' => 2024,
            'category' => 'Teknologi',
            'stock' => 1,
            'description' => 'Buku arsitektur aplikasi.',
        ]);

        $response = $this->get(route('katalog.show', $book));

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }
}
