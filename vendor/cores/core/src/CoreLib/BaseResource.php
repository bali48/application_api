<?php

namespace CoreLib;

//use ZF\Rest\AbstractResourceListener;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\ApiProblem\ApiProblem;
use Zend\Config\Reader\Yaml;
use ZF\Rest\ResourceEvent;
use Zend\Session\Container;

require_once APPLICATION_PATH . '/vendor/spyc-master/Spyc.php';

class BaseResource extends AbstractResourceListener implements ServiceLocatorAwareInterface {

    /**
     * @var ObjectPropertyHydrator
     */
    protected $hydrator;

    /**
     * @var Entity
     */
    protected $serviceLocator;

    /**
     * @var Entity Manager
     */
    protected $em;   

    /**
     * @var RoleModel
     */
    protected $model;

    /**
     * @var RoleEntity
     */
    protected $entity;

    /*
     * @var __NAMESPACE__ constant
     */
    protected $nameSpace;

    /*
     * @var ymlReader 
     */
    protected $ymlReader;

    /*
     * @var ymlData
     */
    protected $ymlData;

    /*
     * @var langCode
     */
    protected $langCode = 'en';

    /*
     * @var UserSession
     * current logged in user session object
     */
    protected $UserSession;

    /*
     * @var PriviligesTokanSession
     * Priviliged Resources object for the current logged in user
     */
    protected $PriviligesTokanSession;

    /*
     * @var isParamsQryWhitelst
     * to check wheter patameters are defined in configuration
     */
    protected $isParamsQryWhitelst = false;

    /*
     * init
     */

    public function init() {
        $this->hydrator = new ObjectPropertyHydrator();
        $this->em = $this->serviceLocator->get('Doctrine\ORM\EntityManager');
        //$this->alternateEm = $this->serviceLocator->get('doctrine.entitymanager.orm_alternative');

        $className = $this->nameSpace . "\\" . $this->model;
        $entityClass = $this->nameSpace . "\\" . $this->entity;               
        $this->entity = new $entityClass;
                        

        $this->model = new $className(
                $this->getEntityManager(), $this->getEntityManager()->getClassMetadata(
                        $entityClass
                )
        );        
        
        $this->ymlReader = new Yaml(array('Spyc', 'YAMLLoadString'));

        /*
         * on based on collection_query_whitelist 
         * set $queryParamsValidation
         * on based on QueryParamsValidator
         * set isParamsQryWhitelst
         */
        if (!empty($this->serviceLocator->get('config')['zf-rest'][$this->getNameSpace() . '\Controller']['collection_query_whitelist'])) {
            $this->isParamsQryWhitelst = true;
            if (isset($this->serviceLocator->get('config')['input_filter_specs'][$this->getNameSpace() . '\QueryParamsValidator'])) {
                $this->queryParamsValidation = $this->serviceLocator->get('config')['input_filter_specs'][$this->getNameSpace() . '\QueryParamsValidator'];
            }
        }

        $this->setUserSession();
    }

