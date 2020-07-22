<?php declare(strict_types=1);


namespace Cspray\Labrador\AsyncDbTest;

use Amp\Promise;
use Amp\Sql\Link;
use Aura\SqlQuery\QueryFactory;
use function Amp\call;

class ArrayFixture implements Fixture {

    private $table;

    private $records = [];

    private $queryFactory;

    public function __construct(string $table, QueryFactory $queryFactory = null) {
        $this->table = $table;
        $this->queryFactory = $queryFactory ?? new QueryFactory('pgsql');
    }

    public function addRecord(array $record) : self {
        $this->records[] = $record;
        return $this;
    }

    public function load(Link $link) : Promise {
        return call(function() use($link) {
            $insert = $this->queryFactory->newInsert();
            $insert->into($this->table);
            foreach ($this->records as $record) {
                $insert->addRow($record);
            }

            $statement = yield $link->prepare($insert->getStatement());
            yield $statement->execute($insert->getBindValues());
        });
    }
}