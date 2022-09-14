<?php

use Validaide\HtmlBuilder\HTML;

// text() with XSS injection
return function () {
    return HTML::create(HTML::SPAN)
        ->text('<svg onload=alert("XSS_ATTACK_ATTEMPT")>');
};