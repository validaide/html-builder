<?php

use Validaide\HtmlBuilder\HTML;

/**
 * Attempt to do XSS
 */
return fn() => HTML::create('p')
    ->text("<script type='application/javascript'>alert('hacked!');</script>");
