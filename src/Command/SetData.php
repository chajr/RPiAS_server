<?php

namespace Command;

use Config\Config;

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
            $manager->markAsConsumed($request->post);
            $manager->setOutput($request->post);
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view());
    }
}
