<?php

if (!function_exists('s3_config')) {
    function s3_config(string $env_bucket, string $default)
    {
        return [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env($env_bucket, $default),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'visibility' => 'public',
        ];
    }
}

if (!function_exists('minio_config')) {
    function minio_config(string $env_bucket, string $default)
    {
        return in_array(env('APP_ENV'), ['preproduction', 'production'])
            ? s3_config($env_bucket, $default) : [
                'driver' => 's3',
                'endpoint' => env('MINIO_ENDPOINT', 'http://127.0.0.1:9000'),
                'use_path_style_endpoint' => true,
                'key' => env('MINIO_KEY'),
                'secret' => env('MINIO_SECRET'),
                'region' => env('MINIO_REGION'),
                'bucket' => env($env_bucket, $default),
            ];
    }
}
