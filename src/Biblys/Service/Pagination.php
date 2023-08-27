<?php

namespace Biblys\Service;

use InvalidArgumentException;

class Pagination
{
    private int $currentPageIndex;
    private int $currentPageNumber;
    private int $itemCount;
    private int $limit;
    private int $totalPages;
    private int $offset;
    private array $queryParams = [];

    public function __construct($currentPageIndex, $itemCount, $limit = 10)
    {
        if ($currentPageIndex < 0) {
            throw new InvalidArgumentException("Page number cannot be less than 0");
        }

        $this->currentPageIndex = $currentPageIndex;
        $this->currentPageNumber = $currentPageIndex + 1;
        $this->itemCount = $itemCount;
        $this->limit = $limit;
        $this->totalPages = (int) ceil($this->itemCount / $this->limit);
        $this->offset = $currentPageIndex * $this->limit;
    }

    public function getCurrent(): int
    {
        return $this->currentPageNumber;
    }

    public function getTotal(): int
    {
        return $this->totalPages;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getPrevious(): bool|int
    {
        if ($this->currentPageIndex < 1) {
            return false;
        }

        return $this->currentPageIndex - 1;
    }

    public function getNext(): bool|int
    {
        if ($this->currentPageIndex >= $this->totalPages - 1) {
            return false;
        }

        return $this->currentPageIndex + 1;
    }

    public function setQueryParams(array $_queryParams): void
    {
        $this->queryParams = $_queryParams;
    }

    private function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getQueryForPageNumber(int $pageNumber): ?string
    {
        $pageIndex = $pageNumber - 1;
        return $this->getQueryForPageIndex($pageIndex);
    }

    public function getQueryForPageIndex(?int $pageIndex = null): ?string
    {
        $params = $this->getQueryParams();

        if ($pageIndex !== null) {
            $params['p'] = $pageIndex;
        }

        if (count($params) === 0) {
            return null;
        }

        return "?".http_build_query($params);
    }

    public function getPreviousQuery(): ?string
    {
        $previousPageNumber = $this->getPrevious();
        return $this->getQueryForPageIndex($previousPageNumber);
    }

    public function getNextQuery(): ?string
    {
        $nextPageNumber = $this->getNext();
        return $this->getQueryForPageIndex($nextPageNumber);
    }
}
