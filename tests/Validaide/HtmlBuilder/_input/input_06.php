<?php

use Validaide\HtmlBuilder\HTML;

//
return function () {
    return HTML::create('h1')
        ->arg('id', 'my-id')
        ->arg('class', 'text-muted')
        ->arg('data-content', 'some_cool_content');
};