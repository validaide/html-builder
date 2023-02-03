<?php

use Validaide\HtmlBuilder\HTML;

// class with XSS injection

return fn() => HTML::create('div')
    ->class("\"><script>alert('XSS')</script>");
