<?php

namespace Nullix\Sasige;

/**
 * A group of pages filtered and ordered by specific filters
 */
class Pageset
{

    /**
     * The filter to filter the pages
     * @var array
     */
    private $filter = [];

    /**
     * Order entries by
     * @var string[]
     */
    private $orderBy;

    /**
     * Get pages for this pageset
     *
     * @return Page[]
     * @throws Exception
     */
    public function getPages()
    {
        $allPages = Page::getPublicInstances();
        $matchedPages = [];
        // all include filters, if one filter matches than add to matched pages
        foreach ($allPages as $page) {
            foreach ($this->filter as $row) {
                if ($row["merge"] == "include") {
                    $result = $this->isPageMatchingFilter($page, $row);
                    if ($result) {
                        $matchedPages[] = $page;
                        continue 2;
                    }
                }
            }
        }
        // all exclude filters, if one matches than remove from page set
        foreach ($matchedPages as $key => $page) {
            foreach ($this->filter as $row) {
                if ($row["merge"] == "exclude") {
                    $result = $this->isPageMatchingFilter($page, $row);
                    if ($result) {
                        unset($matchedPages[$key]);
                        continue 2;
                    }
                }
            }
        }
        // all filter filters, this filter must match with a page that already have been included by other filters
        // we call it intersect
        foreach ($matchedPages as $key => $page) {
            foreach ($this->filter as $row) {
                if ($row["merge"] == "intersect") {
                    $result = $this->isPageMatchingFilter($page, $row);
                    if (!$result) {
                        unset($matchedPages[$key]);
                        continue 2;
                    }
                }
            }
        }
        // sort if required
        if ($this->orderBy) {
            $parameters = [];
            foreach ($this->orderBy as $orderBy) {
                $direction = substr($orderBy, 0, 1);
                if ($direction != "-" && $direction != "+") {
                    throw new Exception("Required +/- prefix for orderBy in pageset");
                }
                $direction = $direction === "-" ? SORT_DESC : SORT_ASC;
                $field = substr($orderBy, 1);
                $sort = [];
                foreach ($matchedPages as $key => $page) {
                    $sortValue = null;
                    if ($field === "date") {
                        $sortValue = $page->getDate()->format("U");
                    }
                    if ($field === "label") {
                        $sortValue = $page->getLabel();
                    }
                    if ($field === "sort") {
                        $sortValue = $page->getSort();
                    }
                    $sort[$key] = $sortValue;
                }
                $parameters[] = $sort;
                $parameters[] = $direction;
            }
            $parameters[] = &$matchedPages;
            call_user_func_array("array_multisort", $parameters);
        }
        return $matchedPages;
    }

    /**
     * Set order by - Allowed: +date, -date, +label, -label, +sort, -sort
     * A plus means ASC
     * A minus means DESC
     * @param string|string[] $orderBy Could be array of multiple sorts or a single sort
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = !is_array($orderBy) ? [$orderBy] : $orderBy;
    }

    /**
     * Include pages their relative path and filename matches the regex
     * @param string $regex
     */
    public function includePagesByRegex($regex)
    {
        $this->filter[] = [
            "type" => "regex",
            "regex" => $regex,
            "merge" => "include"
        ];
    }

    /**
     * Exclude pages their relative path and filename matches the regex
     * @param string $regex
     */
    public function excludePagesByRegex($regex)
    {
        $this->filter[] = [
            "type" => "regex",
            "regex" => $regex,
            "merge" => "exclude"
        ];
    }

    /**
     * Filter pages their relative path and filename matches the regex
     * @param string $regex
     */
    public function filterPagesByRegex($regex)
    {
        $this->filter[] = [
            "type" => "regex",
            "regex" => $regex,
            "merge" => "intersect"
        ];
    }


    /**
     * Include pages that have match all of the given tags
     * @param string[] $tags
     */
    public function includePagesByTags(array $tags)
    {
        $this->filter[] = [
            "type" => "tags",
            "tags" => $tags,
            "merge" => "include"
        ];
    }

    /**
     * Exclude pages that match all of the given tags
     * @param string[] $tags
     */
    public function excludePagesByTags(array $tags)
    {
        $this->filter[] = [
            "type" => "tags",
            "tags" => $tags,
            "merge" => "exclude"
        ];
    }


