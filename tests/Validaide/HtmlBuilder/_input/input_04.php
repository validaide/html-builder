<?php

use Validaide\HtmlBuilder\HTML;

return fn() => HTML::create('h1')->attr('class', 'text-muted')->text('monkey farts');
