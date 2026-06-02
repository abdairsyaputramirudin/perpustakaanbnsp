<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'member_code' => 'AGT001',
                'name' => 'Budi Santoso',
                'phone' => '081234567890',
                'email' => 'budi@example.com',
                'address' => 'Surabaya',
            ],
            [
                'member_code' => 'AGT002',
                'name' => 'Ani Wulandari',
                'phone' => '081234567891',
                'email' => 'ani@example.com',
                'address' => 'Sidoarjo',
            ],
            [
                'member_code' => 'AGT003',
                'name' => 'Rizky Ramadhan',
                'phone' => '081234567892',
                'email' => 'rizky@example.com',
                'address' => 'Gresik',
            ],
        ];

        foreach ($members as $member) {
            Member::updateOrCreate(['member_code' => $member['member_code']], $member);
        }
    }
}

