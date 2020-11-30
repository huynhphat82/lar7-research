<?php

namespace Tests\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $http;
    /**
     * Base url
     *
     * @var string
     */
    protected $baseUrl = '';

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $stack = new HandlerStack();
        $stack->setHandler(new CurlHandler());

        // $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
        //     return $request->withHeader('X-Foo', 'bar');
        // }));
        // $stack->push(Middleware::mapResponse(function (ResponseInterface $response) {
        //     return $response->withHeader('X-Foo', 'bar');
        // }));

        // $stack->push($this->add_request_header('X-Foo-Request', 'bar'));
        // $stack->push($this->add_response_header('X-Foo-Response', 'baz'));

        $stack->push($this->add_request_header($this->addHeaders()));

        $this->http = new Client([
            // Base URI is used with relative requests
            //'base_uri' => $this->baseUrl,
            // You can set any number of default request options.
            'timeout'  => 2.0,
            'connect_timeout' => 3.0,
            'debug' => false,
            'http_errors' => true,
            'proxy' => '',
            'verify' => true,
            'headers' => [
                'Accept' => 'application/json',
            ],
            'handler' => $stack,
        ]);
    }

    /**
     * __call
     *
     * @param  string $method
     * @param  array $arguments
     * @return object
     */
    public function __call($method, $arguments = [])
    {
        try {
            $response = $this->http->request(strtoupper($method), ...$arguments);
            return $this->_responseSuccess(json_decode($response->getBody()), $response->getStatusCode(), $response->getReasonPhrase());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return $this->_responseError($response->getBody(), $response->getStatusCode(), $response->getReasonPhrase());
            }
            return $this->_responseError('Unknown.', 422, 'Unprocessable Entity');
        }
    }

    /**
     * Add headers to request headers
     *
     * @return array
     */
    protected function addHeaders()
    {
        return [];
    }

    /**
     * Success response
     *
     * @param  mixed $data
     * @param  int $statusCode
     * @param  string $statusText
     * @return object
     */
    private function _responseSuccess($data, $statusCode = 200, $statusText = 'OK')
    {
        return $this->_toJson([
            'success' => true,
            'status_code' => $statusCode,
            'status_text' => $statusText,
            'data' => $data
        ]);
    }

    private function _responseError($errors, $statusCode = 400, $statusText = 'ERROR')
    {
        return $this->_toJson([
            'success' => false,
            'status_code' => $statusCode,
            'status_text' => $statusText,
            'errors' => $errors,
        ]);
    }

    /**
     * Convert an array to json object
     *
     * @param  array $data
     * @return object
     */
    private function _toJson($data = [])
    {
        return json_decode(json_encode($data));
    }

    /**
     * Add query to request
     *
     * @return object
     */
    protected function withQuery()
    {
        $this->query = 'query';
        return $this;
    }

    /**
     * Body option as entity enclosing request (PUT, POST, PATCH)
     *
     * @return object
     */
    protected function withBody()
    {
        $this->body = 'body';
        return $this;
    }

    /**
     * Body option as json encoded data
     *
     * @return object
     */
    protected function withJson()
    {
        $this->body = 'json';
        return $this;
    }

    /**
     * Body option as application/x-www-form-urlencoded POST request
     *
     * @return object
     */
    protected function withForm()
    {
        $this->body = 'form_params';
        return $this;
    }

    /**
     * Body options as multipart/form-data form
     *
     * @return object
     */
    protected function withFile()
    {
        $this->body = 'multipart';
        return $this;
    }

    /**
     * Add request header (middleware)
     *
     * @param  string|array $header
     * @param  mixed $value
     * @return Closure
     */
    private function add_request_header($header = null, $value = null)
    {
        return function (callable $handler) use ($header, $value) {
            return function (
                RequestInterface $request,
                array $options
            ) use ($handler, $header, $value) {
                if (!is_array($header)) {
                    $header[$header] = $value;
                }
                foreach ($header as $k => $v) {
                    if (is_string($k) && $k) {
                        $request = $request->withHeader($k, $v);
                    }
                }
                return $handler($request, $options);
            };
        };
    }

    /**
     * Add response header (middleware)
     *
     * @param  string $header
     * @param  mixed $value
     * @return Closure
     */
    private function add_response_header($header, $value)
    {
        return function (callable $handler) use ($header, $value) {
            return function (
                RequestInterface $request,
                array $options
            ) use ($handler, $header, $value) {
                $promise = $handler($request, $options);
                return $promise->then(
                    function (ResponseInterface $response) use ($header, $value) {
                        return $response->withHeader($header, $value);
                    }
                );
            };
        };
    }

    /**
     * Options
     *
     * @return array
     */
    private function options()
    {
        return [
            'query' => [ // query string
                'foo' => 'bar'
            ],
            // string|resource|stream: entity enclosing request (PUT, POST, PATCH)
            'body' => 'text string', // fopen('http://httpbin.org', 'r'),
            'form_params' => [ // application/x-www-form-urlencoded
                'foo' => 'bar',
                'baz' => ['hi', 'there!']
            ],
            'json' => [ // JSON encoded data
                'foo' => 'bar'
            ],
            'multipart' => [ // multipart/form-data
                [
                    'name'     => 'foo',
                    'contents' => 'data',
                    'headers'  => ['X-Baz' => 'bar']
                ],
                [
                    'name'     => 'baz',
                    'contents' => fopen('/path/to/file', 'r')
                ],
                [
                    'name'     => 'qux',
                    'contents' => fopen('/path/to/file', 'r'),
                    'filename' => 'custom_filename.txt'
                ],
            ],
            'stream' => true
        ];
    }
}
