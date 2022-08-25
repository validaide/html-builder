<?php

use Validaide\HtmlBuilder\HTML;

// class with XSS injection

return function () {
    return HTML::create('div')
        ->class("\"><script>alert('XSS')</script>")
        ;
};