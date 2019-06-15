<?php
/**
 * @author Serge Rodovnichenko <serge@syrnik.com>
 * @copyright Serge Rodovnichenko, 2019
 * @license MIT
 */

namespace SergeR\WebasystDeveloperAccount\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use SergeR\WebasystDeveloperAccount\Exception\ErrorMessage;
use SergeR\WebasystDeveloperAccount\Exception\ResponseException;

/**
 * Class GuzzleAdapter
 *
 * @package SergeR\WebasystDeveloperAccount\Adapter
 */
class GuzzleAdapter extends AbstractAdapter implements AdapterInterface
{
    /** @var ClientInterface */
    protected $client;

    /** @var Response */
    protected $response;

    /**
     * GuzzleAdapter constructor.
     * @param $token
     */
    public function __construct($token)
    {
        if (!is_string($token) || empty($token)) {
            throw new InvalidArgumentException('Non-empty token required');
        }

        $options['headers']['X-API-Key'] = $token;

        $handler = HandlerStack::create();
        $handler->push(Middleware::mapResponse(function (ResponseInterface $response) {
            return $this->handleResponse($response);
        }));

        $options['handler'] = $handler;

        $this->client = new Client($options);
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws RuntimeException
     * @throws ResponseException
     */
    protected function handleResponse(ResponseInterface $response)
    {
        $code = $response->getStatusCode();

        $this->response = $response;

        $body = $response->getBody();
        $decoded_body = json_decode($body, true);

        if ($code == 200) {
            if (is_array($decoded_body) && isset($decoded_body['status']) && ($decoded_body['status'] == 'ok')) {
                $data = isset($decoded_body['data']) ? $decoded_body['data'] : [];
                $newBody = fopen('data://text/plain,' . json_encode($data), 'r');
                $response = $response->withBody(new Stream($newBody));
                return $response;
            }
        }

        if (!is_array($decoded_body) || !isset($decoded_body['status']) || ($decoded_body['status'] != 'fail')) {
            throw new RuntimeException($response->getReasonPhrase(), $response->getStatusCode());
        } else {
            throw (new ResponseException($response->getReasonPhrase(), $response->getStatusCode()))
                ->setError(new ErrorMessage($decoded_body));
        }
    }

    /**
     * Performs a HTTP GET request
     *
     * @param string $url
     * @param array $headers
     * @param array $query_params
     * @return string
     * @throws GuzzleException
     */
    public function get($url, array $headers = [], array $query_params = [])
    {
        $options = ['headers' => $headers, 'query' => $query_params];
        $this->response = $this->client->request('GET', $this->getUrl() . $url, $options);

        return (string)$this->response->getBody();
    }

    /**
     * Performs a HTTP POST request
     *
     * @param string $url
     * @param array $headers
     * @param mixed $content
     * @return string
     * @throws GuzzleException
     */
    public function post($url, array $headers = [], $content = '')
    {
        $options = ['headers' => $headers, 'form_params' => $content];
        $this->response = $this->client->request('POST', $this->getUrl() . $url, $options);

        return (string)$this->response->getBody();
    }

    /**
     * Performs a HTTP DELETE request
     *
     * @param string $url
     * @param array $headers
     * @param array $query_params
     * @return string
     * @throws GuzzleException
     */
    public function delete($url, array $headers = [], array $query_params = [])
    {
        $options = ['headers' => $headers, 'query' => $query_params];
        $this->response = $this->client->request('DELETE', $this->getUrl() . $url, $options);

        return (string)$this->response->getBody();
    }
}