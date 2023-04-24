<?php

namespace Light;

use Config\Config;
use Aura\Web\WebFactory;

class LightSwitcher
{
    /**
     * @var \Aura\Web\Request
     */
    protected $request;

    /**
     * @var \Aura\Web\Response
     */
    protected $response;

    /**
     * @var \Aura\View\View
     */
    protected $view;

    /**
     * @param string $message
     * @param Callback $setRedisCommands
     */
    public function process($message, \Closure $setRedisCommands)
    {
        $status = 'success';

        $secureToken = Config::getConfig()['secure_token'];
        $retrievedSecureToken = Config::urlParamsBypass('key');

        if ($secureToken !== $retrievedSecureToken) {
            $status  = 'error';
            $message = 'Incorrect secure token.';
        } else {
            $setRedisCommands();
        }

        $this->view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $view = $this->view;

        $this->response->content->set($view());
    }
}
