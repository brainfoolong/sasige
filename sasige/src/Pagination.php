<?php

namespace Nullix\Sasige;

/**
 * Class Pagination
 */
class Pagination
{

    /**
     * The page
     * @var Page
     */
    private $page;

    /**
     * The pageset
     * @var Pageset
     */
    private $pageset;

    /**
     * Set the base page for this pagination (relative in content/pages folder)
     * @var string
     */
    private $basePage;

    /**
     * How much entries per page
     * @var int
     */
    private $entriesPerPage;

    /**
     * The current page in the progress
     * @var int
     */
    private $currentPage;

    /**
     * Pagination constructor.
     * @param Page $page
     * @param Pageset $pageset
     */
    public function __construct(Page $page, Pageset $pageset)
    {
        $this->pageset = $pageset;
        $this->page = $page;
        $page->setPagination($this);
    }

    /**
     * @return Pageset
     */
    public function getPageset()
    {
        return $this->pageset;
    }

    /**
     * @param int $entriesPerPage
     */
    public function setEntriesPerPage($entriesPerPage)
    {
        $this->entriesPerPage = $entriesPerPage;
    }

    /**
     * Get total number of pages in this pagination
     *
     * @return int
     */
    public function getTotalPages()
    {
        return ceil(count($this->pageset->getPages()) / $this->entriesPerPage);
    }

    /**
     * Get the current page number during generation progress
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Set the current page number for generation progress
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * Get pages of given page nr
     * @param null|int $pageNr If null than use currentPage number
     * @return Page[]
     */
    public function getPagesOfPage($pageNr = null)
    {
        if ($pageNr === null) {
            $pageNr = $this->currentPage;
        }
        $this->setCurrentPage($pageNr);
        $allPages = $this->pageset->getPages();
        $allPagesCount = count($allPages);
        $perPage = $this->entriesPerPage;
        $start = ($pageNr - 1) * $perPage;
        $end = $start + $perPage;
        if ($end > $allPagesCount) {
            $end = $allPagesCount;
        }
        return array_splice($allPages, $start, $end);
    }

    /**
     * Get html list for the number of total pages
     *
     * @return string
     */
    public function getNumericPageHtmlList()
    {
        $pages = $this->getTotalPages();
        $out = '<ul class="pagination-list">';
        for ($i = 1; $i <= $pages; $i++) {
            $out .= '<li class="pagination-list-item '
                . ($this->getCurrentPage() === $i ? "pagination-list-item-active" : "")
                . '"><a href="' . $this->page->getUrlToPageNr($i) . '" class="pagination-link">'
                . $i . '</a></li>';
        }
        $out .= '</ul>';
        return $out;
    }
}
