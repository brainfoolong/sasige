<?php

namespace Nullix\Sasige;

/**
 * Hold information about the current page context during generation
 */
class Page
{
    /**
     * All instances
     *
     * @var Page[]
     */
    private static $instances = [];

    /**
     * The current page
     *
     * @var Page
     */
    private static $current;

    /**
     * The parent page
     *
     * @var string
     */
    private $parent;

    /**
     * The tags
     *
     * @var string[]
     */
    private $tags;

    /**
     * The date
     *
     * @var \DateTime
     */
    private $date;

    /**
     * The language
     *
     * @var string
     */
    private $language;

    /**
     * The label
     *
     * @var string
     */
    private $label;

    /**
     * The page title
     *
     * @var string
     */
    private $pageTitle;

    /**
     * The lead text
     *
     * @var string
     */
    private $leadText;

    /**
     * The content callable
     *
     * @var callable
     */
    private $content;

    /**
     * The full path to the page file
     *
     * @var string
     */
    private $fullPath;

    /**
     * The directory
     *
     * @var string
     */
    private $directory;

    /**
     * The filename
     *
     * @var string
     */
    private $filename;

    /**
     * The template
     *
     * @var string
     */
    private $template;

    /**
     * The custom properties
     *
     * @var array
     */
    private $customProperties;

    /**
     * The sort value
     *
     * @var mixed
     */
    private $sort;

    /**
     * The pagination
     *
     * @var Pagination
     */
    private $pagination;

    /**
     * Set instances
     * @param Page[] $instances
     */
    public static function setInstances($instances)
    {
        self::$instances = $instances;
    }

    /**
     * Create new instance
     *
     * @return Page
     */
    public static function create()
    {
        $page = new self();
        $page->setLanguage(Config::getDefaultLanguage());
        self::$instances[] = $page;
        return $page;
    }

    /**
     * Get all instances that are considered public (no drafts)
     * @return Page[]
     */
    public static function getPublicInstances()
    {
        $pages = [];
        foreach (self::$instances as $page) {
            if ($page->getDate()) {
                $pages[] = $page;
            }
        }
        return $pages;
    }

    /**
     * @return Page
     */
    public static function getCurrent()
    {
        return self::$current;
    }

    /**
     * @param Page $current
     */
    public static function setCurrent($current)
    {
        self::$current = $current;
    }

