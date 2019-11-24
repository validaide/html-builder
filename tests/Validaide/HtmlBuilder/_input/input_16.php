<?php

use Validaide\HtmlBuilder\HTML;

//
return function () {
    return HTML::create(HTML::LIST)
       ->list(['one', 'two', 'three']);
};