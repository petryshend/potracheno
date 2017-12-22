<?php

namespace Tests\AppBundle\Utils;

use AppBundle\Utils\Pagination;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    public function testGetValidPage()
    {
        $pagination = new Pagination(120, 10, 3);
        $this->assertEquals(3, $pagination->getPage());
        $pagination = new Pagination(120, 10, 0);
        $this->assertEquals(1, $pagination->getPage());
        $pagination = new Pagination(120, 10, 13);
        $this->assertEquals(12, $pagination->getPage());
    }

    public function testGetValidOffset()
    {
        $pagination = new Pagination(120, 10, 3);
        $this->assertEquals(20, $pagination->getOffset());
        $pagination = new Pagination(120, 10, 15);
        $this->assertEquals(110, $pagination->getOffset());
        $pagination = new Pagination(120, 10, -2);
        $this->assertEquals(0, $pagination->getOffset());
    }
}