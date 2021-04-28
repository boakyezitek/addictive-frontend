<?php

namespace App\Responsables;

use Illuminate\Http\Request;
use League\Fractal\Scope;
use League\Fractal\Manager;
use App\Serializers\CustomArraySerializer;
use League\Fractal\Resource\ResourceInterface;

class ResponseWithManager extends Response
{

    /**
     * Fractal resource manager
     *
     * @var League\Fractal\Manager
     */
    protected $manager;

    /**
     * @param mixed  $data
     * @param boolean $as_collection
     */
    public function __construct($data, $as_collection = false)
    {
        parent::__construct($data, $as_collection);
        $this->createManager();
    }

    /**
     * Create a new manager and update it
     *
     * @return void
     */
    final private function createManager()
    {
        $this->manager = new Manager();
        if(request()->input('include')) {
            $this->manager->parseIncludes(request()->input('include'));
        }
        $this->manager->setSerializer(new CustomArraySerializer());
    }

    /**
     * Call createData method of the manager
     *
     * @param mixed $resource
     * @param string $scopeIdentifier
     * @param Scope $parentScopeInstance
     *
     * @return Scope
     */
    protected function createData(ResourceInterface $resource, $scopeIdentifier = null, Scope $parentScopeInstance = null)
    {
        return $this->manager->createData($resource, $scopeIdentifier, $parentScopeInstance);
    }
}
