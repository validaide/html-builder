<?php

use Validaide\HtmlBuilder\HTML;

return function () {
    return HTML::create('div')
        ->class(['class2','class3', 'class4'])
        ->classPrepend('class1')
        ->classAppend(['class5', 'class6']);
};