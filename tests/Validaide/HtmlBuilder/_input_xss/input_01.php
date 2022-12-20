<?php

use Validaide\HtmlBuilder\HTML;

// text() with XSS injection
return fn() => HTML::create(HTML::SPAN)
    ->text('<svg onload=alert("XSS_ATTACK_ATTEMPT")>');
