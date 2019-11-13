<?php

namespace AdMarketingAPI\OceanEngine\Dmp;

use AdMarketingAPI\OceanEngine\OceanEngine;

class Dmp extends OceanEngine
{
    /**
     * 数据源文件上传
     * 
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=73
     * 
     * @param int $advertiser_id
     * @param string $path
     * @param string $file_signature
     * 
     * @return array
     */
    public function dataSourceUpload(int $advertiser_id, string $path, string $file_signature = "")
    {
        $payload = [
            'advertiser_id' => $advertiser_id,
            'file' => new \CURLFile($path, null, null)
        ];
        $this->app['config']->set('account_id', $advertiser_id);
        $token = $this->app['oauth']->getToken()['access_token'];
        
        return $this->curlFile("https://ad.oceanengine.com/open_api/2/dmp/data_source/file/upload/", $token, $payload);
    }

    /**
     * 数据源创建
     * 
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=74
     * 
     * @param int $advertiser_id
     * @param array $payload
     * 
     * @return array
     */
    public function dataSourceCreate(int $advertiser_id, array $payload)
    {
        $payload['advertiser_id'] = $advertiser_id;
        return $this->required([
                'advertiser_id','data_source_name','description','data_format',
                'file_storage_type','file_paths'
            ])
            ->request('open_api/2/dmp/data_source/create/', $payload, "POST");
    }

     /**
     * 数据源更新
     * 
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=75
     * 
     * @param int $advertiser_id
     * @param string $data_source_id
     * @param array $payload
     * 
     * @return array
     */
    public function dataSourceUpdate(int $advertiser_id, string $data_source_id, array $payload)
    {
        $payload['advertiser_id'] = $advertiser_id;
        $payload['data_source_id'] = $data_source_id;
        return $this->required([
                'advertiser_id','data_source_id','operation_type','data_format',
                'file_storage_type','file_paths'
            ])
            ->request('open_api/2/dmp/data_source/update/', $payload, "POST");
    }

    /**
     * 数据源更新
     * 
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=77
     * 
     * @param int $advertiser_id
     * @param array $data_source_ids
     * 
     * @return array
     */
    public function dataSourceRead(int $advertiser_id, array $data_source_ids)
    {
        $payload = [    
            'advertiser_id' => $advertiser_id,
            'data_source_id_list' => $data_source_ids
        ];
        return $this->request('open_api/2/dmp/data_source/read/', $payload);
    }

    /**
     * 人群包列表
     * 
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=76
     * 
     * @param int $advertiser_id
     * @param int $select_type
     * 
     * @return array
     */
    public function audiences(int $advertiser_id, int $select_type = 1)
    {
        $payload = [    
            'advertiser_id' => $advertiser_id,
            'select_type' => $select_type
        ];
        return $this->request('open_api/2/dmp/custom_audience/select/', $payload);
    }

    /**
     * 人群包详细信息
     * 
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=242
     * 
     * @param int $advertiser_id
     * @param array $custom_audience_ids
     * 
     * @return array
     */
    public function audienceRead(int $advertiser_id, array $custom_audience_ids = [])
    {
        $payload = [    
            'advertiser_id' => $advertiser_id,
            'custom_audience_ids' => $custom_audience_ids
        ];
        return $this->request('open_api/2/dmp/custom_audience/read/', $payload);
    }
    
    /**
     * 发布人群包（新版）
     * 
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=238
     * 
     * @param int $advertiser_id
     * @param int $custom_audience_id
     * 
     * @return array
     */
    public function publishCustomAudience(int $advertiser_id, int $custom_audience_id)
    {
        $payload = [    
            'advertiser_id' => $advertiser_id,
            'custom_audience_id' => $custom_audience_id
        ];
        return $this->request('open_api/2/dmp/custom_audience/publish/', $payload, "POST");
    }

    /**
     * 推送人群包（新版）
     * 
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=237
     * 
     * @param int $advertiser_id
     * @param int $audience_id
     * @param array $target
     * 
     * @return array
     */
    public function pushCustomAudience(int $advertiser_id, int $audience_id, array $target = [])
    {
        $payload = [    
            'advertiser_id' => $advertiser_id,
            'custom_audience_id' => $audience_id,
            'target_advertiser_ids' => $target
        ];
        return $this->request('open_api/2/dmp/custom_audience/push_v2/', $payload, "POST");
    }

    /**
     * 删除人群包
     * 
     * @see https://ad.oceanengine.com/openapi/doc/index.html?id=79
     * 
     * @param int $advertiser_id
     * @param int $audience_id
     * 
     * @return array
     */
    public function deleteCustomAudience(int $advertiser_id, int $audience_id)
    {
        $payload = [    
            'advertiser_id' => $advertiser_id,
            'custom_audience_id' => $audience_id,
        ];
        return $this->request('open_api/2/dmp/custom_audience/delete/', $payload, "POST");
    }
}
