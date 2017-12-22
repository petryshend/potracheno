<?php

namespace AppBundle\Utils;

class Pagination
{
    /** @var int */
    private $page;
    /** @var int */
    private $pageLimit;
    /** @var int */
    private $totalItemsCount;

    public function __construct(int $totalItemsCount, int $pageLimit, int $currentPage)
    {
        $this->totalItemsCount = $totalItemsCount;
        $this->pageLimit = $pageLimit;
        $this->page = $currentPage;
        if ($this->page < 1) {
            $this->page = 1;
        } else if ($this->page > $this->getTotalPages()) {
            $this->page = $this->getTotalPages();
        }
    }

    public function getOffset(): int
    {
        return $this->pageLimit * ($this->page - 1);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getTotalPages(): int
    {
        return ceil($this->totalItemsCount / $this->pageLimit);
    }

    public function getPageLimit(): int
    {
        return $this->pageLimit;
    }
}