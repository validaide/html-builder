<?php

use Validaide\HtmlBuilder\HTML;

//
return function () {

    $appendHTML = HTML::create('small')
        ->id('test-id')
        ->text("Append Information");

    return HTML::create(HTML::SPAN)
        ->text("span-0")
        ->append($appendHTML)
        ->end();
};
