<?php

namespace AdMarketingAPI\OceanEngine\Campaign;

use AdMarketingAPI\OceanEngine\OceanEngine;

class Campaign extends OceanEngine
{
    /**
     * 获取广告组.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=153
     *
     * @param int $advertiser_id
     * @param int $page
     * @param int $pageSize
     *
     * @return array
     */
    public function list(int $advertiser_id, int $page = 1, int $pageSize = 10)
    {
        $payload = [
            'advertiser_id' => $advertiser_id,
            'page' => $page,
            'page_size' => $pageSize,
        ];

        return $this->request('open_api/2/campaign/get/', $payload);
    }

    /**
     * 广告组数据.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=187
     *
     * @param int $advertiser_id
     * @param int $page
     * @param int $pageSize
     *
     * @return array
     */
    public function report(int $advertiser_id, int $page = 1, int $pageSize = 20)
    {
        $payload = [
            'advertiser_id' => $advertiser_id,
            'page' => $page,
            'page_size' => $pageSize,
        ];

        return $this->required(['start_date','end_date'])
            ->request('open_api/2/report/campaign/get/', $payload);
    }

    /**
     * 创建广告组.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=51
     *
     * @param array $payload
     *
     * @return array
     */
    public function create(array $payload)
    {
        return $this->required(['advertiser_id','campaign_name'])
            ->request('open_api/2/campaign/create/', $payload, 'POST');
    }

    /**
     * 创建广告组.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=51
     *
     * @param array $payload
     *
     * @return array
     */
    public function update(array $payload)
    {
        return $this->required(['advertiser_id','campaign_id'])
            ->request('open_api/2/campaign/update/', $payload, 'POST');
    }

    /**
     * 广告组更新状态.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=54
     *
     * @param int $advertiser_id
     * @param array $campaign_ids
     * @param string $opt_status
     *
     * @return array
     */
    public function status(int $advertiser_id, array $campaign_ids, string $opt_status)
    {
        $payload = [
            'advertiser_id' => $advertiser_id,
            'campaign_ids' => $campaign_ids,
            'opt_status' => $opt_status,
        ];
        return $this->required(['advertiser_id','campaign_ids', 'opt_status'])
            ->request('open_api/2/campaign/update/status/', $payload, 'POST');
    }
}
