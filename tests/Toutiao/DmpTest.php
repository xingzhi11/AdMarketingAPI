<?php

namespace AdMarketingAPI\Tests\Toutiao;

use AdMarketingAPI\Tests\OceanEngineTest;

class DmpTest extends OceanEngineTest
{
    public function testDataSourceCreate()
    {
        $app = $this->app()->dmp;

        $result = $app->dataSourceCreate(1649531590784077, [
            'data_source_name' => '1至7天零单人群包数据源',
            'description' => '1至7天零单人群包数据源',
            'data_format' => 0,
            'file_storage_type' => 0,
            'file_paths' => [
                "1649531590784077-2786c528c5a95e5e82a40f4efb5f54e8"
            ]
        ]);

        dump($result);

        die;
    }

    public function testDataSourceUpload()
    {
        $app = $this->app()->dmp;

        $dataSoureFilePath = __DIR__.'/../../resource/dmp/datasource/no_order_silence_one_to_seven_days.txt';
        // dump($dataSoureFilePath);

        $result = $app->dataSourceUpload(1649531590784077, $dataSoureFilePath);
        dump($result);die;
    }

    
}