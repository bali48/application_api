<?php
namespace user\V1\Rest\User;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use CoreLib\UserResource as BaseUserResource;
use Zend\Crypt\Password\Bcrypt;
class UserResource extends BaseUserResource
{
    protected $bcrypt = NULL;
    /*
     * init
     */
    public function init() {
        $this->setNameSpace(__NAMESPACE__);
        $this->setEntity('UserEntity');
        $this->setModel('UserModel');
        parent::init();
    }  
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        $data = $this->getInputFilter()->getValues();
        if ($data instanceof Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }
        if (is_object($data)) {
            $data = (array) $data;
        }
        if (is_array($data)) {
            var_dump($data);
            exit;
            $UserEntity = $this->getEntity();
            $UserEntity->setCreatedBy($this->UserSession->UserID);
            $User = $this->hydrator->hydrate($data, $UserEntity);
            try {
                return $this->getModel()->insert($User);
            } catch (\Exception $e) {
                return $this->throwCustomException('DbError', $e);
            }
        } else {
            return $this->throwCustomException('Internal');
        }
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = array())
    {
        try {
//            if($params->Type == 'User') {              
                return $this->getModel()->getUserListByUserID($this->filterQueryParams($params));                
//            } else {
//                return $this->getModel()->getGroupUserListByUserList(null, null, $this->filterQueryParams($params));                
//            }
        } catch (\Exception $e) {
            return $this->throwCustomException('DbError', $e);
        }
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
