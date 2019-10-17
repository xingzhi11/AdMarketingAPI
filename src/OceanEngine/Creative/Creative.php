<?php

namespace AdMarketingAPI\OceanEngine\Ad;

use AdMarketingAPI\OceanEngine\OceanEngine;

class Creative extends OceanEngine
{
    /**
     * 获取创意列表（新版）.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=263
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

        return $this->request('open_api/2/creative/get/', $payload);
    }

    /**
     * 创意详细信息（新版）.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=144
     *
     * @param int $advertiser_id
     * @param int $ad_id
     *
     * @return array
     */
    public function read_v2(int $advertiser_id, int $ad_id)
    {
        $payload = [
            'advertiser_id' => $advertiser_id,
            'ad_id' => $ad_id,
        ];

        return $this->request('open_api/2/creative/read_v2/', $payload);
    }

    /**
     * 广告创意数据（新版）.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=189
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
            ->request('open_api/2/report/creative/get/', $payload);
    }

    /**
     * 创建广告创意（新版）.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=143
     *
     * @param array $payload
     *
     * @return array
     */
    public function create(array $payload)
    {
        $fields = [
            'advertiser_id','ad_id','creatives','source','inventory_type'
        ];
        return $this->required($fields)
            ->request('open_api/2/creative/create_v2/', $payload, 'POST');
    }

    /**
     * 修改创意信息（新版）.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=145
     *
     * @param array $payload
     *
     * @return array
     */
    public function update(array $payload)
    {
        return $this->required(['advertiser_id','ad_id', 'modify_time'])
            ->request('open_api/2/creative/update_v2/', $payload, 'POST');
    }

    /**
     * 更新创意状态.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=68
     *
     * @param int $advertiser_id
     * @param array $creative_ids
     * @param string $opt_status
     *
     * @return array
     */
    public function status(int $advertiser_id, array $creative_ids, string $opt_status)
    {
        $payload = [
            'advertiser_id' => $advertiser_id,
            'creative_ids' => $creative_ids,
            'opt_status' => $opt_status,
        ];
        return $this->required(['advertiser_id','creative_ids', 'opt_status'])
            ->request('open_api/2/creative/update/status/', $payload, 'POST');
    }

    /**
     * 创意素材信息.
     *
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=69
     *
     * @param int $advertiser_id
     * @param array $creative_ids
     *
     * @return array
     */
    public function material(int $advertiser_id, array $creative_ids)
    {
        $payload = [
            'advertiser_id' => $advertiser_id,
            'creative_ids' => $creative_ids,
        ];
        return $this->required(['advertiser_id','creative_ids'])
            ->request('open_api/2/creative/material/read/', $payload);
    }
}
