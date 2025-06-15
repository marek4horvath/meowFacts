# MeowFacts PHP Client

A lightweight PHP client for the [Meow Facts API](https://github.com/wh-iterabb-it/meowfacts), allowing you to fetch random cat facts with optional parameters like language, count, and ID.

## Project Structure

```
project-root/
│
├── src/
│   └── MeowFactsClient.php     # Main API client
│
├── public/
│   └── css/
│       └── index.css           # Custom CSS styles for the frontend UI. Used to override or extend Bootstrap
│                               # styles, and define application-specific visual tweaks.
│   └── js/
│       └── index.js            # Frontend JavaScript logic that handles form submission via AJAX,
│                               # updates the DOM dynamically, handles error display, and manages browser history.
│
│   └── api.php                 # HTTP endpoint / front controller
│
│   └── index.html              # Main HTML file that renders the frontend. Includes the form for user input
│                               # and markup placeholders where cat facts are dynamically displayed using JS.
```

## Usage

### 1. Basic Example (without Composer)

```php
require_once 'src/MeowFactsClient.php';

use MeowFacts\MeowFactsClient;

$client = new MeowFactsClient();

// Fetch 3 facts in English
$facts = $client->getFacts(3, 'eng');

foreach ($facts as $fact) {
    echo $fact . PHP_EOL;
}
```

---

## Class Overview

### `MeowFactsClient`

Handles communication with the Meow Facts API.

#### Method: `getFacts`

```php
getFacts(?int $count = 1, ?string $lang = null, ?string $id = null): array
```

| Parameter | Type         | Description |
|-----------|--------------|-------------|
| `$count`  | `int|null`   | Number of facts to fetch (1–100). Default is 1. |
| `$lang`   | `string|null`| Optional language code (`eng`, `ces-cz`, `ger`). |
| `$id`     | `string|null`| Optional ID of a specific fact. |

**Returns:** An array of strings – each string is a single fact.

#### Exceptions

- `InvalidArgumentException`: If an invalid parameter is passed.
- `RuntimeException`: If the API request fails or returns invalid data.

---

## HTTP Endpoint Example (`api.php`)

You can use `MeowFactsClient` in an API endpoint like this:

```php
<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/MeowFactsClient.php';

use MeowFacts\MeowFactsClient;

header('Content-Type: application/json');

try {
    $count = isset($_GET['count']) ? (int)$_GET['count'] : 1;
    $lang  = $_GET['lang'] ?? null;
    $id    = $_GET['id'] ?? null;

    $client = new MeowFactsClient();
    $facts = $client->getFacts($count, $lang, $id);

    echo json_encode([
        'success' => true,
        'facts' => $facts,
    ]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
    ]);
}
```

### Example HTTP Request

```http
GET http://localhost:8000/api.php?count=2&lang=ces-cz
```

### JSON Response

```json
{
  "success": true,
  "facts": [
    "Cats bury their feces to cover their trails from predators."
  ]
}
```

---

## Supported Languages

| Code      | Language   |
|-----------|------------|
| `eng`     | English    |
| `ces-cz`  | Czech      |
| `ger`     | German     |

---

## Error Handling

Example error response:

```json
{
  "success": false,
  "error": "Unsupported language 'fr'."
}
```

---

## Future Improvements

- Convert response to data objects (DTO)
- Add caching (e.g. Redis)
- Laravel integration
- CLI support

---
