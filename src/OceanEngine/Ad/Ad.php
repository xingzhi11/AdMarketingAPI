<?php

namespace AdMarketingAPI\OceanEngine\Ad;

use AdMarketingAPI\OceanEngine\OceanEngine;

class Ad extends OceanEngine
{
    /**
     * 获取广告计划（新版）.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=154
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

        return $this->request('open_api/2/ad/get/', $payload);
    }

    /**
     * 广告计划数据（新版）.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=188
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
            ->request('open_api/2/report/ad/get/', $payload);
    }

    /**
     * 创建广告计划.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=57
     *
     * @param array $payload
     *
     * @return array
     */
    public function create(array $payload)
    {
        $fields = [
            'advertiser_id','campaign_id','delivery_range','budget_mode','budget',
            'start_time', 'end_time', 'bid', 'pricing', 'schedule_type','schedule_time',
            'name'
        ];
        return $this->required($fields)
            ->request('open_api/2/ad/create/', $payload, 'POST');
    }

    /**
     * 修改广告计划.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=59
     *
     * @param array $payload
     *
     * @return array
     */
    public function update(array $payload)
    {
        return $this->required(['advertiser_id','ad_id', 'modify_time'])
            ->request('open_api/2/ad/update/', $payload, 'POST');
    }

    /**
     * 更新计划状态.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=60
     *
     * @param int $advertiser_id
     * @param array $ad_ids
     * @param string $opt_status
     *
     * @return array
     */
    public function status(int $advertiser_id, array $ad_ids, string $opt_status)
    {
        $payload = [
            'advertiser_id' => $advertiser_id,
            'ad_ids' => $ad_ids,
            'opt_status' => $opt_status,
        ];
        return $this->required(['advertiser_id','ad_ids', 'opt_status'])
            ->request('open_api/2/ad/update/status/', $payload, 'POST');
    }
}
