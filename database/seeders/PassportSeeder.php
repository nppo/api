<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Auth\Passport\Client;
use Illuminate\Database\Seeder;

class PassportSeeder extends Seeder
{
    public function run(): void
    {
        Client::create([
            'id'       => '92aea21e-2f37-4095-b604-dd6b3fc16a5e',
            'name'     => 'surapp_frontend_local',
            'redirect' => implode(',', [
                'http://localhost:3000/login',
                'https://surapp.stage2web.nl/login',
                'https://surapp-web.accept2web.nl/login',
            ]),
            'personal_access_client' => false,
            'password_client'        => false,
            'revoked'                => false,
        ]);
    }
}
