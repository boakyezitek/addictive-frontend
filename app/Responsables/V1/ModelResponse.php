<?php

namespace App\Responsables\V1;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use App\Transformers\V1\UserTransformer;
use App\Responsables\ResponseWithManager;
use App\Serializers\CustomArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Symfony\Component\Intl\DateFormatter\DateFormat\Transformer;

class ModelResponse extends ResponseWithManager
{

    private TransformerAbstract $transformer;
    private int $per_page;

    public function __construct($data, bool $as_collection, $per_page = 15)
    {
        parent::__construct($data, $as_collection);
        $this->transformer = $data->getModel()->transformer();
        $this->per_page = $per_page;
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
        $paginator = $collection->paginate($this->per_page)->appends(request()->input());
        $paginated_collection = $paginator->getCollection();
        $manager = new Manager();
        if (isset($_GET['include'])) {
            $manager->parseIncludes($_GET['include']);
            $manager->setSerializer(new CustomArraySerializer());
            $resource = new Collection($paginated_collection, $this->transformer, 'data');
        } else {
            $resource = new Collection($paginated_collection, $this->transformer);
        }
        $response = $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $manager->createData($response)->toArray();
    }

    public function setTransformer(TransformerAbstract $transformer)
    {
        $this->transformer = $transformer;
    }
}
