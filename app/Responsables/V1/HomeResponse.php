<?php

namespace App\Responsables\V1;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use App\Transformers\V1\UserTransformer;
use App\Responsables\ResponseWithManager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\TransformerAbstract;
use Symfony\Component\Intl\DateFormatter\DateFormat\Transformer;

class HomeResponse extends ResponseWithManager
{

    private TransformerAbstract $transformer;

    public function __construct($data, bool $as_collection)
    {
        parent::__construct($data, $as_collection);
        $this->transformer = $data->getModel()->transformer();
    }

    /**
     * Create a response that returns JSON data.
     *
     * @param mixed $item
     *
     * @return Illuminate\Http\Response
     */
    public function toJsonResponse($item)
    {
        $resource = new Item($item, new $this->transformer);
        return ['data' => $this->createData($resource)->toArray()];
    }

    /**
     * Create a response that returns JSON data.
     *
     * @param mixed $collection
     *
     * @return Illuminate\Http\Response
     */
    public function toJsonCollectionResponse($collection)
    {

        $manager = new Manager();
        $resource = new Collection($collection->get(), $this->transformer);
        return $manager->createData($resource)->toArray();
    }

    public function setTransformer(TransformerAbstract $transformer)
    {
        $this->transformer = $transformer;
    }
}
