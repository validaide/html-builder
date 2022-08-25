<?php

use Validaide\HtmlBuilder\HTML;

// name with XSS injection

return function () {
    return HTML::create('<svg onload=alert("XSS_ATTACK_ATTEMPT")>');
};