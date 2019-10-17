<?php

namespace AdMarketingAPI\OceanEngine;

use AdMarketingAPI\Kernel\BaseService;
use AdMarketingAPI\Kernel\Exceptions\InvalidArgumentException;

class OceanEngine extends BaseService
{
    public function order()
    {
        
    }

    /**
     * Palyload build.
     *
     * @param array       $prepends
     * @return array
     */
    protected function build(array $prepends = []): array
    {
        $palyload = array_replace_recursive($this->all(), $prepends);
        
        if (isset($palyload['advertiser_id'])) {
            $this->app['config']->set('account_id', $palyload['advertiser_id']);
        }
        if (isset($palyload['advertiser_ids'])) {
            if (count($palyload['advertiser_ids']) > 100) {
                throw new InvalidArgumentException("The advertiser_ids may not have more than 100 items");
            }
            $this->app['config']->set('account_id', $palyload['advertiser_ids'][0]);
        }

        if (isset($palyload['filtering'])) {
            $palyload['filtering'] = json_encode($palyload['filtering']);
        }
        if (isset($palyload['fields'])) {
            $palyload['fields'] = json_encode($palyload['fields']);
        }
        if (isset($palyload['group_by'])) {
            $palyload['group_by'] = json_encode($palyload['group_by']);
        }
        
        return $palyload;
    }
}
