<?php


namespace App\Services\RevenueCat;


use App\Services\RevenueCat\Enums\Platform;
use App\Services\RevenueCat\Exceptions\BadRequestException;
use App\Services\RevenueCat\Exceptions\ServerErrorException;
use App\Services\RevenueCat\Exceptions\UnauthorizedException;
use App\Services\RevenueCat\Interfaces\ApiManagerInterface;
use App\Services\RevenueCat\Interfaces\ObjectInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiManager implements ApiManagerInterface
{
    protected PendingRequest $client;
    protected ?string $platform;

    public function __construct(string $apiKey)
    {
        $this->client = Http::withToken($apiKey)
            ->acceptJson()
            ->contentType('application/json');
    }

    public function get(string $url, callable $onSuccess)
    {

            $response = $this->client->get($url);
            return $this->handleResponse($response, $onSuccess);
    }

    public function post(string $url, array $data, callable $onSuccess)
    {
        try {
            $response = $this->client->post($url, $data);
            return $this->handleResponse($response, $onSuccess);
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }
    }

    public function delete(string $url, callable $onSuccess)
    {
        try {
            $response = $this->client->delete($url);
            return $this->handleResponse($response, $onSuccess);
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }

    }

    private function handleResponse(Response $response, callable $onSuccess)
    {
        if($response->successful()){
            return $onSuccess($response->json());
        } else if($response->status() === 400) {
            throw new BadRequestException($response);
        } else if($response->status() === 401) {
            throw new UnauthorizedException();
        } else if($response->serverError()) {
            throw new ServerErrorException();
        }
    }

    /**
     * @return string
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     */
    public function setPlatform(string $platform): ApiManager
    {
        if(in_array($platform, Platform::values())){
            $this->platform = $platform;
            $this->client = $this->client->withHeaders([
                'X-Platform' => $platform
            ]);
        } else {
            Log::alert('RevenueCat platform is not valid');
        }

        return $this;
    }

    /**
     * @param string $platform
     */
    public function unsetPlatform(): ApiManager
    {
        $this->platform = null;
        $this->client = $this->client->withHeaders([]);
        return $this;
    }

}
