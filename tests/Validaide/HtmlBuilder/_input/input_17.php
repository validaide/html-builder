<?php

use Validaide\HtmlBuilder\HTML;

//
return function () {
    return HTML::create(HTML::SPAN)
        ->title('My Title')
        ->titleAppend('With Extra content');
};