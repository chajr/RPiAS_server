<?php

namespace Command;

use Database\Connect;
use Database\Query;
use \Aura\Web\Request\Values;

class Manage
{
    /**
     * @param \Aura\Web\Request\Values $post
     */
    public function setCommand(Values $post)
    {
        $query = (new Query)
            ->insert()
            ->table('commands')
            ->cols([
                'consumed' => 0,
                'command' => $post->get('command', null),
                'to_be_exec' => $post->get('to_be_exec', '0000-00-00 00:00:00'),
                'host' => $post->get('host', null),
            ]);

        (new Connect)->query($query);
    }

    /**
     * @param \Aura\Web\Request\Values $post
     */
    public function markAsConsumed(Values $post)
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
    public function setOutput(Values $post)
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

    /**
     * get all don't executed commands from database
     *
     * @param string $host
     * @return array
     * @throws \Exception
     */
    public function getCommands($host)
    {
        $query = (new \Database\Query)
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
