<?php

use Validaide\HtmlBuilder\HTML;

return fn() => HTML::create('div')
    ->tag('h1')
    ->end();
