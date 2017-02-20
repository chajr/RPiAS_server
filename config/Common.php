<?php
namespace Aura\Framework_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;
use Config\Config as SystemConfig;

class Common extends Config
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param Container $di
     * @return void
     */
    public function define(Container $di)
    {
        $di->set('aura/project-kernel:logger', $di->lazyNew('Monolog\Logger'));

        $di->params['Aura\View\TemplateRegistry']['paths'] = [
            dirname(__DIR__) . '/templates/views',
            dirname(__DIR__) . '/templates/layouts',
        ];
        $di->set('view', $di->lazyNew('Aura\View\View'));
    }

    /**
     * @param Container $di
     * @return void
     */
    public function modify(Container $di)
    {
        $this->modifyLogger($di);
        $this->modifyCliDispatcher($di);
        $this->modifyWebRouter($di);
        $this->modifyWebDispatcher($di);
    }

    /**
     * @param Container $di
     */
    protected function modifyLogger(Container $di)
    {
        $project = $di->get('project');
        $mode = $project->getMode();
        $file = $project->getPath("tmp/log/{$mode}.log");

        $logger = $di->get('aura/project-kernel:logger');
        $logger->pushHandler($di->newInstance(
            'Monolog\Handler\StreamHandler',
            ['stream' => $file]
        ));
    }

    /**
     * @param Container $di
     */
    protected function modifyCliDispatcher(Container $di)
    {
        $context = $di->get('aura/cli-kernel:context');
        $stdio = $di->get('aura/cli-kernel:stdio');
        $logger = $di->get('aura/project-kernel:logger');
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
        $dispatcher->setObject(
            'hello',
            function ($name = 'World') use ($context, $stdio, $logger) {
                $stdio->outln("Hello {$name}!");
                $logger->debug("Said hello to '{$name}'");
            }
        );
    }

    /**
     * @param Container $di
     */
    public function modifyWebRouter(Container $di)
    {
        $this->init();
        $router = $di->get('aura/web-kernel:router');

        foreach ($this->config['routing'] as $name => $settings) {
            if ($settings['enabled'] === false) {
                continue;
            }

            $method = 'add' . ucfirst($settings['method']);
            $router->$method($name, $settings['route'])->setValues(['action' => $settings['action']]);
        }
    }

    /**
     * @param Container $di
     */
    public function modifyWebDispatcher(Container $di)
    {
        /** @var \Aura\View\View $view */
        $view = $di->get('view');
        /** @var \Aura\Dispatcher\Dispatcher $dispatcher */
        $dispatcher = $di->get('aura/web-kernel:dispatcher');
        /** @var \Aura\Web\Response $response */
        $response = $di->get('aura/web-kernel:response');
        /** @var \Aura\Web\Request $request */
        $request = $di->get('aura/web-kernel:request');

        $this->init();

        foreach ($this->config['routing'] as $settings) {
            if ($settings['enabled'] === false) {
                continue;
            }

            $dispatcher->setObject($settings['action'], function () use ($view, $response, $request, $settings) {
                $view->setView($settings['view']);
                $view->setLayout($settings['layout']);

                (new $settings['object']($request, $response, $view));
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
            $this->config = (new SystemConfig)->getConfig();
        }

        return $this;
    }
}
