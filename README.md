# ApcuCacheWrapper
A tiny PHP class to cache expensive function/method calls (e.g. to remote APIs or databases).

Your application retrieves data from REST services or other remote systems? You have expensive methods for data that doesn't change that often? Then this little helper is for your to improve your applications performance.

## Usage

Your old code:

    $data = $myApiClient->fetchData($params);
    
New code using ApcuCacheWrapper:

    $cacheWrapper = new ApcuCacheWrapper('myprefix_', 3600);
    $data = $cacheWrapper->cachedCall(array($myApiClient, 'fetchData'), $params);

The *cachedCall*-Method wraps your original method call (here as callable *array($myApiClient, 'fetchData')*) and stores the result in a cache using APCU. Once the result is cached, the next call of *cachedCall* with the same parameters will return the cached results.
