<?php declare(strict_types=1);

namespace Validaide\HtmlBuilder;

use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\Deprecated;
use LogicException;
use tidy;

class HTML
{
    public const DIV  = 'div';
    public const SPAN = 'span';
    public const LIST = 'ul';

    protected static ?HTML $instance = null;

    private string $name = '';

    private array $attributes = [];

    /** @var HTML[]|Text */
    private array $content = [];

    private ?HTML $parent;

    /** @var null|HTML[] */
    private ?array $appendHTML = [];

    /** @var null|HTML[] */
    private ?array $prependHTML = [];

    protected function __construct(string $name, ?HTML $parent = null)
    {
        $name = preg_replace("/[^a-zA-Z0-9]+/", "", $name);

        $this->name       = $name;
        $this->parent     = $parent;
    }

    public static function create(string $name, HTML $parent = null): HTML
    {
        self::$instance = new self($name, $parent);

        return self::$instance;
    }

    public function tag(string $name): HTML
    {
        $tag = new HTML($name, $this);

        $this->content[] = $tag;

        return $this->content[count($this->content) - 1];
    }

    public function tagHTML(HTML $tag): HTML
    {
        $this->content[] = $tag;

        return $this->content[count($this->content) - 1];
    }

    public function append(HTML $html): HTML
    {
        $this->appendHTML[] = $html;

        return $this;
    }

    public function prepend(HTML $html): HTML
    {
        $this->prependHTML[] = $html;

        return $this;
    }

    /**
     * $raw parameter is deprecated
     * Use instead tagHTML in order to insert html into the mix
     */
    public function text(string $text,
                         #[Deprecated]
                         bool $raw = false): HTML
    {
        $text = new Text($text, $this, $raw);

        $this->content[] = $text;

        return $this;
    }

    public function list(array $array): HTML
    {
        foreach ($array as $element) {
            $liElement = self::create('li', $this)->text($element);
            $this->tagHTML($liElement);
        }

        return $this;
    }

    public function end(): HTML
    {
        if ($this->parent !== null) {
            return $this->getParent();
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function attr(string $key, string $value): self
    {
        PurifierBuilder::checkAttribute($key, $this->name);

        $this->attributes[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE);

        return $this;
    }

    public function rattr(string $key): self
    {
        unset($this->attributes[$key]);

        return $this;
    }

    public function removeAttribute(string $key): self
    {
        return $this->rattr($key);
    }

    /*****************************************************************************/
    /* ATTRIBUTES
    /*****************************************************************************/

    public function attribute(string $key, string $value): self
    {
        return $this->attr($key, $value);
    }

    public function id(string $value): self
    {
        return $this->attr('id', $value);
    }

    public function class($value): self
    {
        $value = $this->generateClassString($value);

        return $this->attr('class', $value);
    }

    public function classPrepend($value): self
    {
        $classNew = sprintf("%s %s", $this->generateClassString($value), $this->getClass());

        return $this->attr('class', $classNew);
    }

    public function classAppend($value): self
    {
        $classNew = sprintf("%s %s", $this->getClass(), $this->generateClassString($value));

        return $this->attr('class', $classNew);
    }

    public function href(string $value): self
    {
        return $this->attr('href', $value);
    }

    public function title(string $value): self
    {
        return $this->attr('title', $value);
    }

    public function titleAppend(string $value): self
    {
        return $this->attr('title', $this->getTitle() . $value);
    }

    public function style(string $value): self
    {
        return $this->attr('style', $value);
    }

    public function dataToggle(string $value, ?string $dataPlacement = null): self
    {
        if ($dataPlacement) {
            $this->attr('data-placement', $dataPlacement);
        }

        return $this->attr('data-toggle', $value);
    }

    private function renderTag(): string
    {
        $renderedString = sprintf(
            "<%s%s>%s</%s>",
            $this->name,
            $this->attributes === [] ? '' : ' ' . $this->renderAttributes(),
            $this->renderTags(),
            $this->name
        );

        if (count((array) $this->appendHTML) > 0) {
            $renderedString .= $this->renderAppendedString();
        }

        if (count((array) $this->prependHTML) > 0) {
            $renderedString = sprintf("%s%s", $this->renderPrependedString(), $renderedString);
        }

        return $renderedString;
    }

    private function renderAppendedString(): string
    {
        $appendString = '';

        foreach ($this->appendHTML as $appendHTML) {
            $appendString .= $appendHTML;
        }

        return $appendString;
    }

    private function renderPrependedString(): string
    {
        $prependString = '';

        foreach ($this->prependHTML as $prependHTML) {
            $prependString .= $prependHTML;
        }

        return $prependString;
    }

    private function renderAttributes(): string
    {
        ksort($this->attributes);

        $argumentArray = [];
        foreach ($this->attributes as $argument => $value) {
            $argumentArray[] = sprintf('%s="%s"', $argument, $value);
        }

        return implode(" ", $argumentArray);
    }

    private function renderTags(): string
    {
        $result = '';
        foreach ($this->content as $tag) {
            if ($tag instanceof HTML) {
                $result .= $tag->html();
            } elseif ($tag instanceof Text) {
                $result .= $tag->render();
            }
        }

        return $result;
    }

    public function __toString(): string
    {
        return $this->renderTag();
    }

    public function isEmpty(): bool
    {
        return count($this->attributes) === 0 && count($this->content) === 0 && count($this->appendHTML) === 0 && count($this->prependHTML) === 0;
    }

    /**
     * @throws LogicException
     */
    public function html(bool $pretty = false): string
    {
        if ($pretty) {
            if (!extension_loaded(tidy::class)) {
                throw new LogicException('The Tidy extension is not loaded: you cannot pretty-print without it');
            }

            $tidy = new Tidy();
            $tidy->parseString($this->renderTag(), ['indent' => true, 'show-body-only' => true, 'indent-spaces' => 4, 'quote-ampersand' => false], 'utf8');

            return (string)$tidy;
        }

        $renderedString = $this->renderTag();

        // CN: due to the recursive nature of this method call, we apply purifier only on the final result
        if ($this->isTopLevel()) {
            $purifier = PurifierBuilder::purifier();
            return $purifier->purify($renderedString);
        }

        return $renderedString;
    }

    /*****************************************************************************/
    /* GETTERS
    /*****************************************************************************/

    private function getParent(): ?HTML
    {
        return $this->parent;
    }

    public function getClass(): ?string
    {
        return $this->attributes['class'] ?? null;
    }

    public function getText(): ?string
    {
        return $this->attributes['text'] ?? null;
    }

    public function getTitle(): ?string
    {
        return $this->attributes['title'] ?? null;
    }

    public function isTopLevel(): bool
    {
        return !(bool)$this->getParent();
    }

    /*****************************************************************************/
    /* HELPERS
    /*****************************************************************************/

    public function generateClassString($value): string
    {
        switch (true) {
            case is_array($value):
                return implode(' ', $value);
            case is_string($value):
                return $value;
            default:
                throw new InvalidArgumentException('Class method accepts only string or array as an argument');
        }
    }
}
