<?php

namespace AdMarketingAPI\OceanEngine;

use AdMarketingAPI\Kernel\BaseService;
use AdMarketingAPI\Kernel\Exceptions\InvalidArgumentException;

class OceanEngine extends BaseService
{
    /**
     * Palyload build.
     *
     * @param array $prepends
     *
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
                throw new InvalidArgumentException('The advertiser_ids may not have more than 100 items');
            }
            $this->app['config']->set('account_id', $palyload['advertiser_ids'][0]);
        }

        if (!empty($palyload['creative_ids'])) {
            if (count($palyload['creative_ids']) > 100) {
                throw new InvalidArgumentException('The creative_ids may not have more than 100 items');
            }
        }

        if (!empty($palyload['campaign_ids'])) {
            if (count($palyload['campaign_ids']) > 100) {
                throw new InvalidArgumentException('The campaign_ids may not have more than 100 items');
            }
        }

        if (!empty($palyload['ad_ids'])) {
            if (count($palyload['ad_ids']) > 100) {
                throw new InvalidArgumentException('The ad_ids may not have more than 100 items');
            }
        }
        foreach ($palyload as $field => $value) {
            if (is_array($value)) {
                $palyload[$field] = json_encode($value);
            }
        }

        return $palyload;
    }
}
