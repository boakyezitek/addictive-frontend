<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO;

    public function __construct(Repository $config)
    {
        parent::__construct($config);

        if ($config->get('app.behind_load_balancer', false)) {
            $raw_ips_list = $config->get('app.load_balancer_ips', '*');

            $this->proxies = $raw_ips_list !== null ? explode(',', $raw_ips_list) : '*';
        }
    }
}
