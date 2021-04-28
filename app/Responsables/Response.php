<?php

namespace App\Responsables;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Responsable;

abstract class Response implements Responsable
{
    /**
     * Constant representing a successfully sent reminder.
     *
     * @var string
     */
    const AS_COLLECTION = true;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var bool
     */
    protected $as_collection = false;

    /**
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * @param mixed  $data
     * @param boolean $as_collection
     */
    public function __construct($data, $as_collection = false)
    {
        $this->data = $data;
        $this->as_collection = $as_collection;
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
        
        if ($this->isCollection()) {
            return $this->toJsonCollectionResponse($this->data);
        }
        return $this->toJsonResponse($this->data);
        

        return $this->toHtmlResponse();
    }

    /**
     * Determine if response should return a collection.
     *
     * @return boolean
     */
    public function isCollection()
    {
        return $this->as_collection;
    }
}
