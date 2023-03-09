<?php

namespace Tests\Validaide\HtmlBuilder;

use Exception;
use PHPUnit\Framework\TestCase;
use Validaide\HtmlBuilder\HTML;

/**
 * @author Mark Bijl <mark.bijl@validaide.com>
 */
class HTMLTest extends TestCase
{
    /*****************************************************************************/
    /* Tests
    /*****************************************************************************/

    /**
     * @dataProvider inputCommandToOutputFilesProvider
     */
    public function testOutputs(string $inputCommandFilePath, string $outputFilePathFlat, string $outputFilePathPretty)
    {
        $code = require $inputCommandFilePath;

        $html = call_user_func($code);
        $this->assertEqualsToHtmlFile($html, $outputFilePathFlat, $outputFilePathPretty);
    }

    public function testUnSupportedAttribute()
    {
        // Assert (needs to be first in this case)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unsupported data attribute');

        // Arrange
        $h1 = HTML::create('h1');

        // Act
        $h1->attr('data-contenta', 'value');
    }

    public function testUnSupportedElementForAttributes()
    {
        // Assert (needs to be first in this case)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unsupported html element');

        // Arrange
        $h8 = HTML::create('h8');

        // Act
        $h8->attr('data-content', 'value');
    }

    public function testSupportedAttributeOrdElement()
    {
        // Arrange
        $h1 = HTML::create('h1');

        // Act
        $h1->attr('data-content', 'value');

        // Assert
        $this->assertEquals('<h1 data-content="value"></h1>', $h1->html());
    }

    public function testSupportedAttributeOrDataBS3Element()
    {
        // Arrange
        $h1 = HTML::create('h1');

        // Act
        $h1->attr('data-toggle', 'dialog');

        // Assert
        $this->assertEquals('<h1 data-toggle="dialog"></h1>', $h1->html());
    }

    public function testSupportedAttributeOrDataBS5Element()
    {
        // Arrange
        $h1 = HTML::create('h1');

        // Act
        $h1->attr('data-bs-toggle', 'dialog');

        // Assert
        $this->assertEquals('<h1 data-bs-toggle="dialog"></h1>', $h1->html());
    }

    /*****************************************************************************/
    /* Helpers
    /*****************************************************************************/

    public function inputCommandToOutputFilesProvider(): array
    {
        $baseDir = __DIR__;

        return array_map(
            null,
            glob($baseDir . '/_input/input_*.php'),
            glob($baseDir . '/_output/output_*_flat.html'),
            glob($baseDir . '/_output/output_*_pretty.html')
        );
    }

    protected function assertEqualsToHtmlFile(HTML $html, string $outputFilePathFlat, string $outputFilePathPretty)
    {
        $this->assertEquals(file_get_contents($outputFilePathFlat), $html->html(), sprintf('Flat File: %s', $outputFilePathFlat));
        $this->assertEquals(file_get_contents($outputFilePathPretty), $html->html(true), sprintf('Pretty File: %s', $outputFilePathPretty));
    }
}