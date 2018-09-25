<?php

use Validaide\HtmlBuilder\HTML;

//
return function () {
    return HTML::create('div')
        ->arg('id', 'my-div')
        ->text('hello')
        ->tag('h1')
        ->arg('style', 'font-style: italic')
        ->text('beauty')
        ->end();
};