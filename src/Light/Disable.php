<?php

namespace Light;

use Log\Log;
use Config\Config;
use Command\Manage;

class Disable
{
    /**
     * setData constructor.
     *
     * @param \Aura\Web\Request $request
     * @param \Aura\Web\Response $response
     * @param \Aura\View\View $view
     */
    public function __construct($request, $response, $view)
    {
        $status = 'success';
        $message = 'Light turned on.';
        $manager = new Manage;

        $secureToken = Config::getConfig()['secure_token'];
        $retrievedSecureToken = $request->query->get('key', '');

        if ($secureToken !== $retrievedSecureToken) {
            $status  = 'error';
            $message = 'Incorrect secure token';
        } else {
            $manager->setCommand(Helper::createValueObject('redis-cli set rpia_illuminate_force false'));
            $manager->setCommand(Helper::createValueObject('redis-cli set rpia_illuminate_status false'));
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view());
    }
}
