<?php

use App\Services\FonnteService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Config::set('services.fonnte.token', 'test-token');
    Config::set('services.fonnte.api_url', 'https://api.fonnte.com/send');
    Config::set('services.fonnte.retries', 3);
});

test('send method makes correct http request', function () {
    Http::fake([
        'api.fonnte.com/send' => Http::response(['status' => true, 'detail' => 'sent'], 200),
    ]);

    $service = new FonnteService();
    $response = $service->send('08123456789', 'Hello World');

    Http::assertSent(function ($request) {
        return $request->url() == 'https://api.fonnte.com/send' &&
               $request->hasHeader('Authorization', 'test-token') &&
               $request['target'] == '628123456789' &&
               $request['message'] == 'Hello World';
    });

    expect($response['status'])->toBeTrue();
});

test('send method returns response for client error 400', function () {
    Http::fake([
        'api.fonnte.com/send' => Http::response(['status' => false, 'reason' => 'invalid'], 400),
    ]);
    
    $service = new FonnteService();
    $response = $service->send('08123456789', 'Hello');
    
    // Should NOT throw, but return response
    expect($response['status'])->toBeFalse();
});

test('send method throws exception for server error 500', function () {
    Http::fake([
        'api.fonnte.com/send' => Http::response(['status' => false], 500),
    ]);
    
    $service = new FonnteService();
    
    expect(fn() => $service->send('08123456789', 'Hello'))
        ->toThrow(\Illuminate\Http\Client\RequestException::class);
});
