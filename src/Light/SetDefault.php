<?php

namespace Light;

use Log\Log;
use Config\Config;
use Command\Manage;

class SetDefault
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

        $secureToken = (new Config)->getConfig()['secure_token'];
        $retrievedSecureToken = $request->query->get('key', '');

        if ($secureToken !== $retrievedSecureToken) {
            $status  = 'error';
            $message = 'Incorrect secure token';
        } else {
            $request->post->host = 'rpi-mc';

            $request->post->command = 'redis-cli set rpia_illuminate_force null';
            $manager->setCommand($request->post);
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view());
    }
}
