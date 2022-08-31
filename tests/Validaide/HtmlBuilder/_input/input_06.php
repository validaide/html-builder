<?php

use Validaide\HtmlBuilder\HTML;

return function () {
    return HTML::create('h1')
        ->attr('id', 'my-id')
        ->attr('class', 'text-muted')
        ->attr('data-content', 'some_cool_content');
};