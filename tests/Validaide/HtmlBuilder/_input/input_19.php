<?php

use Validaide\HtmlBuilder\HTML;

return function () {

    $prependHTML = HTML::create('small')
        ->id('test-id')
        ->text("Prepend Information");

    return HTML::create(HTML::SPAN)
        ->text("span-0")
        ->prepend($prependHTML)
        ->end();
};
