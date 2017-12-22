<?php

namespace AppBundle\Utils;

use Symfony\Component\HttpFoundation\Request;

class Pagination
{
    /** @var int */
    private $page;
    /** @var int */
    private $pageLimit;
    /** @var int */
    private $totalItemsCount;
    /** @var string */
    private $pathName;

    public function __construct(Request $request, int $totalItemsCount, int $pageLimit)
    {
        $this->totalItemsCount = $totalItemsCount;
        $this->pageLimit = $pageLimit;
        $this->page = $request->query->getInt('page', 1);
        $this->pathName = $request->get('_route');
        dump($this->pathName);
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

    public function isFirstPage(): bool
    {
        return $this->page === 1;
    }

    public function isLastPage(): bool
    {
        return $this->page === $this->getTotalPages();
    }

    public function getPathName(): string
    {
        return $this->pathName;
    }
}