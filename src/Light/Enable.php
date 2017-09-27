<?php

namespace Light;

use Log\Log;
use Config\Config;
use Command\Manage;

class Enable
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
            $manager->setCommand($this->createValueObject('redis-cli set rpia_illuminate_force true'));
            $manager->setCommand($this->createValueObject('redis-cli set rpia_illuminate_status true'));
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view());
    }

    /**
     * @param string $command
     * @return \Aura\Web\Request\Values
     */
    protected function createValueObject($command)
    {
        return new \Aura\Web\Request\Values([
            'host' => 'rpi-mc',
            'to_be_exec' => '0000-00-00 00:00:00',
            'command' => $command,
        ]);
    }
}
