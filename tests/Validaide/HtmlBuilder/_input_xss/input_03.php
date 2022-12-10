<?php

use Validaide\HtmlBuilder\HTML;

// attribute with XSS injection

return fn() => HTML::create('img')
    ->attr('onmouseover', "alert('XSS')")
    ->attr('src', '#');
