<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZF\Hal;

use Zend\Loader\StandardAutoloader;
use Zend\Mvc\MvcEvent;

/**
 * ZF2 module
 */
class Module
{
    /**
     * Retrieve autoloader configuration
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            StandardAutoloader::class => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/',
                ],
            ],
        ];
    }

    /**
     * Retrieve module configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Listener for bootstrap event
     *
     * Attaches a render event.
     *
     * @param  MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $events = $e->getTarget()->getEventManager();
        $events->attach(MvcEvent::EVENT_RENDER, [$this, 'onRender'], 100);
    }

    /**
     * Listener for the render event
     *
     * Attaches a rendering/response strategy to the View.
     *
     * @param  MvcEvent $e
     */
    public function onRender(MvcEvent $e)
    {
        $result = $e->getResult();
        if (!$result instanceof View\HalJsonModel) {
            return;
        }

        $services = $e->getTarget()->getServiceManager();
        $events   = $services->get('View')->getEventManager();

        // register at high priority, to "beat" normal json strategy registered
        // via view manager
        $events->attach($services->get('ZF\Hal\JsonStrategy'), 200);
    }
}
