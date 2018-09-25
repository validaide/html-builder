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

    /**
     * TextElement constructor.
     *
     * @param string    $text
     * @param HTML|null $parent
     */
    public function __construct(string $text, HTML $parent = null)
    {
        $this->text   = $text;
        $this->parent = $parent;

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
}
