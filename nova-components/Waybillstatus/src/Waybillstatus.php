<?php

namespace Wasandev\Waybillstatus;

use Laravel\Nova\Card;

class Waybillstatus extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'waybillstatus';
    }
    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'waybillstatus';
    }
    public function name()
    {
        return 'สถานะใบกำกับ';
    }
}
