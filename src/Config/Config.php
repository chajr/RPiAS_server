<?php

namespace Config;

use Aura\Web\WebFactory;

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

    /**
     * @param $param
     * @return array|mixed|null
     * @throws \Aura\Web\Exception\InvalidComponent
     */
    public static function urlParamsBypass($param = null)
    {
        $webFactory = new WebFactory($GLOBALS);
        $request = $webFactory->newRequest();
        \parse_str($request->url->get(6), $params);

        if (!$param) {
            return $params;
        }

        if (isset($params[$param])) {
            return $params[$param];
        }

        return null;
    }
}
