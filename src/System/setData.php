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
        $retrievedSecureToken = $request->query->get('key', '');

        if ($secureToken !== $retrievedSecureToken) {
            $status = 'error';
            $message = 'Incorrect secure token';
        } else {
            $post = $request->post;

            $query = (new\Database\Query)
                ->insert()
                ->into('system_log')
                ->cols([
                    'date' => $post->get('date', 'NOW()'),
                    'cpu_utilization' => $post->get('cpu_utilization', 0),
                    'memory_free' => $post->get('memory_free', 0),
                    'memory_used' => $post->get('memory_used', 0),
                    'uptime_p' => $post->get('uptime_p', ''),
                    'uptime_s' => $post->get('uptime_s', ''),
                    'system_load' => $post->get('system_load', ''),
                    'process_number' => $post->get('process_number', 0),
                    'disk_utilization' => $post->get('disk_utilization', ''),
                    'network_utilization' => $post->get('network_utilization', ''),
                    'logged_in_users' => $post->get('logged_in_users', ''),
                    'logged_in_users_count' => $post->get('logged_in_users_count', 0),
                    'users_work' => $post->get('users_work', ''),
                    'hostname' => $post->get('hostname', ''),
                    'ip_internal' => $post->get('ip_internal', ''),
                    'ip_external' => $post->get('ip_external', ''),
                    'extra' => $post->get('extra', ''),
                ]);
            (new Connect)->query($query);
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view->__invoke());
    }
}
