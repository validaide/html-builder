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
    /** @var int */
    private $level;

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
        return $this->render();
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->text;
    }
}
