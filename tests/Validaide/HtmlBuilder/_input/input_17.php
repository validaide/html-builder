<?php

use Validaide\HtmlBuilder\HTML;

return fn() => HTML::create(HTML::SPAN)
    ->text('Hi there')
    ->title('My Title')
    ->titleAppend('With Extra content');
