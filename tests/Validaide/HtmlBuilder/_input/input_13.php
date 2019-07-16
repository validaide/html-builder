<?php

use Validaide\HtmlBuilder\HTML;

/**
 * Output Text raw
 */
return function () {
    $htmlDiv = HTML::create('div')
        ->attr('data-my-id', 'my-id');

    for($count = 0; $count < 5; ++$count) {
        $htmlSpan = HTML::create('span')
            ->class('text-bold')
            ->text($count);

        $htmlDiv->text($htmlSpan, true);
    }

    return $htmlDiv;
};