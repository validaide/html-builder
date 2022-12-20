<?php

use Validaide\HtmlBuilder\HTML;

return fn() => HTML::create(HTML::LIST)
   ->list(['one', 'two', 'three']);
