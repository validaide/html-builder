<?php

use Validaide\HtmlBuilder\HTML;

//
return function () {
    return HTML::create('table')->attr('class', 'table table-default')
        ->tag('thead')
        ->tag('tr')
        ->tag('th')->end()
        ->end()
        ->end()
        ->tag('tbody')
        ->tag('tr')
        ->tag('td')->end()
        ->end()
        ->end();
};