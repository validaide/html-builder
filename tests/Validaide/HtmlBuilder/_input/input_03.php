<?php

use Validaide\HtmlBuilder\HTML;

return function () {
    return HTML::create('h1')->attr('class', 'text-muted');
};