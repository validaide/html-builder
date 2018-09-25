<?php

use Validaide\HtmlBuilder\HTML;

//
return function () {
    return HTML::create('div')
        ->tag('h1')
        ->end();
};