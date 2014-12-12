<?php
include __DIR__ . '/../../src/Xshifty/AsyncClosure.php';

use Xshifty\AsyncClosure;

class AsyncClosureTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testCreateReturnIsAClosure()
    {
        $closure = AsyncClosure::create(function () {
        });

        $this->assertEquals('Closure', get_class($closure));
    }
}
