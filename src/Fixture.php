<?php declare(strict_types=1);

namespace Cspray\Labrador\AsyncDbTest;

use Amp\Promise;
use Amp\Sql\Executor;

interface Fixture {

    public function load(Executor $executor) : Promise;

}