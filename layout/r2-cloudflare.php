<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Aws\S3\S3Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(
    dirname(__DIR__)
    // dirname(__DIR__, 3) . '/cloudflare' //Diluar public html
);

$dotenv->safeLoad();

$r2AccountId = $_ENV['R2_ACCOUNT_ID'] ?? '';
$r2AccessKey = $_ENV['R2_ACCESS_KEY'] ?? '';
$r2SecretKey = $_ENV['R2_SECRET_ACCESS_KEY'] ?? '';
$r2Bucket    = $_ENV['R2_BUCKET'] ?? '';

if (
    $r2AccountId === '' ||
    $r2AccessKey === '' ||
    $r2SecretKey === '' ||
    $r2Bucket === ''
) {
    throw new RuntimeException(
        'Konfigurasi Cloudflare R2 belum lengkap. Periksa file .env.'
    );
}

$r2 = new S3Client([
    'version' => 'latest',
    'region'  => 'auto',

    'endpoint' =>
        'https://' .
        $r2AccountId .
        '.r2.cloudflarestorage.com',

    'credentials' => [
        'key'    => $r2AccessKey,
        'secret' => $r2SecretKey,
    ],
]);