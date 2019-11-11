<?php

namespace AdMarketingAPI\Gdt;

use AdMarketingAPI\Kernel\BaseService;
use AdMarketingAPI\Kernel\Exceptions\InvalidArgumentException;

class Gdt extends BaseService
{
    /**
     * Palyload build.
     *
     * @param array       $prepends
     * @return array
     */
    protected function build(array $prepends = []): array
    {
        $palyload = array_replace_recursive($this->all(), $prepends);
        
        
        return $palyload;
    }
}
