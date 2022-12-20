<?php

use Validaide\HtmlBuilder\HTML;

return fn() => HTML::create('table')->attr('class', 'table table-default')
    ->tag('thead')
    ->tag('tr')
    ->tag('th')
    ->text('Company Name')
    ->end()
    ->end()
    ->end()
    ->tag('tbody')
    ->tag('tr')
    ->tag('td')
    ->text('Validaide B.V.')
    ->end()
    ->end()
    ->end();
