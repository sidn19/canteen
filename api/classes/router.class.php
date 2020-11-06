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

    private static function verifyAndDecodeToken(string $token): array {
        require_once __DIR__.'/../vendor/autoload.php';
        $client = new Google_Client(['client_id' => '570178535400-0ljjrn2urq7el0maauibd1qjq0482n76.apps.googleusercontent.com']);
        $payload = $client->verifyIdToken($token);

        if (!$payload) {
            http_response_code(400);
            die('Invalid token!');
        }
        else if ($payload['hd'] !== 'student.mes.ac.in') {
            http_response_code(400);
            die('Invalid domain!');
        }

        return $payload;
    }

    public function run(string $type, string $url): void {
        $resource = self::decodeResource($url);

        $resourceIndex = array_search($resource, array_column($this->resources[$type], 'url'));

        if ($resourceIndex !== false) {
            if ($_GET['token'] ?? $_POST['token'] ?? false) {
                $user = self::verifyAndDecodeToken($_GET['token'] ?? $_POST['token']);
            }

            $response = $this->resources[$type][$resourceIndex]['handler'](
                array_merge($type === 'GET' ? $_GET : $_POST, [
                    'user' => $user ?? null
                ])
            );
            
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