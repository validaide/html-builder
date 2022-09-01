<?php

use Validaide\HtmlBuilder\HTML;

return function () {
    $ul = HTML::create(HTML::LIST);
    $li = HTML::create('li', $ul)->text('FWD1');
    $ul->tagHTML($li);

    $li = HTML::create('li', $ul)->text('FWD2');
    $ul->tagHTML($li);

    return $ul;
};
