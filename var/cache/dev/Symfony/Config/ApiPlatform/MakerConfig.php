<?php

namespace Symfony\Config\ApiPlatform;

use Symfony\Component\Config\Loader\ParamConfigurator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class is automatically generated to help in creating a config.
 */
class MakerConfig 
{
    private $enabled;
    private $namespacePrefix;
    private $_usedProperties = [];
    
    /**
     * @default true
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
     * Add a prefix to all maker generated classes. e.g set it to "Api" to set the maker namespace to "App\Api\" (if the maker.root_namespace config is App). e.g. App\Api\State\MyStateProcessor
     * @param ParamConfigurator|mixed $value
     * @return $this
     */
    public function namespacePrefix($value): static
    {
        $this->_usedProperties['namespacePrefix'] = true;
        $this->namespacePrefix = $value;
    
        return $this;
    }
    
    public function __construct(array $config = [])
    {
        if (array_key_exists('enabled', $config)) {
            $this->_usedProperties['enabled'] = true;
            $this->enabled = $config['enabled'];
            unset($config['enabled']);
        }
    
        if (array_key_exists('namespace_prefix', $config)) {
            $this->_usedProperties['namespacePrefix'] = true;
            $this->namespacePrefix = $config['namespace_prefix'];
            unset($config['namespace_prefix']);
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
        if (isset($this->_usedProperties['namespacePrefix'])) {
            $output['namespace_prefix'] = $this->namespacePrefix;
        }
    
        return $output;
    }

}
