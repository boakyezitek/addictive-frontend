<?php


namespace App\Services\RevenueCat\Objects;

use App\Services\RevenueCat\Interfaces\ObjectInterface;
use Carbon\Carbon;

class Response extends BaseObject implements ObjectInterface
{
    private Carbon $requestDate;
    private int $requestDateMs;
    private Subscriber $subscriber;

    public function __construct(Carbon $requestDate, int $requestDateMs, Subscriber $subscriber)
    {
        $this->requestDate = $requestDate;
        $this->requestDateMs = $requestDateMs;
        $this->subscriber = $subscriber;
    }

    public static function fromJson(array $json): Response
    {
        return new Response(
            Carbon::parse($json['request_date']),
            $json['request_date_ms'],
            Subscriber::fromJson($json['subscriber'])
        );
    }

    /**
     * @return Carbon
     */
    public function getRequestDate(): Carbon
    {
        return $this->requestDate;
    }

    /**
     * @return int
     */
    public function getRequestDateMs(): int
    {
        return $this->requestDateMs;
    }

    /**
     * @return Subscriber
     */
    public function getSubscriber(): Subscriber
    {
        return $this->subscriber;
    }

}
