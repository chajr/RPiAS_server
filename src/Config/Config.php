<?php

namespace Config;

class Config
{
    /**
     * @var array|null
     */
    protected static $loadedConfig = null;

    /**
     * load configuration
     *
     * @param string|bool $path
     */
    public static function load($path = false)
    {
        $configPath = '../etc/config.json';

        if ($path) {
            $configPath = $path;
        }

        $config = file_get_contents($configPath);

        self::$loadedConfig = json_decode($config, true);
    }

    /**
     * get configuration
     *
     * @return array|null
     */
    public static function getConfig()
    {
        if (is_null(self::$loadedConfig)) {
            self::load();
        }

        return self::$loadedConfig;
    }
}
