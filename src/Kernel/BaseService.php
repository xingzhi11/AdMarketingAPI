<?php

namespace AdMarketingAPI\Kernel;

use AdMarketingAPI\Kernel\Supports\Traits\HasAttributes;
use AdMarketingAPI\Kernel\Exceptions\InvalidArgumentException;
use AdMarketingAPI\Kernel\Exceptions\HttpException;

abstract class BaseService
{
    use HasAttributes;

    /**
     * @var \AdMarketingAPI\Kernel\ServiceContainer
     */
    protected $app;

    /**
     * @var BaseClient
     */
    protected $client;

    /**
     * BaseClient constructor.
     *
     * @param \AdMarketingAPI\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
        $this->client = new BaseClient($app);
    }

    public function getClient()
    {
        return  $this->client;
    }

    public function required(array $required = [])
    {
        $this->required = $required;
        return $this;
    }

    /**
     * 查询字段集合.
     *
     * @param array $fields
     *
     * @return
     */
    public function fields(array $fields = [])
    {
        $this->fields = $fields;

        return $this;
    }

    public function dateRange(string $start_date, string $end_date, int $max_step = 0)
    {
        if (!Supports\validate_date_format($start_date, "Y-m-d") || !Supports\validate_date_format($end_date)) {
            throw new InvalidArgumentException("format of start_date and end_date must be YYYY-MM-DD.");
        }

        $todayDate = date('Y-m-d');
        $todayDateTime = strtotime("{$todayDate} 00:00:00");
        $startDateTime = strtotime("{$start_date} 00:00:00");
        $endDateTime = strtotime("{$end_date} 00:00:00");

        if ($endDateTime < $startDateTime) {
            throw new InvalidArgumentException("end_date must be less than or equal to start_date.");
        }

        if ($max_step > 0 && ($endDateTime-$startDateTime)/86400 > $max_step) {
            throw new InvalidArgumentException("The maximum time span supported is {$max_step} day.");
        }
 
        if ( $startDateTime > $todayDateTime || $endDateTime > $todayDateTime) {
            throw new InvalidArgumentException("start_date and end_date must be less than or equal to today.");
        }

        $this->start_date = $start_date;
        $this->end_date = $end_date;

        return $this;
    }

    public function filter(array $filter = [])
    {
        $this->filtering = $filter;

        return $this;
    }

    public function groupBy(array $group = [])
    {
        $this->group_by = $group;

        return $this;
    }

    /**
     * do api request.
     *
     * @param string $endpoint
     * @param array  $prepends
     * @param string $method
     *
     * @return array
     */
    public function request(string $endpoint, array $prepends = [], string $method = 'GET')
    {
        $this->required(array_keys($prepends));
        $palyload = $this->build($prepends);
        unset($palyload['required']);

        return 'GET' === $method ? $this->client->httpGet($endpoint, $palyload) :
            $this->client->httpPostJson($endpoint, $palyload);
    }

    public function curlFile($url, $token, $postData)
    {
        $headers = [
            "Access-Token:{$token}"
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $output = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($output, true);
        if (!isset($result['code']) || $result['code'] != 0) {
            throw new HttpException(
                "Request [{$url}] fail:".json_encode($result,JSON_UNESCAPED_UNICODE),
                null,
                $result
            );
        }
        return $result['data'];
    }

    abstract protected function build(array $prepends = []): array;
}
