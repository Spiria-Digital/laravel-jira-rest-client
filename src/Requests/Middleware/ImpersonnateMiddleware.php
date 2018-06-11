<?php

namespace Atlassian\JiraRest\Requests\Middleware;

use Psr\Http\Message\RequestInterface;

class ImpersonnateMiddleware
{
    private $user_id;

    function __construct($userId = '')
    {
        $this->user_id = $userId;
    }
    
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $queryparams = \GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery());
            $query = \GuzzleHttp\Psr7\build_query(["user_id" => $this->user_id] + $queryparams);

            return $handler($request->withUri($request->getUri()->withQuery($query)), $options);
        };
    }
}
