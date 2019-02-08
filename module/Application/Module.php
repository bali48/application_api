<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Uri\UriFactory;
use Zend\Mvc\MvcEvent;
use ZF\MvcAuth\MvcAuthEvent;
use Zend\Mvc\Controller\AbstractActionController;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;
use Zend\Mvc\ModuleRouteListener;
use Zend\Session\Container;
use Zend\Http\Request as HttpRequest;
use Zend\Console\Request as ConsoleRequest;

class Module extends AbstractActionController {
    public $moduleRouteListener;
    public $eventManager;
    public $serviceManager; 
    
    public function setSession() { 
       
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        if ($e->getRequest() instanceof HttpRequest) {
            $headers = $e->getRequest()->getHeaders();
            
            // do something important for Http
            //Postman(chrome extension) setting
            UriFactory::registerScheme('chrome-extension', 'Zend\Uri\Uri');
            //Time zone setting
            date_default_timezone_set('Asia/Karachi');
           
            
            $this->eventManager        = $e->getApplication()->getEventManager();

            $this->moduleRouteListener = new ModuleRouteListener();
            $this->moduleRouteListener->attach($this->eventManager);
            $this->serviceManager = $e->getApplication()->getServiceManager();
            //apigilityCustomSession
             $this->customSession = new Container('Apigility_Custom');

               //set Session
            $this->eventManager->attach(MvcAuthEvent::EVENT_AUTHENTICATION_POST, function (MvcAuthEvent $e) {
                $identity = $e->getIdentity();      
                $token = $identity->getAuthenticationIdentity(); 

                if (!$identity instanceof AuthenticatedIdentity) {
                    return;
                }                
                $token = $identity->getAuthenticationIdentity();                
                if (isset($this->customSession->user)) {

                    $this->PriviligesTokanSession = new Container('Tokan_' . $this->customSession->token['access_token']);
                    
                    if ($this->customSession->token['access_token'] != $token['access_token']) {
                        $this->customSession->getManager()->getStorage()->clear('Apigility_Custom');
                        $this->PriviligesTokanSession->getManager()->getStorage()->clear('Tokan_' . $this->customSession->token['access_token']);
                    }
                }
                if (!isset($this->customSession->user)) {   
                    echo 'inside condition customSession';

                    $this->customSession->user = $token['user_id'];
                    $this->customSession->token = $token;
                    $this->setSession();
                }
            });
            
        }elseif ($e->getRequest() instanceof ConsoleRequest) {
            // do something important for Console
           
        }
        
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
