<?php

namespace CoreLib\Adapter;

use ZF\OAuth2\Adapter\PdoAdapter;

class OAuth2PdoAdapter extends PdoAdapter {

//    protected $mapper;
//
//    public function setUsersMapper(Users\MapperInterface $mapper) {
//        exit('setmapper');
//        $this->mapper = $mapper;
//    }

    public function checkUserCredentials($username, $password) {
        exit('credentials');
        return $this->mapper->validate($username, $password);
    }

    public function getUser($username) {
        exit('getUser');
        $user = $this->mapper->fetch($username, $asArray = true);
        if (!$user) {
            return false;
        }
        unset(
                $user['activated'], $user['activated_date'], $user['activation_key'], $user['password']
        );
        return array_merge(array(
            'user_id' => $username,
                ), $user->getArrayCopy());
    }

    public function setUser($username, $password, $firstName = null, $lastName = null) {
        exit('setuser');
        $user = $this->mapper->create(
                $username, $password, sprintf('%s %s', $firstName, $lastName), $asArray = true
        );
        if (false === $user) {
            return false;
        }
        return true;
    }

}
