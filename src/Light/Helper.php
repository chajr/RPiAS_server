<?php

namespace Light;

class Helper
{
    /**
     * @param string $command
     * @return \Aura\Web\Request\Values
     * @todo host in config
     */
    public static function createValueObject($command)
    {
        return new \Aura\Web\Request\Values([
            'host' => 'osmc',
            'to_be_exec' => '0000-00-00 00:00:00',
            'command' => $command,
        ]);
    }
}