    /*
     * Implementation of setServiceLocator of ServiceLocatorAwareInterface 
     */

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        $this->init();
    }

    /*
     * Implementation of getServiceLocator ServiceLocatorAwareInterface 
     */

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    /**
     * Dispatch an incoming event to the appropriate method
     *
     * arguments from the event parameters.
     *
     * Returns an ApiProblem if Query parameter define but its validation not defined.
     *
     * @param  ResourceEvent $event
     * @return mixed
     */
    public function dispatch(ResourceEvent $event) {
        if ($this->isParamsQryWhitelst === true && !is_array($this->queryParamsValidation)) {
            return $this->throwCustomException("ExptdQryParmValidation");
        }
        return parent::dispatch($event);
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data) {
        //call update function
        $this->update($id, $data);
    }

    /*
     * @var getEntity Object  
     */

    public function getEntity() {
        return $this->entity;
    }

    public function setEntity($entity) {        
        $this->entity = $entity;
    }

    /*
     * @var getModel Object  
     */

    public function getModel() {
        return $this->model;
    }

    public function setModel($model) {        
        $this->model = $model;
    }

    /*
     * @var getNameSpace constant
     */

    public function getNameSpace() {
        return $this->nameSpace;
    }

    /*
     * @var setNameSpace constant
     */

    public function setNameSpace($nameSpace) {        
        $this->nameSpace = $nameSpace;
    }

    /**
     * @return \Doctrine\ORM\EntityManager doctrine entity manger object
     */
    public function getEntityManager() {
        return $this->em;
    }  

    /*
     * return Messages Array form  YML files
     */

    public function getYmlData() {
        $moduleYmlName = APPLICATION_PATH . '/module/' . $this->getModuleName() . '/config/messages.' . $this->langCode . '.yml';
        $baseYmlName = __DIR__ . '/../../config/messages.' . $this->langCode . '.yml';
        if (!file_exists($baseYmlName) || !file_exists($moduleYmlName)) {
            return $this->throwCustomException('Yml file not found, Please check in your module/core.');
        }
        $ymlData = $this->getYmlReader()->fromFile($moduleYmlName);

        $BaseYMLData = $this->getYmlReader()->fromFile($baseYmlName);
        $combinedYMLData = array_merge($BaseYMLData, $ymlData);
        return $this->ymlData = $combinedYMLData;
    }

    /*
     * return YMLReader
     */

    public function getYmlReader() {
        return $this->ymlReader;
    }

    public function getModuleName() {
        $moduleName = explode('\\', get_called_class());
        return $moduleName[0];
    }

    /*
     * @var setUserSession
     */

    public function setUserSession() {
        //To Store the current logged in user session object
        $this->UserSession = new Container('Apigility_Custom');

        if (isset($this->UserSession->token)) {
            $this->setPriviligesTokanSession();
        }
    }

    /*
     * @var getUserSession 
     */

    public function getUserSession() {
        return $this->UserSession;
    }

    /*
     * @var setPriviligesTokanSession
     */

    public function setPriviligesTokanSession() {
        //To Store the Priviliged Resources object for the current logged in user
        $this->PriviligesTokanSession = new Container('Tokan_' . $this->UserSession->token['access_token']);
    }

    /*
     * @var getPriviligesTokanSession 
     */

    public function getPriviligesTokanSession() {
        return $this->PriviligesTokanSession;
    }

    /**
     * @return dateTime format according to db
     */
    public function dateTimeSQLFormat($date, $time = false, $ampm = false, $format ='j/m/Y') {
        if (empty($date))
            return null;
        
        if ($time) {
            if ($ampm)
                $format .= ' h:i A';
            else
                $format .= ' H:i';
        }
                
        $DateTime = \DateTime::createFromFormat($format, $date);        
        if ($DateTime) {            
            if ($time)
                return $DateTime->format('Y-m-d H:i:s');
            else
                return $DateTime->format('Y-m-d');
        } else {
            return $date;
        }
    }

    /**
     * @return dateTime format
     */
    public function dateFormat($date, $time = false, $dateformat = 'd-M-Y') {
        $format = $dateformat;
        if ($time)
            $format .= ' H:i:s';
        if (is_object($date))
            $DateTime = $date;
        else
            $DateTime = new \DateTime($date);

        return $DateTime->format($format);
    }
    
    /**     
     * @return array
     */
    public function validateDate($date) {
        $isValid = false;
        $format = '';
         // DD-MM-Y Format
        if (preg_match("/^(\d{1,2})-([A-Za-z]{3})-(\d{2})$/", $date, $matches)) {
             $monthArray = array(
                1 => "Jan",
                2 => "Feb",
                3 => "Mar",
                4 => "Apr",
                5 => "May",
                6 => "Jun",
                7 => "Jul",
                8 => "Aug",
                9 => "Sep",
                10 => "Oct",
                11 => "Nov",
                12 => "Dec"
            );             
             $month = array_search($matches[2], $monthArray);             
             if($month !== false){
                 if (checkdate($month, $matches[1], $matches[3])) {
                    $isValid = true;
                    $format = 'd-M-y';
                }
            }            
        } 
        // YYYY-MM-DD Format
        else if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {                
                $isValid = true;
                $format = 'Y-m-d';
            }
        }        
        // DD-MM-YYYY Format
        else if (preg_match("/^(\d{2})-(\d{2})-(\d{4})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[1], $matches[3])) {
                $isValid = true;
                $format = 'd-m-Y';
            }
        }        
        // YYYY/MM/DD Format
        else if (preg_match("/^(\d{4})\/(\d{2})\/(\d{2})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                $isValid = true;
                $format = 'Y/m/d';
            }
        }
       // DD/MM/YYYY Format
        else if (preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[1], $matches[3])) {
                $isValid = true;
                $format = 'd/m/Y';
            }
        }                        
        return array('isValid' => $isValid, 'format' => $format);
    }

    /**
     * @param type $params
     * @return params after filteration
     */
    public function filterQueryParams($params) {
        if (array_key_exists('page', $params)) {
            unset($params['page']);
        }
        return $params;
    }

    /*
     * return IOFactory
     */

    public function loadExcelClass() {
        require_once APPLICATION_PATH . '/vendor/PHPExcel/Classes/PHPExcel/IOFactory.php';
    }

    /*
     * Throw Custom exception
     */

    public function throwCustomException($code, $ex = null, $IsAppendCustomMessage = false) {
        $httpCode = 500;
        $type = 'error';
        $message = $code;
        $ymlData = $this->getYmlData();
        if (!is_null($ex)) {

            file_put_contents(getcwd() . 'log-' . date('Y-m-d') . '.txt', "\n\r" . date('Y-m-d H:i:s') . " ----" . $ex->getMessage() . ' ', FILE_APPEND);

            $msgs = $this->getExceptionMessages($ex->getMessage());

            if (is_array($msgs)) {
                $message = "";
                foreach ($msgs as $msg) {

                    $message .= ($message && $message != "") ? "<br>" : "";
                    if (isset($ymlData[$msg['error'][0]])) {
                        $httpCode = $ymlData[$msg['error'][0]]['HttpCode'];
                        $type = $ymlData[$msg['error'][0]]['Type'];
                        $message .= $ymlData[$msg['error'][0]]['Message'];
                    } else {
                        if ($IsAppendCustomMessage == true) {
                            if (isset($ymlData[$code])) {
                                $httpCode = $ymlData[$code]['HttpCode'];
                                $type = $ymlData[$code]['Type'];
                                $message .= $ymlData[$code]['Message'];
                            }
                        }
                        $message .= $msg['error'][0];
                    }
                }
            }
        } else {
            if (isset($ymlData[$code])) {

                $httpCode = $ymlData[$code]['HttpCode'];
                $type = $ymlData[$code]['Type'];
                $message = $ymlData[$code]['Message'];
            }
        }

        return new ApiProblemResponse(
                new ApiProblem($httpCode, ($message) ? $message : ( $message .
                        (getenv('APPLICATION_ENV') && (!is_null($ex)) ?
                                " - " . $ex->getMessage() :
                                '')), $type)
        );
    }

    /**
     * Returns exception data ( category, message ) from custom exception object
     * @param type $message
     * @return array
     */
    public function getExceptionData($message) {
        preg_match('^<message category\="(.*)">(.*)</message>^', $message, $Matches);
        if (empty($Matches)) {
            return array();
        }
        return array($Matches[1], $Matches[2]);
    }

    /**
     * Returns exception data ( category, message ) from custom exception object
     * @param type $message
     * @return array
     */
    public function getExceptionMessages($message) {
//        if( substr_count($message, '<messagegroup>') > 0 ) {
//            preg_match_all('#<message category\="(\w+)">(\w+)</message>#', $message, $Matches);
//        } else {
//            preg_match_all('#<message category\="(.*)">(.*)</message>#', $message, $Matches);
//        }

        preg_match_all('(\<message category\="(.*)"\>(.*)\<\/message\>)U', $message, $Matches);
        if (empty($Matches)) {
            return array();
        }
        $messages = array();
        foreach ($Matches[1] as $i => $category) {
            $messages[] = array($category => array($Matches[2][$i], null));
        }
        return $messages;
    }

}
