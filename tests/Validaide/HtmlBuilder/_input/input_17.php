<?php

use Validaide\HtmlBuilder\HTML;

return function () {
    return HTML::create(HTML::SPAN)
        ->text('Hi there')
        ->title('My Title')
        ->titleAppend('With Extra content');
};