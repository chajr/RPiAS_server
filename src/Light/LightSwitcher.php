<?php

namespace Light;

use Config\Config;

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
    public function process($message, Callback $setRedisCommands)
    {
        $status = 'success';

        $secureToken = Config::getConfig()['secure_token'];
        $retrievedSecureToken = $this->request->query->get('key', '');

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
