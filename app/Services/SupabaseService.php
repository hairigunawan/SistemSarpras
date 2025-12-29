<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected $url;
    protected $key;
    protected $bucket;

    public function __construct()
    {
        $this->url = config('services.supabase.url');
        $this->key = config('services.supabase.key');
        $this->bucket = config('services.supabase.bucket');
    }

    public function upload($file, $path)
    {
        $endpoint = "{$this->url}/storage/v1/object/{$this->bucket}/{$path}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->key,
            'Content-Type' => $file->getMimeType(),
        ])->withBody(
            file_get_contents($file->getRealPath()),
            $file->getMimeType()
        )->post($endpoint);

        if ($response->successful()) {
            return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$path}";
        }

        Log::error('Supabase Upload Failed: ' . $response->body());
        throw new \Exception('Gagal upload ke Supabase: ' . $response->body());
    }

    public function delete($path)
    {
        // Extract relative path if full URL is given
        // Example URL: https://xyz.supabase.co/storage/v1/object/public/images/folder/file.jpg
        // Target path for API: folder/file.jpg (in DELETE body)
        
        // Remove the public URL prefix to get the relative path inside the bucket
        $prefix = "{$this->url}/storage/v1/object/public/{$this->bucket}/";
        $relativePath = str_replace($prefix, '', $path);

        $endpoint = "{$this->url}/storage/v1/object/{$this->bucket}"; // DELETE takes array of keys

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->key,
            'Content-Type' => 'application/json',
        ])->delete($endpoint, [
            'prefixes' => [$relativePath]
        ]);

        if (!$response->successful()) {
            Log::error('Supabase Delete Failed: ' . $response->body());
        }
        
        return $response->successful();
    }
}
