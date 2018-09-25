<?php

use Validaide\HtmlBuilder\HTML;

/**
 * Attempt to do script injection
 */
return function () {
    return HTML::create('p')
        ->text("<script type='application/javascript'>alert('hacked!');</script>");
};