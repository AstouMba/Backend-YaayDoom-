<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('passport:ensure', function (): void {
    $publicKey = Passport::keyPath('oauth-public.key');
    $privateKey = Passport::keyPath('oauth-private.key');

    if (! file_exists($publicKey) || ! file_exists($privateKey)) {
        $this->call('passport:keys', [
            '--force' => true,
        ]);
    }

    $provider = config('auth.guards.api.provider') ?: 'users';
    $clients = app(ClientRepository::class);

    try {
        $clients->personalAccessClient($provider);
    } catch (\RuntimeException) {
        $clients->createPersonalAccessGrantClient(config('app.name'), $provider);
    }

    $this->info('Passport keys and personal access client are ready.');
})->purpose('Ensure Passport keys and the personal access client exist');
