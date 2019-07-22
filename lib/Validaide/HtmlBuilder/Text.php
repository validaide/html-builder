<?php

namespace Validaide\HtmlBuilder;

/**
 * @author Mark Bijl <mark.bijl@validaide.com>
 */
class Text
{
    /** @var string */
    private $text;
    /** @var null|HTML */
    private $parent;
    /** @var bool */
    private $raw;

    /**
     * @param string    $text
     * @param HTML|null $parent
     * @param bool      $raw
     */
    public function __construct(string $text, HTML $parent = null, bool $raw = false)
    {
        $this->text   = $text;
        $this->parent = $parent;
        $this->raw    = $raw;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->text;
    }

    /**
     * @return bool
     */
    public function isRaw(): bool
    {
        return $this->raw;
    }
}
