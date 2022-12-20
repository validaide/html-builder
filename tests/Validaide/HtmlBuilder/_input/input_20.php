<?php

use Validaide\HtmlBuilder\HTML;

return function () {

    $a = HTML::create('a')
        ->attr('href', '#');

    $small = HTML::create('small')->id('test_id')->text('Small Text');
    $a->tagHTML($small);

    $a->text('span-0');

    return $a;
};
