<?php

namespace SynergiTech\WeasyPrint\Facades;

use Illuminate\Support\Facades\Facade;

class WeasyPrint extends Facade
{
    /**
     * [getFacadeAccessor description]
     * @return [type] [description]
     */
    protected static function getFacadeAccessor()
    {
        return 'synergitech.weasyprint';
    }
}
