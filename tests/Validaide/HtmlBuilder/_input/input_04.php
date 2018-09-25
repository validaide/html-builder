<?php

use Validaide\HtmlBuilder\HTML;

//
return function () {
    return HTML::create('h1')->arg('class', 'text-muted')->text('monkey farts');
};