<?php

namespace App\Responsables;

use Spatie\Fractal\Fractal;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseResponse implements Responsable
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var bool
     */
    protected $as_collection = false;

    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected $paginator;

    /**
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Spatie\Fractal\Fractal
     */
    protected $fractal;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * Construct the object.
     */
    public function __construct()
    {
        $this->fractal = Fractal::create()->withResourceName('data');
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        $this->request = $request;

        return $this->request->wantsJson() ? $this->createJsonResponse() : $this->toHtmlResponse();
    }

    /**
     * Return a single item.
     *
     * @param mixed $data
     *
     * @return self
     */
    public function item($data)
    {
        $this->data = $data;

        $this->as_collection = false;

        return $this;
    }

    /**
     * Return a collection of item.
     *
     * @param mixed $data
     *
     * @return self
     */
    public function collection($data)
    {
        $this->data = $data;

        $this->as_collection = true;

        return $this;
    }

    /**
     * Paginator to use for the collection.
     *
     * @param  \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator
     *
     * @return self
     */
    public function paginator(LengthAwarePaginator $paginator = null)
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * @param  array  $headers
     *
     * @return self
     */
    public function withHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Determine if response should return a collection.
     *
     * @return boolean
     */
    protected function isCollection()
    {
        return $this->as_collection;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createJsonResponse() : JsonResponse
    {
        $response = new JsonResponse;

        if ($this->isCollection()) {
            $data = $this->toJsonCollectionResponse($this->data, $this->paginator);
        } else {
            $data = $this->toJsonResponse($this->data);
        }

        $response->setData($data);
        $response->withHeaders($this->headers);

        return $response;
    }
}
