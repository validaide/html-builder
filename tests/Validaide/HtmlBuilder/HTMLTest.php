<?php

namespace Tests\Validaide\HtmlBuilder;

use Exception;
use PHPUnit\Framework\TestCase;
use Validaide\HtmlBuilder\HTML;
use Validaide\HtmlBuilder\PurifierBuilder;

class HTMLTest extends TestCase
{
    /*****************************************************************************/
    /* Tests
    /*****************************************************************************/

    /**
     * @dataProvider inputCommandToOutputFilesProvider
     *
     * @param string $inputCommandFilePath
     * @param string $outputFilePathFlat
     * @param string $outputFilePathPretty
     *
     * @group        grain
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

    public function testIncludedTagsShouldHaveAParent()
    {
        // Arrange
        $ul = HTML::create(HTML::LIST);
        $li = HTML::create('li');

        // Act
        $ul->tagHTML($li);

        // Assert
        $this->assertEquals($ul, $li->getParent());
    }

    /*****************************************************************************/
    /* Helpers
    /*****************************************************************************/

    /**
     * @return array
     */
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

    /**
     * @param HTML   $html
     * @param string $outputFilePathFlat
     * @param string $outputFilePathPretty
     */
    protected function assertEqualsToHtmlFile(HTML $html, string $outputFilePathFlat, string $outputFilePathPretty)
    {
        $this->assertEquals(file_get_contents($outputFilePathFlat), $html->html());
        $this->assertEquals(file_get_contents($outputFilePathPretty), $html->html(true));
    }
}