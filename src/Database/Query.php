<?php

namespace Database;

use Aura\SqlQuery\QueryFactory;

class Query
{
    /**
     * @var Connect
     */
    protected $connection;

    /**
     * @var QueryFactory
     */
    protected $factory;

    /**
     * Create sql query factory
     */
    public function __construct()
    {
        $this->factory = new QueryFactory('mysql');
    }

    /**
     * return select object
     *
     * @return \Aura\SqlQuery\Common\SelectInterface
     */
    public function select()
    {
        return $this->factory->newSelect();
    }

    /**
     * return insert object
     *
     * @return \Aura\SqlQuery\Common\InsertInterface
     */
    public function insert()
    {
        return  $this->factory->newInsert();
    }

    /**
     * return update object
     *
     * @return \Aura\SqlQuery\Common\UpdateInterface
     */
    public function update()
    {
        return $this->factory->newUpdate();
    }
}
