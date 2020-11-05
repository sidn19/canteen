<?php

class Router {
    private array $resources = [
        'GET' => [],
        'POST' => []
    ];

    public function get(string $url, callable $handler): void {
        $this->resources['GET'][] = ['url' => $url, 'handler' => $handler]; 
    }

    public function post(string $url, callable $handler): void {
        $this->resources['POST'][] = ['url' => $url, 'handler' => $handler];
    }

    private static function decodeResource(string $rawUrl): string {
        return str_replace('api/', '', str_replace('index.php', '', parse_url($rawUrl, PHP_URL_PATH)));
    }

    public function run(string $type, string $url): void {
        $resource = self::decodeResource($url);

        $resourceIndex = array_search($resource, array_column($this->resources[$type], 'url'));

        if ($resourceIndex !== false) {
            $response = $this->resources[$type][$resourceIndex]['handler']($type === 'GET' ? $_GET : $_POST);
            
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        else {
            http_response_code(400);
            die('Invalid Resource!');
        }
    }
}