    /**
     * Filter pages that match all of the given tags
     * @param string[] $tags
     */
    public function filterPagesByTags(array $tags)
    {
        $this->filter[] = [
            "type" => "tags",
            "tags" => $tags,
            "merge" => "intersect"
        ];
    }

    /**
     * Include pages with the given language
     * @param string $language
     */
    public function includePagesByLanguage($language)
    {
        $this->filter[] = [
            "type" => "language",
            "language" => $language,
            "merge" => "include"
        ];
    }

    /**
     * Exclude pages with the given language
     * @param string $language
     */
    public function excludePagesByLanguage($language)
    {
        $this->filter[] = [
            "type" => "language",
            "language" => $language,
            "merge" => "exclude"
        ];
    }

    /**
     * Filter pages with the given language
     * @param string $language
     */
    public function filterPagesByLanguage($language)
    {
        $this->filter[] = [
            "type" => "language",
            "language" => $language,
            "merge" => "intersect"
        ];
    }

    /**
     * Include a single page (or multiple with an array) by filenames to the set
     * @param string|string[] $filenames
     */
    public function includePages($filenames)
    {
        if (!is_array($filenames)) {
            $filenames = [$filenames];
        }
        $this->filter[] = [
            "type" => "manual",
            "pages" => $filenames,
            "merge" => "include"
        ];
    }

    /**
     * Include a single page (or multiple with an array) by filenames to the set
     * @param string|string[] $filenames
     */
    public function excludePages($filenames)
    {
        if (!is_array($filenames)) {
            $filenames = [$filenames];
        }
        $this->filter[] = [
            "type" => "manual",
            "pages" => $filenames,
            "merge" => "exclude"
        ];
    }

    /**
     * Filter a single page (or multiple with an array) by filenames to the set
     * They must already exist in the matched pages, intersect
     * @param string|string[] $filenames
     */
    public function filterPages($filenames)
    {
        if (!is_array($filenames)) {
            $filenames = [$filenames];
        }
        $this->filter[] = [
            "type" => "manual",
            "pages" => $filenames,
            "merge" => "intersect"
        ];
    }

    /**
     * Get html flat list for all pages
     *
     * @return string
     */
    public function getHtmlListFlat()
    {
        $out = '<ul class="pageset-list">';
        foreach ($this->getPages() as $page) {
            $out .= '<li class="pageset-list-item '
                . (Page::getCurrent() === $page ? "pageset-list-item-active" : "")
                . '"><a href="' . $page->getUrl() . '" class="pageset-link">'
                . $page->getLabel() . '</a></li>';
        }
        $out .= '</ul>';
        return $out;
    }

    /**
     * Get recursive html lists, depending on the parent the pages
     * Imagine a common folder structure
     *
     * @param string|null $parent
     * @param int $level
     * @return string
     */
    public function getHtmlListRecursive($parent = null, $level = 0)
    {
        $pages = $this->getPages();
        $validPages = [];
        foreach ($pages as $page) {
            if ($page->getParent() === $parent) {
                $validPages[] = $page;
            }
        }
        if (!$validPages) {
            return "";
        }
        $out = '<ul class="pageset-list pageset-list-level-' . $level . '">';
        foreach ($validPages as $page) {
            $tag = '<a href="' . $page->getUrl() . '" class="pageset-link">' . $page->getLabel() . '</a>';
            $tag .= $this->getHtmlListRecursive($page->getPath(), $level + 1);
            $out .= '<li class="pageset-list-item '
                . (Page::getCurrent() === $page ? "pageset-list-item-active" : "")
                . '">' . $tag . '</li>';

        }
        $out .= '</ul>';
        return $out;
    }

    /**
     * Check if page is matching given filter
     * @param Page $page
     * @param array $filter
     * @return bool
     */
    private function isPageMatchingFilter(Page $page, $filter)
    {
        if ($filter["type"] == "tags") {
            $pageTags = $page->getTags();
            if (!$pageTags) {
                return false;
            }
            foreach ($filter["tags"] as $tag) {
                if (!in_array($tag, $pageTags)) {
                    return false;
                }
            }
            return true;
        }
        if ($filter["type"] == "manual") {
            foreach ($filter["pages"] as $filename) {
                if ($filename == $page->getPath()) {
                    return true;
                }
            }
            return false;
        }
        if ($filter["type"] == "regex") {
            return !!preg_match($filter["regex"], $page->getPath());
        }
        if ($filter["type"] == "language") {
            return $page->getLanguage() === $filter["language"];
        }
        return false;
    }
}
