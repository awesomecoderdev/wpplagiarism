<?php

namespace AwesomeCoder\Plugin\RestApi\EndPoints\Settings;

use AwesomeCoder\Plagiarism\Config\ConfigSet;
use AwesomeCoder\Plugin\RestApi\EndPoints\AbstractEndpointHandler;
use WP_REST_Request;
use WP_REST_Response;

/**
 * The handler for the endpoint that provides settings values.
 *
 * @since 0.1
 */
class GetSettingsEndpoint extends AbstractEndpointHandler
{
    /**
     * @since 0.1
     *
     * @var ConfigSet
     */
    protected $config;

    /**
     * Constructor.
     *
     * @since 0.1
     *
     * @param ConfigSet $config The config set.
     */
    public function __construct(ConfigSet $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     *
     * @since 0.1
     */
    protected function handle(WP_REST_Request $request)
    {
        return new WP_REST_Response($this->config->getValues());
    }
}
