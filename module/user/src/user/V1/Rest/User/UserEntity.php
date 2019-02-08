<?php
namespace user\V1\Rest\User;
use Doctrine\ORM\Mapping as ORM;
use CoreLib\UserEntity as BaseUserEntity;

/** @ORM\Entity
 * @ORM\Table(name="umg_tbuser")
 */
class UserEntity extends BaseUserEntity
{
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $UserID;


    /**
     * @ORM\Column(type="string")
     */
    public $Username;


    /**
     * @ORM\Column(type="boolean")
     */
    public $IsActive;

    /**
     * @ORM\Column(type="boolean")
     */
    public $IsDeleted;

    /**
     * @ORM\Column(type="integer")
     */
    public $CreatedBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $CreatedOn;

    /**
     * @ORM\Column(type="integer")
     */
    public $LastModifiedBy;

    /**
     * @ORM\Column(type="datetime")
     */
    public $LastModifiedOn;

    /**
     * @ORM\Column(type="string")
     */
    public $UserGUID;
    
    /**
     * @ORM\Column(type="string")
     */
    public $UserEmail;
    
    /**
     * @ORM\Column(type="string")
     */
    public $PlainPassword;
    
     /** @return integer */
    public function getUserID() {
        return $this->UserID;
    }
    /** @return string */
    public function getUsername() {
        return $this->Username;
    }
    /** @return boolean */
    public function getIsActive() {
        return $this->IsActive;
    }
    /** @return boolean */
    public function getIsDeleted() {
        return $this->IsDeleted;
    }
    /** @return integer */
    public function getCreatedBy() {
        return $this->CreatedBy;
    }
    /** @return datetime */
    public function getCreatedOn() {
        return $this->CreatedOn;
    }
    /** @return integer */
    public function getLastModifiedBy() {
        return $this->LastModifiedBy;
    }
    /** @return datetime */
    public function getLastModifiedOn() {
        return $this->LastModifiedOn;
    }
    /** @return string */
    public function getUserGUID() {
        return $this->UserGUID;
    }
    /** @return string */
    public function getUserEmail() {
        return $this->UserEmail;
    }
    /**
     * @param integer $UserID
     * @return void
     */
    public function setUserID($UserID) {
        $this->UserID = $UserID;
    }
    /**
     * @param integer $Username
     * @return void
     */
    public function setUsername($Username) {
        $this->Username = $Username;
    }
    /**
     * @param boolean $IsActive
     * @return void
     */
    public function setIsActive($IsActive) {
        $this->IsActive = $IsActive;
    }
     /**
     * @param boolean $IsDeleted
     * @return void
     */
    public function setIsDeleted($IsDeleted) {
        $this->IsDeleted = $IsDeleted;
    }
     /**
     * @param integer $CreatedBy
     * @return void
     */
    public function setCreatedBy($CreatedBy) {
        $this->CreatedBy = $CreatedBy;
    }
    /**
     * @param integer $CreatedOn
     * @return void
     */
    public function setCreatedOn($CreatedOn) {
        $this->CreatedOn = $CreatedOn;
    }
    /**
     * @param integer $LastModifiedBy
     * @return void
     */
    public function setLastModifiedBy($LastModifiedBy) {
        $this->LastModifiedBy = $LastModifiedBy;
    }
    /**
     * @param integer $LastModifiedOn
     * @return void
     */
    public function setLastModifiedOn($LastModifiedOn) {
        $this->LastModifiedOn = $LastModifiedOn;
    }
    /**
     * @param integer $UserGUID
     * @return void
     */
    public function setUserGUID($UserGUID) {
        $this->UserGUID = $UserGUID;
    }
    
    /**
     * @param integer $UserEmail
     * @return void
     */
    public function setUserEmail($UserEmail) {
        $this->UserEmail = $UserEmail;
    }
    /** @return string */
    public function getPlainPassword() {
        return $this->PlainPassword;
    }
    /**
     * @param integer $UserEmail
     * @return void
     */
    public function setPlainPassword($PlainPassword) {
        $this->PlainPassword = $PlainPassword;
    }


}
