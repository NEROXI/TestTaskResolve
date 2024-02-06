<?php

namespace Root\Html;

require_once __DIR__ . '/../../vendor/autoload.php';

use Symfony\Component\VarDumper\VarDumper;

interface HttpServiceInterface {
    public function request(string $url, string $method, array $options = []): void;
}

class XMLHttpService implements HttpServiceInterface {
    public function request(string $url, string $method, array $options = []): void {
        VarDumper::dump("Making XML HTTP request");
    }
}

class Http {
    private $service;

    public function __construct(HttpServiceInterface $httpService) {
        $this->service = $httpService;
    }

    public function get(string $url, array $options = []): void {
        $this->service->request($url, 'GET', $options);
    }

    public function post(string $url, array $options = []): void {
        $this->service->request($url, 'POST', $options);
    }
}
