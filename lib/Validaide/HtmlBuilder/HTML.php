<?php

namespace Validaide\HtmlBuilder;

use LogicException;
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
     * @param bool   $raw
     *
     * @return HTML
     */
    public function text(string $text, bool $raw = false): HTML
    {
        $text = new Text($text, $this, $raw);

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

    /*****************************************************************************/
    /* ATTRIBUTES
    /*****************************************************************************/

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
     * @param string|array $value
     *
     * @return HTML
     */
    public function class($value): self
    {
        if (is_array($value)) {
            return $this->attr('class', implode(' ', $value));
        }

        return $this->attr('class', $value);
    }

    /**
     * Set the class attribute
     *
     * @param string|array $value
     *
     * @return HTML
     */
    public function classPrepend($value): self
    {
        $class = $this->getClass();

        if (is_array($value)) {
            $value = $this->attr('class', implode(' ', $value));
        }

        $classNew = sprintf("%s %s", $value, $class);

        return $this->attr('class', $classNew);
    }

    /**
     * Set the class attribute
     *
     * @param string|array $value
     *
     * @return HTML
     */
    public function classAppend($value): self
    {
        $class = $this->getClass();

        if (is_array($value)) {
            $value = $this->attr('class', implode(' ', $value));
        }

        $classNew = sprintf("%s %s", $class, $value);

        return $this->attr('class', $classNew);
    }

    /**
     * Set the href attribute
     *
     * @param string $value
     *
     * @return HTML
     */
    public function href(string $value): self
    {
        return $this->attr('href', $value);
    }

    /**
     * Set the title attribute
     *
     * @param string $value
     *
     * @return HTML
     */
    public function title(string $value): self
    {
        return $this->attr('title', $value);
    }

    /**
     * Set the style attribute
     *
     * @param string $value
     *
     * @return HTML
     */
    public function style(string $value): self
    {
        return $this->attr('style', $value);
    }

    /**
     * Set the dataToggle attributes
     *
     * @param string      $value
     * @param string|null $dataPlacement
     * @return HTML
     */
    public function dataToggle(string $value, string $dataPlacement = null): self
    {
        if ($dataPlacement){
            $this->attr('data-placement', $value);
        }

        return $this->attr('data-toggle', $value);
    }

    /*****************************************************************************/
    /* RENDER
    /*****************************************************************************/

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
                if ($tag->isRaw()) {
                    $result .= $tag->render();
                } else {
                    // Make sure the content is 'safe'
                    // @see http://php.net/manual/en/function.htmlspecialchars.php
                    $result .= htmlspecialchars($tag->render());
                }
            }
        }

        return $result;
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
     * @throws LogicException
     */
    public function html(bool $pretty = false): string
    {
        if ($pretty) {
            if (!extension_loaded('tidy')) {
                throw new LogicException('The Tidy extension is not loaded: you cannot pretty-print without it');
            }

            $tidy = new Tidy();
            $tidy->parseString($this->renderTag(), ['indent' => true, 'show-body-only' => true, 'indent-spaces' => 4, 'quote-ampersand' => false], 'utf8');

            return (string)$tidy;
        }

        return $this->renderTag();
    }

    /*****************************************************************************/
    /* GETTERS
    /*****************************************************************************/

    /**
     * @return HTML|null
     */
    private function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return array_key_exists('class', $this->attributes) ? $this->attributes['class'] : null;
    }
}
