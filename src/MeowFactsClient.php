<?php

declare(strict_types=1);

namespace MeowFacts;

/**
 * Class MeowFactsClient
 * 
 * A reusable PHP client for communicating with the Meow Facts API.
 */
class MeowFactsClient
{
    // Base URL of the external Meow Facts API
    private const BASE_URL = 'https://meowfacts.herokuapp.com/';

    // Supported language codes for API requests
    private array $allowedLangs = ['eng', 'ces-cz', 'ger'];

    /**
     * Fetches cat facts from the Meow Facts API.
     * 
     * @param int|null    $count Number of facts to retrieve (1 to 100). Defaults to 1.
     * @param string|null $lang  Optional language code for facts.
     * @param string|null $id    Optional specific fact ID to retrieve.
     * 
     * @return array Returns an array of facts.
     * 
     * @throws \InvalidArgumentException When input parameters are invalid.
     * @throws \RuntimeException         When the API request fails or returns invalid data.
     */
    public function getFacts(?int $count = 1, ?string $lang = null, ?string $id = null): array
    {
        $params = [];

        if ($count !== null) {
            // Validate 'count' parameter range to avoid API misuse or unexpected results
            if ($count < 1 || $count > 100) {
                throw new \InvalidArgumentException("Parameter 'count' must be between 1 and 100.");
            }

            $params['count'] = $count;
        }

        if ($lang !== null) {
            // Check if requested language is supported to prevent API errors
            if (!in_array($lang, $this->allowedLangs)) {
                throw new \InvalidArgumentException("Unsupported language '{$lang}'.");
            }

            $params['lang'] = $lang;
        }

        if ($id !== null) {
            // Include specific fact ID if provided, trusting API to handle validity
            $params['id'] = $id;
        }

        $url = self::BASE_URL . '?' . http_build_query($params);

        // Use file_get_contents for simplicity, suppress errors and handle failure explicitly
        $response = @file_get_contents($url);

        if ($response === false) {
            // Connection failure or network issue detected; throw exception for caller to handle
            throw new \RuntimeException("Failed to connect to Meow Facts API.");
        }

        // Decode JSON response to associative array
        $data = json_decode($response, true);

        // Validate expected structure exists and is correct
        if (!isset($data['data']) || !is_array($data['data'])) {
            throw new \RuntimeException("Invalid response from Meow Facts API.");
        }

        return $data['data'];
    }
}
