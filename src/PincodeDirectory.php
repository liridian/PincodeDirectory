<?php

namespace Liridian;

use GuzzleHttp\Client;

class PincodeDirectory
{

    private $client;

    public $api_key;

    private $url = 'https://data.gov.in/api/datastore/resource.json?resource_id=0a076478-3fd3-4e2c-b2d2-581876f56d77';

    private $params;

    private static $available_filters = array(
        'officename',
        'pincode',
        'divisionname',
        'regionname',
        'DistrictName',
        'statename',
    );

    private static $available_fields = array(
        'officename',
        'pincode',
        'divisionname',
        'regionname',
        'DistrictName',
        'statename',
    );

    public function __construct()
    {
        $this->client = new Client([
            'base_url' => $this->url
        ]);
    }

    public function withApiKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    public function withFilter(array $filters = array())
    {
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                if (in_array(key($filter), self::$available_filters)) {
                    $filter_index = 'filters['.key($filter).']';
                    $this->params['query'][$filter_index] = array_pop($filter);
                } else {
                    throw new \InvalidArgumentException("Invalid filter provided");
                }
            }
        }
        return $this;
    }

    public function withFields(array $fields = array())
    {
        if (!empty($fields) && in_array('*', $fields)) {
            $this->params['query']['fields'] = implode(',', self::$available_fields);
        } else if (!empty($fields)) {
            foreach ($fields as $field) {
                if (in_array($field, self::$available_fields)) {
                    continue;
                } else {
                    throw new \InvalidArgumentException("Invalid select fields provided");
                }
            }
            $select_fields = implode(',', $fields);
            $this->params['query']['fields'] = $select_fields;
        } else {
            $this->params['query']['fields'] = implode(',', self::$available_fields);
        }
        return $this;
    }

    public function withOffset($offset = 0)
    {
        $this->params['query']['offset'] = $offset;
        return $this;
    }

    public function withLimit($limit = 100)
    {
        $this->params['query']['limit'] = $limit;
        return $this;
    }

    public function withSort(array $sort_by = array())
    {
        if (!empty($sort_by)) {
            foreach ($sort_by as $sidx) {
                if (in_array(key($sidx), self::$available_fields)) {
                    $sort_index = 'sort['.key($sidx).']';
                    $this->params['query'][$sort_index] = array_pop($sidx);
                } else {
                    throw new \InvalidArgumentException("Invalid sort index provided");
                }
            }
        }
        return $this;
    }

    public function get()
    {
        $pincodes = $this->restCall();

        if ($pincodes['success']) {
            $response['count']   = $pincodes['body']['count'];
            $response['records'] = $pincodes['body']['records'];
        } else {
            $response['status_code'] = $pincodes['status_code'];
            $response['message'] = $pincodes['message'];
        }

        return $response;
    }

    private function restCall()
    {
        if (!isset($this->params['query']['api-key'])) {
            $this->params['query']['api-key'] = $this->api_key;
        }

        if (!isset($this->params['query']['fields'])) {
            $this->withFields(['*']);
        }

        try {
            $request = $this->client->createRequest('GET', $this->url, $this->params);
            $response = $this->client->send($request);
            $body = $response->getBody();
            return ['success' => true, 'body' => json_decode($body, true)];
        } catch (\Exception $e) {
            return ['success' => false, 'status_code' => $e->getCode(), 'message' => $e->getMessage()];
        }
    }

    public function getAvailableFilters()
    {
        return self::$available_filters;
    }

    public function getAvailableSelectFields()
    {
        return self::$available_fields;
    }

    public function getParams()
    {
        return $this->params;
    }
}
