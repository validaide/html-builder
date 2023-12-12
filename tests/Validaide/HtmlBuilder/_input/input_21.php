<?php

use Validaide\HtmlBuilder\HTML;

return function () {

    $tr = HTML::create('tr')
        ->attr('data-id', '1');

    $tdA = HTML::create('td')->text('A');
    $tdB = HTML::create('td')->text('B');
    $tr->tagHTML($tdA);
    $tr->tagHTML($tdB);

    return $tr;
};
