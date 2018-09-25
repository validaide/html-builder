<?php

use Validaide\HtmlBuilder\HTML;

//
return function () {
    return HTML::create('div')->arg('id', 'div-1')
        ->tag('div')->arg('id', 'div-2a')
        ->tag('div')->arg('id', 'div-3')->end()
        ->end()
        ->tag('div')->arg('id', 'div-2b')
        ->tag('div')->arg('id', 'div-4')->end()
        ->end();
};