    /**
     * Get parent page
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent page, must be relative path to page starting from the pages root folder without any file extension
     *
     * @param string $parent
     */
    public function setParentPage($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Set parent to the name of the pages directory
     * For that, the parent directory have a page with the same name as the directory itself
     * For example:
     *      pages/docs/installation -> The pages directory
     *      pages/docs/installation.php -> The parent page that need to be set
     */
    public function setParentToDirectory()
    {
        $this->setParentPage($this->directory);
    }

    /**
     * Get url to the theme public folder
     *
     * @param string $append
     * @return string
     */
    public function getThemeUrl($append)
    {
        return $this->getPublicRootUrl("sasige-theme/" . $append);
    }

    /**
     * Get a css link that will contain generated and concated content for the given scss/css files
     * @param string[] $files Relative file paths from the theme root folder
     * @return string
     */
    public function getThemeCssUrl(array $files)
    {
        $filename = Theme::getGeneratedFilename($files, "css");
        return $this->getPublicRootUrl("sasige-generated/" . $filename);
    }

    /**
     * Get a javascript link that will contain generated and concated content for the given javascript files
     * @param string[] $files Relative file paths from the theme root folder
     * @return string
     */
    public function getThemeJavascriptUrl(array $files)
    {
        $filename = Theme::getGeneratedFilename($files, "js");
        return $this->getPublicRootUrl("sasige-generated/" . $filename);
    }

    /**
     * Get a relative path to the public root folder, starting from the current page folder
     * @param null|string $append Append the string after the generated url
     * @return string
     */
    public function getPublicRootUrl($append = null)
    {
        $directory = $this->getDirectory();
        $url = "";
        if ($directory) {
            $slashes = substr_count($directory, "/") + 1;
            $url = str_repeat("../", $slashes);
        }
        if ($append) {
            $url .= $append;
        }
        return $url;
    }

    /**
     * Get url to page nr
     *
     * @param int $pageNr
     * @return string
     */
    public function getUrlToPageNr($pageNr)
    {
        $filename = $this->getFilenameToPageNr($pageNr) . ".html";
        if (Config::getHideIndexHtmlInUrls() && $filename === "index.html") {
            $filename = null;
        }
        if (Page::getCurrent() !== $this) {
            $path = $this->getDirectory();
            if ($path) {
                $path .= "/" . $filename;
            } else {
                $path = $filename !== null ? $filename : "./";
            }
            return Page::getCurrent()->getPublicRootUrl($path);
        } else {
            return $filename !== null ? $filename : "./";
        }
    }

    /**
     * Get url to this page
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getUrlToPageNr(1);
    }

    /**
     * Get filename to page nr
     *
     * @param int $pageNr
     * @return string
     */
    public function getFilenameToPageNr($pageNr)
    {
        return $pageNr === 1 ? $this->getFilename() : $this->getFilename() . "-$pageNr";
    }

    /**
     * Get the pagination
     *
     * @return Pagination
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * Set the pagination
     *
     * @param Pagination $pagination
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Get the specific sort value that can be used for the pagesets
     *
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set a specific sort value that can be used for the pagesets
     *
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * Get a custom property
     * @param string $name
     * @return mixed|null
     */
    public function getCustomProperty($name)
    {
        return isset($this->customProperties[$name]) ? $this->customProperties[$name] : null;
    }

    /**
     * Set a custom property value
     * @param string $name
     * @param mixed $value
     */
    public function setCustomProperty($name, $value)
    {
        $this->customProperties[$name] = $value;
    }

    /**
     * @return string
     */
    public function getThemeTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setThemeTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * @param string $fullPath
     */
    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Get relative path to the file
     *
     * @return string
     */
    public function getPath()
    {
        return File::concat($this->directory, $this->filename);
    }


    /**
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * @param string $pageTitle
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * @return string
     */
    public function getLeadText()
    {
        return $this->leadText;
    }

    /**
     * @param string $leadText
     */
    public function setLeadText($leadText)
    {
        $this->leadText = $leadText;
    }

    /**
     * Get the content
     *
     * @return string
     */
    public function getContent()
    {
        if (!$this->content) {
            return "";
        }
        if (is_callable($this->content)) {
            ob_start();
            call_user_func_array($this->content, [$this]);
            $content = ob_get_contents();
            ob_end_clean();
        } else {
            $content = $this->content;
        }
        return $content;
    }

    /**
     * Set content by given callable
     *
     * @param callable $content
     */
    public function setContentByCallable(callable $content)
    {
        $this->content = $content;
    }

    /**
     * Set the content of the page by given markdown string
     *
     * @param string $str
     */
    public function setContentByMarkdownString($str)
    {
        $this->content = function () use ($str) {
            $parsedown = \Parsedown::instance();
            echo $parsedown->text($str);
        };
    }

    /**
     * Set the content of the page by given markdown file
     * The path is relative to the page folder
     *
     * @param string $file
     */
    public function setContentByMarkdownFile($file)
    {
        $path = File::concat(dirname($this->fullPath), $file);
        $this->content = function () use ($path) {
            $parsedown = \Parsedown::instance();
            echo $parsedown->text(file_get_contents($path));
        };
    }

    /**
     * Set the content of the page by the markdown file with the same name as the page
     */
    public function setContentByMarkdownFileSelf()
    {
        $this->setContentByMarkdownFile($this->filename . ".md");
    }

    /**
     * Set the content of the page by given html string
     *
     * @param string $str
     */
    public function setContentByHtmlString($str)
    {
        $this->content = $str;
    }

    /**
     * Set the content of the page by given html file
     * The path is relative to the page folder
     *
     * @param string $file
     */
    public function setContentByHtmlFile($file)
    {
        $path = File::concat(dirname($this->fullPath), $file);
        $this->content = function () use ($path) {
            echo file_get_contents($path);
        };
    }

    /**
     * Set the content of the page by the html file with the same name as the page
     */
    public function setContentByHtmlFileSelf()
    {
        $this->setContentByHtmlFile($this->filename . ".html");
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param \DateTime|string $date
     */
    public function setDate($date)
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }
        $this->date = $date;
    }
}
