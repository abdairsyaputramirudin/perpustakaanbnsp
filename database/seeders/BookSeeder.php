<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'code' => 'BK001',
                'title' => 'Dasar Pemrograman',
                'author' => 'Andi Pratama',
                'publisher' => 'Informatika',
                'year' => 2022,
                'category' => 'Teknologi',
                'stock' => 3,
                'description' => 'Materi dasar logika dan pemrograman.',
            ],
            [
                'code' => 'BK002',
                'title' => 'Laravel untuk Pemula',
                'author' => 'Rina Kurnia',
                'publisher' => 'Tekno Media',
                'year' => 2023,
                'category' => 'Framework',
                'stock' => 4,
                'description' => 'Panduan praktik membangun aplikasi Laravel.',
            ],
            [
                'code' => 'BK003',
                'title' => 'Basis Data MySQL',
                'author' => 'Dwi Saputra',
                'publisher' => 'Data Press',
                'year' => 2021,
                'category' => 'Database',
                'stock' => 2,
                'description' => 'Pembahasan desain tabel dan query SQL.',
            ],
            [
                'code' => 'BK004',
                'title' => 'Pengujian Perangkat Lunak',
                'author' => 'Sinta Maharani',
                'publisher' => 'QA House',
                'year' => 2020,
                'category' => 'Testing',
                'stock' => 1,
                'description' => 'Konsep testing manual dan otomatis.',
            ],
            [
                'code' => 'BK005',
                'title' => 'Clean Code Praktis',
                'author' => 'Bambang Irawan',
                'publisher' => 'Dev Books',
                'year' => 2019,
                'category' => 'Best Practice',
                'stock' => 0,
                'description' => 'Contoh penulisan kode yang mudah dirawat.',
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(['code' => $book['code']], $book);
        }
    }
}

