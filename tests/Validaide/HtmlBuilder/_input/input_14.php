<?php

use Validaide\HtmlBuilder\HTML;

return fn() => HTML::create('a')
    ->dataToggle('tooltip', 'bottom')
    ->style('height: 6px;')
    ->title('someTitle')
    ->href('https://localhost');
