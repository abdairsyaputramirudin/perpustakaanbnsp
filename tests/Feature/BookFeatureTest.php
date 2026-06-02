<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_open_book_detail_page(): void
    {
        $staff = User::create([
            'name' => 'Petugas',
            'email' => 'petugas2@example.com',
            'password' => 'password',
        ]);

        $book = Book::create([
            'code' => 'BK777',
            'title' => 'Clean Architecture',
            'author' => 'Robert C. Martin',
            'publisher' => 'Pearson',
            'year' => 2018,
            'category' => 'Software',
            'stock' => 1,
            'description' => 'Arsitektur perangkat lunak.',
        ]);

        $response = $this->actingAs($staff)->get(route('books.show', $book));

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_staff_can_upload_book_cover_when_creating_book(): void
    {
        Storage::fake('public');

        $staff = User::create([
            'name' => 'Petugas',
            'email' => 'petugas3@example.com',
            'password' => 'password',
        ]);

        $cover = UploadedFile::fake()->image('cover.jpg');

        $response = $this->actingAs($staff)->post(route('books.store'), [
            'code' => 'BK909',
            'title' => 'Pemrograman Lanjut',
            'author' => 'Ahmad',
            'publisher' => 'Tech',
            'year' => 2025,
            'category' => 'Teknologi',
            'stock' => 2,
            'description' => 'Materi lanjutan.',
            'cover_image' => $cover,
        ]);

        $response->assertRedirect(route('books.index'));

        $book = Book::where('code', 'BK909')->firstOrFail();
        $this->assertNotNull($book->cover_image);
        Storage::disk('public')->assertExists($book->cover_image);
    }

    public function test_staff_can_upload_png_and_webp_cover_when_creating_book(): void
    {
        Storage::fake('public');

        $staff = User::create([
            'name' => 'Petugas',
            'email' => 'petugas4@example.com',
            'password' => 'password',
        ]);

        $pngCover = UploadedFile::fake()->image('cover.png');
        $webpCover = UploadedFile::fake()->image('cover.webp');

        $this->actingAs($staff)->post(route('books.store'), [
            'code' => 'BK910',
            'title' => 'Pemrograman Web',
            'author' => 'Nina',
            'publisher' => 'Tech',
            'year' => 2025,
            'category' => 'Teknologi',
            'stock' => 1,
            'description' => 'Materi web.',
            'cover_image' => $pngCover,
        ])->assertRedirect(route('books.index'));

        $this->actingAs($staff)->post(route('books.store'), [
            'code' => 'BK911',
            'title' => 'Pemrograman Mobile',
            'author' => 'Rafi',
            'publisher' => 'Tech',
            'year' => 2025,
            'category' => 'Teknologi',
            'stock' => 1,
            'description' => 'Materi mobile.',
            'cover_image' => $webpCover,
        ])->assertRedirect(route('books.index'));

        $bookPng = Book::where('code', 'BK910')->firstOrFail();
        $bookWebp = Book::where('code', 'BK911')->firstOrFail();

        $this->assertNotNull($bookPng->cover_image);
        $this->assertNotNull($bookWebp->cover_image);
        Storage::disk('public')->assertExists($bookPng->cover_image);
        Storage::disk('public')->assertExists($bookWebp->cover_image);
    }
}
