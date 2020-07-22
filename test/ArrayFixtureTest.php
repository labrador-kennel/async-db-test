<?php declare(strict_types=1);

namespace Cspray\Labrador\AsyncDbTest;

use Amp\Postgres\ConnectionConfig;
use Amp\Promise;
use function Amp\call;
use function Amp\Postgres\pool;

class ArrayFixtureTest extends FixtureLoadingTestCase {

    public function testRecordsLoaded() {
        return call(function() {
            $result = yield self::$connection->query('SELECT COUNT(*) AS "count" FROM foo');
            yield $result->advance();
            $count = $result->getCurrent()['count'];

            $this->assertSame(3, $count);
        });
    }

    protected function getConnection() : Promise {
        $config = new ConnectionConfig('localhost', 5432, 'postgres', null, 'async_db_test');
        return pool($config)->extractConnection();
    }

    protected function getFixture() : Fixture {
        $fixture = new ArrayFixture('foo');
        $fixture->addRecord([
            'bar' => 'foo-bar-1',
            'baz' => 'foo-baz-1',
            'qux' => 'foo-qux-1'
        ])->addRecord([
            'bar' => 'foo-bar-2',
            'baz' => 'foo-baz-2',
            'qux' => 'foo-qux-2'
        ])->addRecord([
            'bar' => 'foo-bar-3',
            'baz' => 'foo-baz-3',
            'qux' => 'foo-qux-3'
        ]);

        return $fixture;
    }
}