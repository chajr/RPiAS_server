<?php

namespace Database;

use Aura\SqlQuery\QueryFactory;

class Query
{
    /**
     * @var Connect
     */
    protected $_connection;

    /**
     * @var QueryFactory
     */
    protected $_factory;

    /**
     * Create sql query factory
     */
    public function __construct()
    {
        $this->_connection = new Connect;
        $this->_factory = new QueryFactory('mysql');
    }

    /**
     * return select object
     *
     * @return \Aura\SqlQuery\Common\SelectInterface
     */
    public function select()
    {
        return $this->_factory->newSelect();
    }

    /**
     * return insert object
     *
     * @return \Aura\SqlQuery\Common\InsertInterface
     */
    public function insert()
    {
        return  $this->_factory->newInsert();
    }

    /**
     * return update object
     *
     * @return \Aura\SqlQuery\Common\UpdateInterface
     */
    public function update()
    {
        return $this->_factory->newUpdate();
    }
}
