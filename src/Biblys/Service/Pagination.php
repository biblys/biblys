<?php

namespace Biblys\Service;

use InvalidArgumentException;

class Pagination
{
    private $_page,
        $_currentPage,
        $_articleCount,
        $_limit,
        $_totalPages,
        $_offset;

    public function __construct($page, $articleCount, $limit = null)
    {
        global $site;

        if ($page < 0) {
            throw new InvalidArgumentException("Page number cannot be less than 0");
        }

        $this->_page = $page;
        $this->_currentPage = $page + 1;
        $this->_articleCount = $articleCount;

        $this->_limit = $site->getOpt('articles_per_page');
        if (!$this->_limit) {
            $this->_limit = 10;
        }
        if ($limit) {
            $this->_limit = $limit;
        }

        $this->_totalPages = ceil($this->_articleCount / $this->_limit);
        $this->_offset = $page * $this->_limit;
    }

    public function getCurrent()
    {
        return $this->_currentPage;
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
        if ($this->_page < 1) {
            return false;
        }
        return $this->_page - 1;
    }

    public function getPreviousQuery(): ?string
    {
        return $this->getPrevious() ? "?p={$this->getPrevious()}" : null;
    }

    public function getNext()
    {
        if ($this->_page >= $this->_totalPages - 1) {
            return false;
        }
        return $this->_page + 1;
    }

    public function getNextQuery(): ?string
    {
        return $this->getNext() ? "?p={$this->getNext()}" : null;
    }

}
