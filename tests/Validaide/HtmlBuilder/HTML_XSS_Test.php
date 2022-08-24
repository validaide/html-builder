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
     * @param string $inputCommandFilePath
     * @param string $outputFilePath
     *
     * @group        grain
     */
    public function testOutputs(string $inputCommandFilePath, string $outputFilePath)
    {
        $code = require $inputCommandFilePath;

        $html = call_user_func($code);
        $this->assertEqualsToHtmlFile($html, $outputFilePath);
    }

    public function testSomething()
    {
        $a = HTML::create('svg onload=alert("XSS_ATTAAACK_ATTEMPT")');
        //$a = HTML::create('div')

    //            ->text('Hi there <svg onload=alert("XSS_ATTACK_ATTEMPT")>', false)
    //            ->title('<svg onload=alert("XSS_ATTACK_ATTEMPT")>')
    //            ->titleAppend('With Extra content');

        $fh =fopen("/tmp/example_xss.html", "w+");
        fwrite($fh, $a->html());
        fclose($fh);

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
            glob($baseDir . '/_input_xss/input_*_X.php'),
            glob($baseDir . '/_output_xss/output_*_X.html'),
        );
    }

    /**
     * @param HTML   $html
     * @param string $outputFilePathFlat
     */
    protected function assertEqualsToHtmlFile(HTML $html, string $outputFilePathFlat)
    {
        $this->assertEquals(file_get_contents($outputFilePathFlat), $html->html());
        // $this->assertEquals(file_get_contents($outputFilePathPretty), $html->html(true));
    }
}