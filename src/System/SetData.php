<?php

namespace System;

use Database\Connect;
use Config\Config;
use Database\Query;

class SetData
{
    /**
     * @var array
     * @todo move to config
     */
    const TEMP_PARSING = [
        'rpi-mc' => [
            'check' => 'temp=[\d]+\.?[\d]{1,2}?\'C',
            'parse' => '[\d]+\.?([\d]+)?',
        ],
        'osmc' => [
            'check' => 'temp=[\d]+\.?[\d]{1,2}?\'C',
            'parse' => '[\d]+\.?([\d]+)?',
        ],
        'master' => [
            'check' => 'temp1:[ ]+\+[\d]+\.?([\d]+)?°C',
            'parse' => '[\d]+\.?([\d]+)?',
        ],
    ];

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

        $secureToken = Config::getConfig()['secure_token'];
        $retrievedSecureToken = Config::urlParamsBypass('key');

        if ($secureToken !== $retrievedSecureToken) {
            $status = 'error';
            $message = 'Incorrect secure token.';
        } else {
            $post = $request->post;
            $host = $post->get('hostname', '');

            $query = (new Query)
                ->insert()
                ->into('system_log')
                ->cols([
                    'log_time' => $post->get('date', 'NOW()'),
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
                    'hostname' => $host,
                    'ip_internal' => $post->get('ip_internal', ''),
                    'ip_external' => $post->get('ip_external', ''),
                    'extra' => json_encode($post->get('extra', '')),
                    'disk_usage' => $post->get('disk_usage', ''),
                    'cpu_temp' => $this->parseCpuTemp(
                        $post->get('cpu_temp', ''),
                        $host
                    ),
                ]);
            (new Connect)->query($query);
        }

        $view->setData([
            'status' => $status,
            'message' => $message,
        ]);

        $response->content->set($view());
    }

    /**
     * @param string $temp
     * @param string $host
     * @return string
     */
    protected function parseCpuTemp($temp, $host)
    {
        $temperature = '';
        $matches = [];
        $count = 1;
        $host = trim($host);
        $expression = self::TEMP_PARSING[$host];
        $tempDetected = preg_match('#' . $expression['check'] . '#', $temp, $matches);

        if (!$tempDetected) {
            return 0;
        }

        foreach ($matches as $match) {
            $val = preg_grep('#' . $expression['parse'] . '#', $match);
            $temperature .= 't' . $count++ . ': ' . reset($val);
        }

        return $temperature;
    }
}
