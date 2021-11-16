<?php

use Validaide\HtmlBuilder\HTML;

return function () {
    return HTML::create('a')
        ->dataToggle('tooltip', 'bottom')
        ->style('height: 6px;')
        ->title('someTitle')
        ->href('link');
};