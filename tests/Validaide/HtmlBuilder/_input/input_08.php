<?php

use Validaide\HtmlBuilder\HTML;

return function () {
    return HTML::create('div')
        ->attr('id', 'my-div')
        ->tag('h1')
        ->attr('style', 'font-style:italic')
        ->end();
};