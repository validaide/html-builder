<?php

namespace Validaide\HtmlBuilder;

/**
 * @author Mark Bijl <mark.bijl@validaide.com>
 */
class Text
{
    private string $text;
    private ?HTML $parent;
    private bool $raw;

    public function __construct(string $text, ?HTML $parent = null, bool $raw = false)
    {
        $this->text   = $text;
        $this->parent = $parent;
        $this->raw    = $raw;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function render(): string
    {
        return $this->text;
    }

    public function isRaw(): bool
    {
        return $this->raw;
    }
}
