<?php

namespace App\Services\RevenueCat\Interfaces;

use App\Services\RevenueCat\ApiManager;

interface ApiManagerInterface {
    public function get(string $url, callable $onSuccess);
    public function post(string $url, array $data, callable $onSuccess);
    public function delete(string $url, callable $onSuccess);

    public function getPlatform(): ?string;
    public function setPlatform(string $platform): ApiManager;
    public function unsetPlatform(): ApiManager;
}
