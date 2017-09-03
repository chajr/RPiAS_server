<?php

namespace Command;

use Config\Config;

class GetData
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

        $secureToken = (new Config)->getConfig()['secure_token'];
        $retrievedSecureToken = $request->query->get('key', '');
        $host = $request->query->get('host', '');

        if ($secureToken !== $retrievedSecureToken) {
            $status = 'error';
            $message = 'Incorrect secure token.';
        } else {
            try {
                $message = json_encode((new Manage)->getCommands($host));
            } catch (\Exception $e) {
                $status = 'error';
                $message = $e->getMessage();
            }
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view());
    }
}
