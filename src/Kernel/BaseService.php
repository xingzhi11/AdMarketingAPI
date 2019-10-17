<?php

namespace AdMarketingAPI\Kernel;

use AdMarketingAPI\Kernel\Supports\Traits\HasAttributes;
use AdMarketingAPI\Kernel\Exceptions\InvalidArgumentException;

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
        return $this->required = $required;
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

    public function groupBy()
    {
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

    abstract protected function build(array $prepends = []): array;
}
