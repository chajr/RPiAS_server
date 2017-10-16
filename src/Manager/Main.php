<?php

namespace Manager;

use Log\Log;
use Config\Config;

class Main
{
    /**
     * @param \Aura\Web\Request $request
     * @param \Aura\Web\Response $response
     * @param \Aura\View\View $view
     */
    public function __construct($request, $response, $view)
    {
        $message = 'Main Page';

        $secureToken = Config::getConfig()['secure_token'];
        $retrievedSecureToken = $request->query->get('key', '');

        if ($secureToken !== $retrievedSecureToken) {
            $message = 'Incorrect secure token';
        } else {
            $this->mainPage($request, $response, $view);
        }

        $view->setData([
            'message' => $message,
        ]);

        $response->content->set($view());
    }

    protected function mainPage($request, $response, $view)
    {
        
    }
}
