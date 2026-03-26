<?php

namespace Symfony\Config\ApiPlatform;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class ElasticsearchConfig 
{
    private $enabled;
    private $hosts;
    private $sslCaBundle;
    private $sslVerification;
    private $client;
    private $_usedProperties = [];

    /**
     * @default false
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function enabled($value): static
    {
        $this->_usedProperties['enabled'] = true;
        $this->enabled = $value;

        return $this;
    }

    /**
     * @param ParamConfigurator|list<ParamConfigurator|mixed>|mixed $value
     *
     * @return $this
     */
    public function hosts(mixed $value): static
    {
        $this->_usedProperties['hosts'] = true;
        $this->hosts = $value;

        return $this;
    }

    /**
     * Path to the SSL CA bundle file for Elasticsearch SSL verification.
     * @default null
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function sslCaBundle($value): static
    {
        $this->_usedProperties['sslCaBundle'] = true;
        $this->sslCaBundle = $value;

        return $this;
    }

    /**
     * Enable or disable SSL verification for Elasticsearch connections.
     * @default true
     * @param ParamConfigurator|bool $value
     * @return $this
     */
    public function sslVerification($value): static
    {
        $this->_usedProperties['sslVerification'] = true;
        $this->sslVerification = $value;

        return $this;
    }

    /**
     * The search engine client to use: "elasticsearch" or "opensearch".
     * @default 'elasticsearch'
     * @param ParamConfigurator|'elasticsearch'|'opensearch' $value
     * @return $this
     */
    public function client($value): static
    {
        $this->_usedProperties['client'] = true;
        $this->client = $value;

        return $this;
    }

    public function __construct(array $config = [])
    {
        if (array_key_exists('enabled', $config)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $config['enabled'];
            unset($config['enabled']);
        }

        if (array_key_exists('hosts', $config)) {
            $this->_usedProperties['hosts'] = true;
            $this->hosts = $config['hosts'];
            unset($config['hosts']);
        }

        if (array_key_exists('ssl_ca_bundle', $config)) {
            $this->_usedProperties['sslCaBundle'] = true;
            $this->sslCaBundle = $config['ssl_ca_bundle'];
            unset($config['ssl_ca_bundle']);
        }

        if (array_key_exists('ssl_verification', $config)) {
            $this->_usedProperties['sslVerification'] = true;
            $this->sslVerification = $config['ssl_verification'];
            unset($config['ssl_verification']);
        }

        if (array_key_exists('client', $config)) {
            $this->_usedProperties['client'] = true;
            $this->client = $config['client'];
            unset($config['client']);
        }

        if ($config) {
            throw new InvalidConfigurationException(sprintf('The following keys are not supported by "%s": ', __CLASS__).implode(', ', array_keys($config)));
        }
    }

    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['enabled'])) {
            $output['enabled'] = $this->enabled;
        }
        if (isset($this->_usedProperties['hosts'])) {
            $output['hosts'] = $this->hosts;
        }
        if (isset($this->_usedProperties['sslCaBundle'])) {
            $output['ssl_ca_bundle'] = $this->sslCaBundle;
        }
        if (isset($this->_usedProperties['sslVerification'])) {
            $output['ssl_verification'] = $this->sslVerification;
        }
        if (isset($this->_usedProperties['client'])) {
            $output['client'] = $this->client;
        }

        return $output;
    }

}
