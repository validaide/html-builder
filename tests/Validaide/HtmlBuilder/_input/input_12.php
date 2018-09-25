<?php

use Validaide\HtmlBuilder\HTML;

/**
 * Attempt to do XSS
 */
return function () {
    return HTML::create('p')
        ->text("<script type='application/javascript'>alert('hacked!');</script>");
};