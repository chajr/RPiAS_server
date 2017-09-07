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
    protected static $connection = null;

    /**
     * create connection
     */
    public function __construct()
    {
        if (is_null(self::$connection)) {
            try {
                $dbConfig = Config::getConfig()['database'];

                self::$connection = new ExtendedPdo(
                    "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};port={$dbConfig['port']}",
                    $dbConfig['user'],
                    $dbConfig['pass']
                );

                self::$connection->connect();
            } catch (\Exception $e) {
                Log::addError($e->getMessage(), 'db connection');
            }
        }
    }

    /**
     * execute query
     *
     * @param \Aura\SqlQuery\AbstractQuery|\Aura\SqlQuery\Common\SelectInterface|\Aura\SqlQuery\Common\ValuesInterface $queryObject
     * @return array|\PDOStatement
     * @throws \Exception
     */
    public function query($queryObject)
    {
        $query = $queryObject->__toString();
        $sth = self::$connection->prepare($query);

        if (!$sth) {
            $err = self::$connection->errorInfo();
            throw new \Exception('DB Error: ' . $err[0] . ' - ' . $err[2]);
        }

        $values = $queryObject->getBindValues();

        foreach ($values as $key => $val) {
            $sth->bindParam(':' . $key, $val);
            unset($val);
        }

        $res = $sth->execute();

        if (!$res) {
            $err = $sth->errorInfo();
            throw new \Exception('DB Error: ' . $err[0] . ' - ' . $err[2]);
        }

        return $sth;
    }

    /**
     * destroy connection
     *
     * @throws \Aura\Sql\Exception\CannotDisconnect
     */
    public function __destruct()
    {
        self::$connection->disconnect();
    }
}
