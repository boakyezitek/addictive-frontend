<?php

namespace App\Responsables\V1;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use App\Transformers\V1\UserTransformer;
use App\Responsables\ResponseWithManager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class UserResponse extends ResponseWithManager
{
    /**
     * Create a response that returns JSON data.
     *
     * @param mixed $item
     *
     * @return Illuminate\Http\Response
     */
    public function toJsonResponse($item)
    {
        $resource = new Item($item, new UserTransformer());
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
        $paginator = $collection->paginate();
        $paginated_collection = $paginator->getCollection();

        $manager = new Manager();
        $resource = new Collection($paginated_collection, new UserTransformer());
        $response = $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $manager->createData($response)->toArray();
    }
}
