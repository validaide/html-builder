<?php declare(strict_types=1);

namespace Validaide\HtmlBuilder;

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
        if ($this->isRaw()) {
            return $this->text;
        }

        // Make sure the content is 'safe'
        // @see http://php.net/manual/en/function.htmlspecialchars.php
        return htmlspecialchars($this->text);
    }

    public function isRaw(): bool
    {
        return $this->raw;
    }
}
