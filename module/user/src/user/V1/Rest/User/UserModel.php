<?php
namespace User\V1\Rest\User;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;
use CoreLib\UserModel;
use Zend\Crypt\Password\Bcrypt;

class UserModel extends UserModel
{  
    protected $bycrpt = null;
    /**
     * @param array $params
     * @return \User\V1\Rest\User\UserEntity
     */
    public function getUserListByUserID($params) {        
        $sql = 'select * from user ';
        $result_mapping = new ResultSetMappingBuilder($this->getEntityManager());
        $result_mapping->addRootEntityFromClassMetadata('\User\V1\Rest\User\UserEntity', 'User');

        $query = $this->getEntityManager()->createNativeQuery($sql, $result_mapping);

        return $query->getArrayResult();
    }
   
    public function InsertNewUser(\user\V1\Rest\User\UserEntity $UserData){
        $this->bcrypt = new  Bcrypt();
        $connection = $this->getEntityManager()
                ->getConnection()
                ->getWrappedConnection();

        $stmt = $connection->prepare('CALL UserInsert(?, ?, ?, ?, ?, ?, @output)');
        $stmt->bindParam(1, $UserData->getUsername(), \PDO::PARAM_STR);
        $stmt->bindParam(2, $UserData->getUserEmail(), \PDO::PARAM_STR);
        $stmt->bindParam(3, $this->bcrypt->create($UserData->getPlainPassword()), \PDO::PARAM_STR);
        $stmt->bindParam(4, $this->bcrypt->create($UserData->getPlainPassword()), \PDO::PARAM_STR);
        $stmt->bindParam(5, $UserData->getIsActive(), \PDO::PARAM_STR);
        $stmt->bindParam(6, $UserData->getCreatedBy(), \PDO::PARAM_STR);
        $stmt->execute();
        $stmt = $connection->query("SELECT @output");
        $id = $stmt->fetchColumn();
        return $id;
    }
}