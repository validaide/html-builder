<?php

use Validaide\HtmlBuilder\HTML;

return fn() => HTML::create('div')->attr('id', 'div-1')
    ->tag('div')->attr('id', 'div-2a')
    ->tag('div')->attr('id', 'div-3')->end()
    ->end()
    ->tag('div')->attr('id', 'div-2b')
    ->tag('div')->attr('id', 'div-4')->end()
    ->end();
