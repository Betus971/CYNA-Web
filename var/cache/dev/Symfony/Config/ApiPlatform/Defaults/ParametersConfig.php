<?php

namespace Symfony\Config\ApiPlatform\Defaults;

use Symfony\Component\Config\Loader\ParamConfigurator;

/**
 * This class is automatically generated to help in creating a config.
 */
class ParametersConfig 
{
    private $key;
    private $schema;
    private $openApi;
    private $provider;
    private $filter;
    private $property;
    private $description;
    private $properties;
    private $required;
    private $priority;
    private $hydra;
    private $constraints;
    private $security;
    private $securityMessage;
    private $extraProperties;
    private $filterContext;
    private $nativeType;
    private $castToArray;
    private $castToNativeType;
    private $castFn;
    private $default;
    private $filterClass;
    private $_usedProperties = [];
    private $_extraKeys;
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function key(mixed $value): static
    {
        $this->_usedProperties['key'] = true;
        $this->key = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function schema(mixed $value): static
    {
        $this->_usedProperties['schema'] = true;
        $this->schema = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function openApi(mixed $value): static
    {
        $this->_usedProperties['openApi'] = true;
        $this->openApi = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function provider(mixed $value): static
    {
        $this->_usedProperties['provider'] = true;
        $this->provider = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function filter(mixed $value): static
    {
        $this->_usedProperties['filter'] = true;
        $this->filter = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function property(mixed $value): static
    {
        $this->_usedProperties['property'] = true;
        $this->property = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function description(mixed $value): static
    {
        $this->_usedProperties['description'] = true;
        $this->description = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function properties(mixed $value): static
    {
        $this->_usedProperties['properties'] = true;
        $this->properties = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function required(mixed $value): static
    {
        $this->_usedProperties['required'] = true;
        $this->required = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function priority(mixed $value): static
    {
        $this->_usedProperties['priority'] = true;
        $this->priority = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function hydra(mixed $value): static
    {
        $this->_usedProperties['hydra'] = true;
        $this->hydra = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function constraints(mixed $value): static
    {
        $this->_usedProperties['constraints'] = true;
        $this->constraints = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function security(mixed $value): static
    {
        $this->_usedProperties['security'] = true;
        $this->security = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function securityMessage(mixed $value): static
    {
        $this->_usedProperties['securityMessage'] = true;
        $this->securityMessage = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function extraProperties(mixed $value): static
    {
        $this->_usedProperties['extraProperties'] = true;
        $this->extraProperties = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function filterContext(mixed $value): static
    {
        $this->_usedProperties['filterContext'] = true;
        $this->filterContext = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function nativeType(mixed $value): static
    {
        $this->_usedProperties['nativeType'] = true;
        $this->nativeType = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function castToArray(mixed $value): static
    {
        $this->_usedProperties['castToArray'] = true;
        $this->castToArray = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function castToNativeType(mixed $value): static
    {
        $this->_usedProperties['castToNativeType'] = true;
        $this->castToNativeType = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function castFn(mixed $value): static
    {
        $this->_usedProperties['castFn'] = true;
        $this->castFn = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function default(mixed $value): static
    {
        $this->_usedProperties['default'] = true;
        $this->default = $value;
    
        return $this;
    }
    
    /**
     * @default null
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function filterClass(mixed $value): static
    {
        $this->_usedProperties['filterClass'] = true;
        $this->filterClass = $value;
    
        return $this;
    }
    
    public function __construct(array $config = [])
    {
        if (array_key_exists('key', $config)) {
            $this->_usedProperties['key'] = true;
            $this->key = $config['key'];
            unset($config['key']);
        }
    
        if (array_key_exists('schema', $config)) {
            $this->_usedProperties['schema'] = true;
            $this->schema = $config['schema'];
            unset($config['schema']);
        }
    
        if (array_key_exists('open_api', $config)) {
            $this->_usedProperties['openApi'] = true;
            $this->openApi = $config['open_api'];
            unset($config['open_api']);
        }
    
        if (array_key_exists('provider', $config)) {
            $this->_usedProperties['provider'] = true;
            $this->provider = $config['provider'];
            unset($config['provider']);
        }
    
        if (array_key_exists('filter', $config)) {
            $this->_usedProperties['filter'] = true;
            $this->filter = $config['filter'];
            unset($config['filter']);
        }
    
        if (array_key_exists('property', $config)) {
            $this->_usedProperties['property'] = true;
            $this->property = $config['property'];
            unset($config['property']);
        }
    
        if (array_key_exists('description', $config)) {
            $this->_usedProperties['description'] = true;
            $this->description = $config['description'];
            unset($config['description']);
        }
    
        if (array_key_exists('properties', $config)) {
            $this->_usedProperties['properties'] = true;
            $this->properties = $config['properties'];
            unset($config['properties']);
        }
    
        if (array_key_exists('required', $config)) {
            $this->_usedProperties['required'] = true;
            $this->required = $config['required'];
            unset($config['required']);
        }
    
        if (array_key_exists('priority', $config)) {
            $this->_usedProperties['priority'] = true;
            $this->priority = $config['priority'];
            unset($config['priority']);
        }
    
        if (array_key_exists('hydra', $config)) {
            $this->_usedProperties['hydra'] = true;
            $this->hydra = $config['hydra'];
            unset($config['hydra']);
        }
    
        if (array_key_exists('constraints', $config)) {
            $this->_usedProperties['constraints'] = true;
            $this->constraints = $config['constraints'];
            unset($config['constraints']);
        }
    
        if (array_key_exists('security', $config)) {
            $this->_usedProperties['security'] = true;
            $this->security = $config['security'];
            unset($config['security']);
        }
    
        if (array_key_exists('security_message', $config)) {
            $this->_usedProperties['securityMessage'] = true;
            $this->securityMessage = $config['security_message'];
            unset($config['security_message']);
        }
    
        if (array_key_exists('extra_properties', $config)) {
            $this->_usedProperties['extraProperties'] = true;
            $this->extraProperties = $config['extra_properties'];
            unset($config['extra_properties']);
        }
    
        if (array_key_exists('filter_context', $config)) {
            $this->_usedProperties['filterContext'] = true;
            $this->filterContext = $config['filter_context'];
            unset($config['filter_context']);
        }
    
        if (array_key_exists('native_type', $config)) {
            $this->_usedProperties['nativeType'] = true;
            $this->nativeType = $config['native_type'];
            unset($config['native_type']);
        }
    
        if (array_key_exists('cast_to_array', $config)) {
            $this->_usedProperties['castToArray'] = true;
            $this->castToArray = $config['cast_to_array'];
            unset($config['cast_to_array']);
        }
    
        if (array_key_exists('cast_to_native_type', $config)) {
            $this->_usedProperties['castToNativeType'] = true;
            $this->castToNativeType = $config['cast_to_native_type'];
            unset($config['cast_to_native_type']);
        }
    
        if (array_key_exists('cast_fn', $config)) {
            $this->_usedProperties['castFn'] = true;
            $this->castFn = $config['cast_fn'];
            unset($config['cast_fn']);
        }
    
        if (array_key_exists('default', $config)) {
            $this->_usedProperties['default'] = true;
            $this->default = $config['default'];
            unset($config['default']);
        }
    
        if (array_key_exists('filter_class', $config)) {
            $this->_usedProperties['filterClass'] = true;
            $this->filterClass = $config['filter_class'];
            unset($config['filter_class']);
        }
    
        $this->_extraKeys = $config;
    
    }
    
    public function toArray(): array
    {
        $output = [];
        if (isset($this->_usedProperties['key'])) {
            $output['key'] = $this->key;
        }
        if (isset($this->_usedProperties['schema'])) {
            $output['schema'] = $this->schema;
        }
        if (isset($this->_usedProperties['openApi'])) {
            $output['open_api'] = $this->openApi;
        }
        if (isset($this->_usedProperties['provider'])) {
            $output['provider'] = $this->provider;
        }
        if (isset($this->_usedProperties['filter'])) {
            $output['filter'] = $this->filter;
        }
        if (isset($this->_usedProperties['property'])) {
            $output['property'] = $this->property;
        }
        if (isset($this->_usedProperties['description'])) {
            $output['description'] = $this->description;
        }
        if (isset($this->_usedProperties['properties'])) {
            $output['properties'] = $this->properties;
        }
        if (isset($this->_usedProperties['required'])) {
            $output['required'] = $this->required;
        }
        if (isset($this->_usedProperties['priority'])) {
            $output['priority'] = $this->priority;
        }
        if (isset($this->_usedProperties['hydra'])) {
            $output['hydra'] = $this->hydra;
        }
        if (isset($this->_usedProperties['constraints'])) {
            $output['constraints'] = $this->constraints;
        }
        if (isset($this->_usedProperties['security'])) {
            $output['security'] = $this->security;
        }
        if (isset($this->_usedProperties['securityMessage'])) {
            $output['security_message'] = $this->securityMessage;
        }
        if (isset($this->_usedProperties['extraProperties'])) {
            $output['extra_properties'] = $this->extraProperties;
        }
        if (isset($this->_usedProperties['filterContext'])) {
            $output['filter_context'] = $this->filterContext;
        }
        if (isset($this->_usedProperties['nativeType'])) {
            $output['native_type'] = $this->nativeType;
        }
        if (isset($this->_usedProperties['castToArray'])) {
            $output['cast_to_array'] = $this->castToArray;
        }
        if (isset($this->_usedProperties['castToNativeType'])) {
            $output['cast_to_native_type'] = $this->castToNativeType;
        }
        if (isset($this->_usedProperties['castFn'])) {
            $output['cast_fn'] = $this->castFn;
        }
        if (isset($this->_usedProperties['default'])) {
            $output['default'] = $this->default;
        }
        if (isset($this->_usedProperties['filterClass'])) {
            $output['filter_class'] = $this->filterClass;
        }
    
        return $output + $this->_extraKeys;
    }
    
    /**
     * @param ParamConfigurator|mixed $value
     *
     * @return $this
     */
    public function set(string $key, mixed $value): static
    {
        $this->_extraKeys[$key] = $value;
    
        return $this;
    }

}
