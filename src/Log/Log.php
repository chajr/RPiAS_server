<?php

namespace Log;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class Log
{
    const MAIN_CHANNEL = 'main';

    /**
     * store all created channels
     *
     * @var array
     */
    protected static $channels = [];

    /**
     * @param string $message
     * @param null|string $channel
     * @return bool
     */
    public static function addError($message, $channel = null)
    {
        return self::getInstance(Logger::ERROR, $channel)->addError($message);
    }

    /**
     * @param string $message
     * @param null|string $channel
     * @return bool
     */
    public static function addWarning($message, $channel = null)
    {
        return self::getInstance(Logger::WARNING, $channel)->addWarning($message);
    }

    /**
     * @param string $message
     * @param null|string $channel
     * @return bool
     */
    public static function addNotice($message, $channel = null)
    {
        return self::getInstance(Logger::NOTICE, $channel)->addNotice($message);
    }

    /**
     * @param string $message
     * @param null|string $channel
     * @return bool
     */
    public static function addInfo($message, $channel = null)
    {
        return self::getInstance(Logger::INFO, $channel)->addInfo($message);
    }

    /**
     * @param string $message
     * @param null|string $channel
     * @return bool
     */
    public static function addDebug($message, $channel = null)
    {
        return self::getInstance(Logger::DEBUG, $channel)->addDebug($message);
    }

    /**
     * @param string $message
     * @param null|string $channel
     * @return bool
     */
    public static function addCritical($message, $channel = null)
    {
        return self::getInstance(Logger::CRITICAL, $channel)->addCritical($message);
    }

    /**
     * @param string $type
     * @param null|string $channel_name
     * @return Logger
     * @throws \Exception
     */
    public static function getInstance($type, $channel_name = null)
    {
        if (!$channel_name) {
            $channel_name = self::MAIN_CHANNEL;
        }

        if (!array_key_exists($channel_name, self::$channels)) {
            self::$channels[$channel_name] = new Logger($channel_name);
        }

        /** @var Logger $log */
        $log = self::$channels[$channel_name];
        $name = Logger::getLevelName($type);

        $handler = new StreamHandler(
            '../tmp/log/' . strtolower($name) . '.log',
            $type
        );

        $handler->setFormatter(new LineFormatter(null, null, true));
        $log->pushHandler($handler);

        return $log;
    }
}
