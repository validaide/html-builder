<?php declare(strict_types=1);

namespace Tests\Validaide\HtmlBuilder;

use PHPUnit\Framework\TestCase;
use Validaide\HtmlBuilder\HTML;

class HTML_XSS_Test extends TestCase
{
    /*****************************************************************************/
    /* Tests
       /*****************************************************************************/
    /**
     * @dataProvider inputCommandToOutputFilesProvider
     *
     *
     * @group        grain
     */
    public function testOutputs(string $inputCommandFilePath, string $outputFilePath)
    {
        $code = require $inputCommandFilePath;

        $html = call_user_func($code);
        $this->assertEqualsToHtmlFile($html, $outputFilePath);
    }

    public function inputCommandToOutputFilesProvider(): array
    {
        $baseDir = __DIR__;

        return array_map(
            null,
            glob($baseDir . '/_input_xss/input_*.php'),
            glob($baseDir . '/_output_xss/output_*.html'),
        );
    }

    protected function assertEqualsToHtmlFile(HTML $html, string $outputFilePathFlat)
    {
        $this->assertEquals(file_get_contents($outputFilePathFlat), $html->html());
    }
}