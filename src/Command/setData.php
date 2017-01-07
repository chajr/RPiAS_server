<?php

namespace Command;

use Database\Connect;
use Config\Config;
use Database\Query;

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
        $message = 'Data updated successfully.';

        $secureToken = (new Config)->getConfig()['secure_token'];
        $retrievedSecureToken = $request->query->get('key', '');

        if ($secureToken !== $retrievedSecureToken) {
            $status = 'error';
            $message = 'Incorrect secure token.';
        } else {
            $post = $request->post;
            $commandId = $post->get('command_id', null);
            $consumedDate = $post->get('command_consumed_date_time', '0000-00-00 00:00:00');
            $mongoId = $post->get('mongo_id', null);

            if ($commandId) {
                $query = (new Query)
                    ->update()
                    ->table('commands')
                    ->cols([
                        'consumed' => 1,
                        'command_consumed_date_time' => $consumedDate,
                        'mongo_id' => $mongoId,
                    ])
                    ->where('command_id = ?', $commandId);

                (new Connect)->query($query);
            }
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view->__invoke());
    }
}
