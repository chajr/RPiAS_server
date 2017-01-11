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
            $this->markAsConsumed($request->post);
            $this->setOutput($request->post);
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view->__invoke());
    }

    /**
     * @param \Aura\Web\Request\Values $post
     */
    protected function markAsConsumed($post)
    {
        if ($post->get('data_update', false)) {
            return;
        }

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

    /**
     * @param \Aura\Web\Request\Values $post
     */
    protected function setOutput($post)
    {
        if ($post->get('data_update', false)) {
            $currentTime = date('Y-m-d H:i:s');
            $commandId = $post->get('command_id', null);

            $executed = $post->get('executed', 0);
            $output = $post->get('output', '');
            $error = $post->get('error', 0);
            $executionTime = $post->get('exec_time', $currentTime);

            if ($commandId) {
                $query = (new Query)
                    ->update()
                    ->table('commands')
                    ->cols([
                        'executed' => $executed,
                        'response' => $output,
                        'error' => $error,
                        'exec_time' => $executionTime,
                        'response_date_time' => $currentTime,
                    ])
                    ->where('command_id = ?', $commandId);

                (new Connect)->query($query);
            }
        }
    }
}
