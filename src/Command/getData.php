<?php

namespace Command;

use Database\Connect;
use Config\Config;

class getData
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
        $message = '';

        $secureToken = (new Config)->getConfig()['secure_token'];
        $retrievedSecureToken = $request->query->get('key', '');
        $host = $request->query->get('host', '');

        if ($secureToken !== $retrievedSecureToken) {
            $status = 'error';
            $message = 'Incorrect secure token.';
        } else {
            $post = $request->post;


        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view->__invoke());
    }

    protected function _getCommands($host)
    {
        $query = (new\Database\Query)
            ->select()
            ->from('commands')
            ->cols([
                'command_id',
                'command',
                'to_be_exec',
            ])
            ->where('executed = 0')
            ->where('consumed = 0')
            ->where("host = '$host'");

        return (new Connect)->query($query);
    }
}
