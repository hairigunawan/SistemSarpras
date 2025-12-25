<?php

use App\Services\FonnteService;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Config::set('services.fonnte.country', '62');
});

test('sanitizeNumber formats number correctly', function () {
    $service = new FonnteService();

    expect($service->sanitizeNumber('08123456789'))->toBe('628123456789');
    expect($service->sanitizeNumber('628123456789'))->toBe('628123456789');
    expect($service->sanitizeNumber('+628123456789'))->toBe('628123456789');
    expect($service->sanitizeNumber('8123456789'))->toBe('628123456789'); 
    expect($service->sanitizeNumber('00628123456789'))->toBe('628123456789');
});

test('sanitizeNumber handles other country codes if configured', function () {
    Config::set('services.fonnte.country', '1'); // US
    $service = new FonnteService(); 

    expect($service->sanitizeNumber('08123456789'))->toBe('18123456789'); 
});
