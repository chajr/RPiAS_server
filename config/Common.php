<?php
namespace Aura\Framework_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;
use Aura\View\TemplateRegistry;
use Aura\View\View;
use Config\Config as SystemConfig;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Common extends Config
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param Container $dependency
     * @return void
     * @throws \Aura\Di\Exception\ServiceNotObject
     * @throws \Aura\Di\Exception\ContainerLocked
     */
    public function define(Container $dependency)
    {
        $dependency->set('aura/project-kernel:logger', $dependency->lazyNew(Logger::class));

        $dependency->params[TemplateRegistry::class]['paths'] = [
            dirname(__DIR__) . '/templates/views',
            dirname(__DIR__) . '/templates/layouts',
        ];

        $dependency->set('view', $dependency->lazyNew(View::class));
    }

    /**
     * @param Container $dependency
     * @return void
     * @throws \Aura\Di\Exception\SetterMethodNotFound
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    public function modify(Container $dependency)
    {
        $this->modifyLogger($dependency);
        $this->modifyCliDispatcher($dependency);
        $this->modifyWebRouter($dependency);
        $this->modifyWebDispatcher($dependency);
    }

    /**
     * @param Container $dependency
     * @throws \Aura\Di\Exception\ServiceNotFound
     * @throws \Aura\Di\Exception\SetterMethodNotFound
     */
    protected function modifyLogger(Container $dependency)
    {
        $project = $dependency->get('project');
        $mode = $project->getMode();
        $file = $project->getPath("tmp/log/{$mode}.log");

        $logger = $dependency->get('aura/project-kernel:logger');
        $logger->pushHandler($dependency->newInstance(
            StreamHandler::class,
            ['stream' => $file]
        ));
    }

    /**
     * @param Container $dependency
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    protected function modifyCliDispatcher(Container $dependency)
    {
//        $context = $dependency->get('aura/cli-kernel:context');
        $stdio = $dependency->get('aura/cli-kernel:stdio');
        $logger = $dependency->get('aura/project-kernel:logger');
        $dispatcher = $dependency->get('aura/cli-kernel:dispatcher');
        $dispatcher->setObject(
            'hello',
            function ($name = 'World') use ($stdio, $logger) {
                $stdio->outln("Hello {$name}!");
                $logger->debug("Said hello to '{$name}'");
            }
        );
    }

    /**
     * @param Container $dependency
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    public function modifyWebRouter(Container $dependency)
    {
        $this->init();
        $router = $dependency->get('aura/web-kernel:router');

        foreach ($this->config['routing'] as $name => $settings) {
            if ($settings['enabled'] === false) {
                continue;
            }

            $method = 'add' . ucfirst($settings['method']);
            $router->$method($name, $settings['route'])->setValues(['action' => $settings['action']]);
        }
    }

    /**
     * @param Container $dependency
     * @throws \Aura\Di\Exception\ServiceNotFound
     */
    public function modifyWebDispatcher(Container $dependency)
    {
        /** @var \Aura\View\View $view */
        $view = $dependency->get('view');
        /** @var \Aura\Dispatcher\Dispatcher $dispatcher */
        $dispatcher = $dependency->get('aura/web-kernel:dispatcher');
        /** @var \Aura\Web\Response $response */
        $response = $dependency->get('aura/web-kernel:response');
        /** @var \Aura\Web\Request $request */
        $request = $dependency->get('aura/web-kernel:request');

        $this->init();

        foreach ($this->config['routing'] as $settings) {
            if ($settings['enabled'] === false) {
                continue;
            }

            $dispatcher->setObject($settings['action'], function () use ($view, $response, $request, $settings) {
                $view->setView($settings['view']);
                $view->setLayout($settings['layout']);

                new $settings['object']($request, $response, $view);
            });
        }
    }

    /**
     * initialize configuration
     *
     * @return $this
     */
    protected function init()
    {
        if (empty($this->config)) {
            $this->config = SystemConfig::getConfig();
        }

        return $this;
    }
}
