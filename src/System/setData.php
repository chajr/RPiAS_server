<?php

namespace System;

use Database\Connect;
use Config\Config;

class setData
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
        $message = 'Data written successfully.';

        $secureToken = (new Config)->getConfig()['secure_token'];
        $retrievedSecureToken = $request->query->get('secure', '');
        
        if ($secureToken !== $retrievedSecureToken) {
            $status = 'error';
            $message = 'Incorrect secure token';
        } else {
            echo ($request->post->get('key2', 'default'));
            //        $q = (new\Database\Query)->select()->cols(['*'])->from('system_log');
            //        var_dump((new Connect)->query($q));
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view->__invoke());
    }
}
