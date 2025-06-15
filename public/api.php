<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/MeowFactsClient.php';

use MeowFacts\MeowFactsClient;

header('Content-Type: application/json');

try {
    // 'count' specifies how many facts to fetch, defaults to 1, cast to int
    $count = isset($_GET['count']) ? (int)$_GET['count'] : 1;
    // 'lang' specifies the language of facts, nullable if not provided
    $lang = $_GET['lang'] ?? null;
    // 'id' specifies a specific fact ID, nullable if not provided
    $id   = $_GET['id'] ?? null;

    // Instantiate the MeowFacts API client
    $client = new MeowFactsClient();

    $facts = $client->getFacts($count, $lang, $id);

    echo json_encode([
        'success' => true,
        'facts' => $facts,
    ]);
} catch (Throwable $e) {
    // On any error, send HTTP 400 Bad Request status code
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ]);
}
