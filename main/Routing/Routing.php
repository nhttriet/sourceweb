<?php

namespace Main\Routing;

class Routing
{
    public function __construct(array $routes = [])
    {
        $requestUrl = $this->getRequestURL();
        $requestMethod = $this->getRequestMethod();
        $requestParams = explode('/', $requestUrl);
        foreach ($routes as $route) {
            $routeParams = explode('/', $route['uri']);
            if (strpos(strtolower($route['method']), strtolower($requestMethod)) !== false) {
                if (count($requestParams) === count($routeParams)) {
                    $checking = new HandleMatched($route['uri'], $requestUrl);
                    if ($checking->isMatched === true) {
                        return new NextPasses($routeParams, $requestParams, $route['action'], $route['middleware']);
                    }
                }
            }
        }
        return $this->handleNotFound();
    }

    /**
     * Handle not found
     */
    private function handleNotFound()
    {
        return app()->make('controller')->render('404');
    }

    /**
     * Get request url
     * @return string
     */
    private function getRequestURL()
    {
        $uri = urldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );
        return $uri === '' || empty($uri) ? '/' : $uri;
    }

    /**
     * Get request method
     * @return string
     */
    private function getRequestMethod()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : "GET";
    }
}
