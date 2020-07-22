<?php declare(strict_types=1);

namespace Cspray\Labrador\AsyncDbTest;

use Amp\PHPUnit\AsyncTestCase;
use Amp\Promise;
use Amp\Sql\Link;
use function Amp\call;

abstract class FixtureLoadingTestCase extends AsyncTestCase {

    /**
     * @var Link
     */
    protected static $connection;

    public function setUpAsync() : Promise {
        return call(function() {
            if (!isset(self::$connection)) {
                self::$connection = yield $this->getConnection();
            }
            yield self::$connection->query('START TRANSACTION');
            yield $this->getFixture()->load(self::$connection);
        });
    }

    public function tearDownAsync() : Promise {
        return call(function() {
            yield self::$connection->query('ROLLBACK');
        });
    }

    abstract protected function getConnection() : Promise;

    abstract protected function getFixture() : Fixture;

}