<?php

class Router {
    private array $resources = [
        'GET' => [],
        'POST' => [],
        'DELETE' => [],
        'PUT' => []
    ];

    public function get(string $url, callable $handler): void {
        $this->resources['GET'][] = ['url' => $url, 'handler' => $handler]; 
    }

    public function post(string $url, callable $handler): void {
        $this->resources['POST'][] = ['url' => $url, 'handler' => $handler];
    }

    public function put(string $url, callable $handler): void {
        $this->resources['PUT'][] = ['url' => $url, 'handler' => $handler];
    }

    public function delete(string $url, callable $handler): void {
        $this->resources['DELETE'][] = ['url' => $url, 'handler' => $handler];
    }

    public function run(string $type, string $url): void {
        $resourceIndex = array_search($url, array_column($this->resources[$type], 'url'));

        if ($resourceIndex !== false) {
            $this->resources[$type][$resourceIndex]['handler']();
        }
        else {
            echo 'Invalid Resource!';
        }
    }
}