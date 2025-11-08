<?php

namespace App\Base;

use App\Base\View;


class ViewFactory
{
    public function make(string $template): View
    {
        return new View($template);
    }

}
