<?php

use PHPUnit\Framework\TestCase;
use Vosiz\VaTools\Filter\StringFilter;
use Vosiz\VaTools\Filter\FilterException;

class FilterTest extends TestCase {

    /** @test */
    public function UnknownCommandIsFilterException() {

        $filter = new StringFilter(['one', 'two']);

        $this->expectException(FilterException::class);
        $filter->Filter(['nonsense' => 'x']);
    }

    /** @test */
    public function NonStringDataAreRefused() {

        $this->expectException(FilterException::class);
        new StringFilter([1, 2.5, []]);
    }
}
