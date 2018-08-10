<?php

/**
 * ApcuCacheWrapper is used to cache expensive function/method calls (e.g. to remote APIs or databases)
 * 
 * @author Hendrik Pilz
 */
class ApcuCacheWrapper {

    private $prefix = '';
    private $ttl = 3600;
    
    /**
     * 
     * @param string $prefix a unique prefix which is used to prefix the caching key, reuse $prefix on different sites to access the same cached data
     * @param int $ttl time in seconds how long the data is stored in the cache
     */
    function __construct($prefix = '', $ttl = 3600) {
        $this->prefix = $prefix;
        $this->ttl = $ttl;
    }

    /**
     * @param callable $callable the function/method to be cached
     * @param mixed $parameters the parameters for the called function/method
     */
    function cachedCall(callable $callable, ...$parameters) {
        if (!function_exists('apcu_fetch')) {
            return call_user_func_array($callable, $parameters);
        }
        
        $key = $this->getKey($callable, $parameters);
        
        if (apcu_exists($key) === true) {
            return apcu_fetch($key);
        }
        
        $result = call_user_func_array($callable, $parameters);
        apcu_add($key, $result, $this->ttl);
        return $result;
    }
    
    private function getKey(callable $callable, $parameters) {
        $key = $this->prefix;
        
        if (is_array($callable)) {
            $className = get_class($callable[0]);
            $methodName = $callable[1];
            $key .= $className . '_' . $methodName;
        } else {
            $key .= $callable;
        }
        
        foreach ($parameters as $parameter) {
            $key .= '_' . $parameter;
        }
        
        return $key;
    }
}
