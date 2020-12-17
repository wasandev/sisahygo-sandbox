<?php

namespace Wasandev\Orderstatus;

use Laravel\Nova\Card;

class Orderstatus extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'orderstatus';
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'orderstatus';
    }
    public function name()
    {
        return 'ความหมายของสถานะ';
    }
}
