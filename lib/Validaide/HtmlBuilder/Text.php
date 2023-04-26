<?php declare(strict_types=1);

namespace Validaide\HtmlBuilder;

use Stringable;
class Text implements Stringable
{
    public function __construct(private readonly string $text, private readonly ?HTML $parent = null, private readonly bool $raw = false)
    {
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
        return htmlspecialchars($this->text, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    public function isRaw(): bool
    {
        return $this->raw;
    }
}
