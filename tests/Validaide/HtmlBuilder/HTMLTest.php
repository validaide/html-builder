<?php

namespace Tests\Validaide\HtmlBuilder;

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

    /*****************************************************************************/
    /* Helpers
    /*****************************************************************************/

    /**
     * @return array
     */
    public function inputCommandToOutputFilesProvider(): array
    {
        $baseDir = __DIR__;
//$number = '11';
//        return [
//            [$baseDir . '/_input/input_'.$number.'.php',
//            $baseDir . '/_output/output_'.$number.'_flat.html',
//            $baseDir . '/_output/output_'.$number.'_pretty.html']
//        ];

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