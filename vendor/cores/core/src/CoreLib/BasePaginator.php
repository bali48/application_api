<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace CoreLib;

use stdClass;
use Zend\Paginator\Adapter\ArrayAdapter as ArrayPaginator;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Specialized Zend\Paginator\Adapter\ArrayAdapter instance for returning
 * hydrated entities.
 */
class BasePaginator extends ArrayPaginator
{
    /**
     * @var object
     */
    protected $entityPrototype;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var object
     */
    protected $model;
    
    /**
     * @var object
     */
    protected $criteria;
    
    /**
     * @param null $hydrator hyderator class reference object
     * @param null $entityPrototype entity class refence object
     * @param null $model model class reference object
     */
    public function __construct($hydrator = null, $entityPrototype = null, $model = null, $criteria = null)
    {
        $this->array = array();
        $this->model = $model;
        $this->criteria = $criteria;
        
        $this->hydrator = $hydrator;
        $this->entityPrototype = $entityPrototype ?: new stdClass;
    }
        

    /**
     * Override getItems()
     *
     * Overrides getItems() to return a collection of entities based on the
     * provided Hydrator and entity prototype, if available.
     *
     * @param int $offset
     * @param int $itemCountPerPage
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {        
        $set = $this->array;        
        if (! $this->hydrator instanceof HydratorInterface) {
            return $set;
        }

        $collection = array();
        foreach ($set as $item) {
            $collection[] = $this->hydrator->hydrate($item, clone $this->entityPrototype);
        }
        return $collection;
    }
}
