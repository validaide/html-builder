<?php

use Validaide\HtmlBuilder\HTML;

return fn() => HTML::create('div')
    ->attr('id', 'my-div')
    ->text('hello')
    ->tag('h1')
    ->attr('style', 'font-style:italic')
    ->text('beauty')
    ->end();
