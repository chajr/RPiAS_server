<?php

namespace Command;

use Config\Config;
use Aura\Web\Request\Values;

class SetData
{
    /**
     * setData constructor.
     *
     * @param \Aura\Web\Request $request
     * @param \Aura\Web\Response $response
     * @param \Aura\View\View $view
     * @throws \Exception
     */
    public function __construct($request, $response, $view)
    {
        $status = 'success';
        $message = 'Data updated successfully.';
        $manager = new Manage;

        $secureToken = Config::getConfig()['secure_token'];
        $retrievedSecureToken = Config::urlParamsBypass('key');

        if ($secureToken !== $retrievedSecureToken) {
            $status = 'error';
            $message = 'Incorrect secure token.';
        } else {
            $valObject = new Values(Config::urlParamsBypassPost());
            $manager->markAsConsumed($valObject);
            $manager->setOutput($valObject);
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view());
    }
}
