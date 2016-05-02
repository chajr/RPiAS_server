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

        $secureToken = (new Config)->getConfig()['secure_token'];
        $retrievedSecureToken = $request->query->get('key', '');
        $host = $request->query->get('host', '');

        if ($secureToken !== $retrievedSecureToken) {
            $status = 'error';
            $message = 'Incorrect secure token.';
        } else {
            try {
                $message = json_encode($this->getCommands($host));
            } catch (\Exception $e) {
                $status = 'error';
                $message = $e->getMessage();
            }
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view->__invoke());
    }

    /**
     * get all don't executed commands from database
     *
     * @param string $host
     * @return array
     * @throws \Exception
     */
    protected function getCommands($host)
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

        return (new Connect)->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
