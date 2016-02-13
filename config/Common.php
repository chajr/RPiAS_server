<?php
namespace Aura\Framework_Project\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->set('aura/project-kernel:logger', $di->lazyNew('Monolog\Logger'));

        $di->params['Aura\View\TemplateRegistry']['paths'] = array(
            dirname(__DIR__) . '/templates/views',
            dirname(__DIR__) . '/templates/layouts',
        );
        $di->set('view', $di->lazyNew('Aura\View\View'));
    }

    public function modify(Container $di)
    {
        $this->modifyLogger($di);
        $this->modifyCliDispatcher($di);
        $this->modifyWebRouter($di);
        $this->modifyWebDispatcher($di);
    }

    protected function modifyLogger(Container $di)
    {
        $project = $di->get('project');
        $mode = $project->getMode();
        $file = $project->getPath("tmp/log/{$mode}.log");

        $logger = $di->get('aura/project-kernel:logger');
        $logger->pushHandler($di->newInstance(
            'Monolog\Handler\StreamHandler',
            array(
                'stream' => $file,
            )
        ));
    }

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

    public function modifyWebRouter(Container $di)
    {
        $router = $di->get('aura/web-kernel:router');

        $router->add('hello', '/')
               ->setValues(array('action' => 'hello'));
    }

    public function modifyWebDispatcher($di)
    {
        $view = $di->get('view');
        $dispatcher = $di->get('aura/web-kernel:dispatcher');
        $response = $di->get('aura/web-kernel:response');
        $request = $di->get('aura/web-kernel:request');
        $dispatcher->setObject('hello', function () use ($view, $response, $request) {
            $view->setView('api_response');
            $view->setLayout('index');
            $response->content->set($view->__invoke());
        });
    }
}
