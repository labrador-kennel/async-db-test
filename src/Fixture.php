<?php declare(strict_types=1);

namespace Cspray\Labrador\AsyncDbTest;

use Amp\Promise;
use Amp\Sql\Link;

interface Fixture {

    public function load(Link $link) : Promise;

}