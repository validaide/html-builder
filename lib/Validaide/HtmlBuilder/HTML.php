<?php

namespace Validaide\HtmlBuilder;

use tidy;

/**
 * @author Mark Bijl <mark.bijl@validaide.com>
 */
class HTML
{
    /** @var HTML */
    protected static $instance = null;
    /** @var string */
    private $name = '';
    /** @var array */
    private $arguments = [];
    /** @var HTML[] */
    private $content = [];
    /** @var null|HTML */
    private $parent;
    /** @var int */
    private $level;

    /**
     * TagElement constructor.
     *
     * @param string    $name
     * @param HTML|null $parent
     */
    protected function __construct(string $name, HTML $parent = null)
    {
        $this->name   = $name;
        $this->parent = $parent;
        if (is_null($parent)) {
            $this->level = 0;
        } else {
            $this->level = $parent->getLevel() + 1;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->renderTag();
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param bool $pretty
     *
     * @return string
     *
     * @throws \LogicException
     */
    public function html(bool $pretty = false): string
    {
        if ($pretty) {
            if (!extension_loaded('tidy')) {
                throw new \LogicException('The Tidy extension is not loaded: you cannot pretty-print without it');
            }

            $tidy = new Tidy();
            $tidy->parseString($this->renderTag(), ['indent' => true, 'show-body-only' => true, 'indent-spaces' => 4]);

            return (string)$tidy;
        }

        return $this->renderTag();
    }

    /**
     * @param string $name
     *
     * @return HTML
     */
    public static function create(string $name)
    {
        self::$instance = new static($name);

        return self::$instance;
    }

    /**
     * @param string $name
     *
     * @return HTML
     */
    public function tag(string $name): HTML
    {
        $tag = new HTML($name, $this);

        $this->content[] = $tag;

        return $this->content[count($this->content) - 1];
    }

    /**
     * @param string $text
     *
     * @return HTML
     */
    public function text(string $text): HTML
    {
        $text = new Text($text, $this);

        $this->content[] = $text;

        return $this;
    }

    /**
     * @return HTML
     */
    public function end(): HTML
    {
        if ($this->parent) {
            return $this->getParent();
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return HTML
     */
    public function arg(string $key, string $value): self
    {
        $this->arguments[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return HTML
     */
    public function rarg(string $key): self
    {
        unset($this->arguments[$key]);

        return $this;
    }

    /**
     * @param HTML $parent
     */
    private function setParent(HTML $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return HTML|null
     */
    private function getParent()
    {
        return $this->parent;
    }

    /**
     * @param bool $pretty
     *
     * @return string
     */
    private function renderTag(): string
    {
        return sprintf(
            "<%s%s>%s</%s>",
            $this->name,
            count($this->arguments) === 0 ? '' : ' ' . $this->renderAttributes(),
            $this->renderTags(),
            $this->name
        );
    }

    /**
     * @return string
     */
    private function renderAttributes(): string
    {
        ksort($this->arguments);

        $argumentArray = [];
        foreach ($this->arguments as $argument => $value) {
            $argumentArray[] = sprintf("%s=\"%s\"", $argument, $value);
        }

        return implode(" ", $argumentArray);
    }

    /**
     * @return string
     */
    private function renderTags(): string
    {
        $result = '';
        foreach ($this->content as $tag) {
            if ($tag instanceof HTML) {
                $result .= $tag->html();
            } else if ($tag instanceof Text) {
                $result .= $tag->render();
            }
        }

        return $result;
    }
}
