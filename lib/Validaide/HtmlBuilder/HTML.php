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
    private $attributes = [];
    /** @var HTML[] */
    private $content = [];
    /** @var null|HTML */
    private $parent;

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
            $tidy->parseString($this->renderTag(), ['indent' => true, 'show-body-only' => true, 'indent-spaces' => 4, 'quote-ampersand' => true], 'utf8');

            return (string)$tidy;
        }

        return $this->renderTag();
    }

    /**
     * Create a Html tag
     *
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
     * Open a HTML tag
     *
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
     * Add text inside the tag
     *
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
     * Close a HTML tag
     *
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
     * Set an attribute
     *
     * @param string $key
     * @param string $value
     *
     * @return HTML
     */
    public function attr(string $key, string $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Synonym for 'attr'
     *
     * @param string $key
     * @param string $value
     *
     * @return HTML
     */
    public function attribute(string $key, string $value): self
    {
        return $this->attr($key, $value);
    }

    /**
     * Remove an attribute
     *
     * @param string $key
     *
     * @return HTML
     */
    public function rattr(string $key): self
    {
        unset($this->attributes[$key]);

        return $this;
    }

    /**
     * Synonym for 'rattr'
     *
     * @param string $key
     *
     * @return HTML
     */
    public function removeAttribute(string $key): self
    {
        return $this->rattr($key);
    }

    // Attribute shorthands

    /**
     * Set the id attribute
     *
     * @param string $value
     *
     * @return HTML
     */
    public function id(string $value): self
    {
        return $this->attr('id', $value);
    }

    /**
     * Set the class attribute
     *
     * @param string $value
     *
     * @return HTML
     */
    public function class(string $value): self
    {
        return $this->attr('class', $value);
    }

    /**
     * @return HTML|null
     */
    private function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    private function renderTag(): string
    {
        return sprintf(
            "<%s%s>%s</%s>",
            $this->name,
            count($this->attributes) === 0 ? '' : ' ' . $this->renderAttributes(),
            $this->renderTags(),
            $this->name
        );
    }

    /**
     * @return string
     */
    private function renderAttributes(): string
    {
        ksort($this->attributes);

        $argumentArray = [];
        foreach ($this->attributes as $argument => $value) {
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
                // Make sure the content is 'safe'
                // @see http://php.net/manual/en/function.htmlspecialchars.php
                $result .= htmlspecialchars($tag->render());
            }
        }

        return $result;
    }
}
