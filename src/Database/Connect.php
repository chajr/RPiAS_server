<?php

namespace Database;

use Config\Config;
use Aura\Sql\ExtendedPdo;
use Log\Log;

class Connect
{
    /**
     * store DB connection
     *
     * @var ExtendedPdo|null
     */
    protected static $_connection = null;

    /**
     * create connection
     */
    public function __construct()
    {
        if (is_null(self::$_connection)) {
            try {
                $dbConfig = Config::getConfig()['database'];
    
                self::$_connection = new ExtendedPdo(
                    "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};port={$dbConfig['port']}",
                    $dbConfig['user'],
                    $dbConfig['pass']
                );
    
                self::$_connection->connect();
            } catch (\Exception $e) {
                Log::addError($e->getMessage(), 'db connection');
            }
        }
    }

    /**
     * execute query
     *
     * @param string $query
     * @return array
     * @throws \Exception
     */
    public function query($query)
    {
        $sth = self::$_connection->prepare($query);

        if (!$sth) {
            $err = self::$_connection->errorInfo();
            throw new \Exception('DB Error: ' . $err[0] . ' - ' . $err[2]);
        }

        $res = $sth->execute();

        if (!$res) {
            $err = $sth->errorInfo();
            throw new \Exception('DB Error: ' . $err[0] . ' - ' . $err[2]);
        }

        return $sth->fetchAll();
    }

    /**
     * destroy connection
     *
     * @throws \Aura\Sql\Exception\CannotDisconnect
     */
    public function __destruct()
    {
        self::$_connection->disconnect();
    }
}