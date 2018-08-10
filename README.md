# ApcuCacheWrapper
A tiny PHP class to cache expensive function/method calls (e.g. to remote APIs or databases)

## Usage

Your old code:

    $data = $myApiClient->fetchData($params);
    
New code using ApcuCacheWrapper:

    $cacheWrapper = new ApcuCacheWrapper('myprefix_', 3600);
    $data = $cacheWrapper->cachedCall(array($myApiClient, 'fetchData'), $params);
