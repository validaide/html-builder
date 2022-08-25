<?php

use Validaide\HtmlBuilder\HTML;

// attribute with XSS injection

return function () {
    return HTML::create('img')
        ->attr('onmouseover', "alert('XSS')")
        ->attr('src', '#');
};