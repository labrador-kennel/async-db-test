<?php declare(strict_types=1);

namespace Cspray\Labrador\AsyncDbTest;

use Amp\PHPUnit\AsyncTestCase;
use Amp\Promise;
use Amp\Sql\Link;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;
use function Amp\call;

abstract class FixtureLoadingTestCase extends AsyncTestCase {

    /**
     * @var Link
     */
    protected $connection;

    /**
     * @var QueryFactory
     */
    protected $queryFactory;

    public function setUp() : void {
        parent::setUp();
        $this->queryFactory = new QueryFactory('pgsql');
    }

    public function setUpAsync() : Promise {
        return call(function() {
            if (!isset($this->connection)) {
                $this->connection = yield $this->getConnection();
            }
            yield $this->connection->query('START TRANSACTION');
            yield $this->getFixture()->load($this->connection);
        });
    }

    public function tearDownAsync() : Promise {
        return call(function() {
            yield $this->connection->query('ROLLBACK');
        });
    }

    protected function assertTableCount(int $expected, string $table) : Promise {
        return call(function() use($expected, $table) {
            $this->addToAssertionCount(1);

            $query = $this->select()->cols(['COUNT(*) as count'])->from($table);
            $result = yield $this->connection->query($query->getStatement());
            yield $result->advance();

            $actual = $result->getCurrent()['count'];
            if ($expected !== $actual) {
                $this->fail(sprintf('Expected %s table to have %d rows but had %d rows.', $table, $expected, $actual));
            }
        });
    }

    protected function select() : SelectInterface {
        return $this->queryFactory->newSelect();
    }

    abstract protected function getConnection() : Promise;

    abstract protected function getFixture() : Fixture;

}