<?php

namespace Wasandev\InputThaiAddress;

use Laravel\Nova\ResourceTool;

class InputThaiAddress extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Input Thai Address';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'input-thai-address';
    }
}
