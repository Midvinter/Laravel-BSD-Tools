<?php

namespace JoelESvensson\LaravelBsdTools\Api;

use Blue\Tools\Api\DeferredException;
use InvalidArgumentException;
use JoelESvensson\LaravelBsdTools\Api\Client as ApiClient;

class Constituent
{

    /**
     * @var ApiClient
     */
    private $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    private function byEmail(array $params)
    {
        if (is_array($params['emails'])) {
            $params['emails'] = implode(',', $params['emails']);
        }

        return $this->api->get('cons/get_constituents_by_email', $params);
    }

    private function byId(array $params)
    {
        if (is_array($params['cons_ids'])) {
            $params['cons_ids'] = implode(',', $params['cons_ids']);
        }

        return $this->api->get('cons/get_constituents_by_id', $params);
    }

    /**
     * array|string|int $consIds
     */
    public function delete($consIds)
    {
        if (is_string($consIds)) {
            $consIds = (int) $consIds;
        }

        if (is_int($consIds)) {
            $consIds = [$consIds];
        }

        if (!is_array($consIds)) {
            throw new InvalidArgumentException();
        }

        try {
            $response = $this->api->post(
                'cons/delete_constituents_by_id',
                [
                    'cons_ids' => implode(',', $consIds),
                ],
                '',
                false
            );
        } catch (DeferredException $e) {
            $response = $e->getDeferredId();
        }

        return $response;
    }

    /**
     * @param array|string|int $params May either take all params as an array,
     * the email as a string or the id as an int
     */
    public function get($params, array $options = null)
    {
        if (is_string($params)) {
            $params = ['emails' => $params];
        } elseif (is_int($params)) {
            $params = ['cons_ids' => $param];
        } elseif (!is_array($params)) {
            throw new InvalidArgumentException();
        }

        if ($options) {
            $params = array_merge($params, $options);
            if (isset($params['bundles'])) {
                if (is_array($options['bundles'])) {
                    $options['bundles'] = implode(',', $options['bundles']);
                }
            }
        }

        if (isset($params['emails'])) {
            return $this->byEmail($params);
        } elseif (isset($params['cons_ids'])) {
            return $this->byIds($params);
        }
    }
}
