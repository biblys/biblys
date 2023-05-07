<?php

namespace Biblys\Service;

use InvalidArgumentException;

class Pagination
{
    private $_currentPageIndex,
        $_currentPageNumber,
        $_itemCount,
        $_limit,
        $_totalPages,
        $_offset;

    /**
     * @var array
     */
    private $_queryParams = [];

    public function __construct($currentPageIndex, $itemCount, $limit = null)
    {
        global $_SITE;

        if ($currentPageIndex < 0) {
            throw new InvalidArgumentException("Page number cannot be less than 0");
        }

        $this->_currentPageIndex = $currentPageIndex;
        $this->_currentPageNumber = $currentPageIndex + 1;
        $this->_itemCount = $itemCount;

        $this->_limit = $_SITE->getOpt('articles_per_page');
        if (!$this->_limit) {
            $this->_limit = 10;
        }
        if ($limit) {
            $this->_limit = $limit;
        }

        $this->_totalPages = ceil($this->_itemCount / $this->_limit);
        $this->_offset = $currentPageIndex * $this->_limit;
    }

    public function getCurrent(): int
    {
        return $this->_currentPageNumber;
    }

    public function getTotal()
    {
        return $this->_totalPages;
    }

    public function getOffset()
    {
        return $this->_offset;
    }

    public function getLimit()
    {
        return $this->_limit;
    }

    public function getPrevious()
    {
        if ($this->_currentPageIndex < 1) {
            return false;
        }

        return $this->_currentPageIndex - 1;
    }

    public function getNext()
    {
        if ($this->_currentPageIndex >= $this->_totalPages - 1) {
            return false;
        }

        return $this->_currentPageIndex + 1;
    }

    public function setQueryParams(array $_queryParams): void
    {
        $this->_queryParams = $_queryParams;
    }

    private function getQueryParams(): array
    {
        return $this->_queryParams;
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
