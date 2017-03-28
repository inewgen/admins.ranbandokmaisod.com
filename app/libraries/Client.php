<?php

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;

class Client
{

    private static $base_url;
    private static $debug;
    private static $client;
    private static $env;
    private static $tran_date;
    private static $register_parallel     = array();
    private static $register_parallel_key = array();
    private static $result_parallel       = false;

    public function __construct($base_url = false, $options = array())
    {
        self::loadConfig($base_url, $options);

        if (!self::$client) {
            self::$debug     = Input::get('debug', false);
            self::$client    = new GuzzleHttp\Client();
            self::$env       = App::environment();
            self::$tran_date = date("Y-m-d H:i:s");
        }
    }

    private static function loadConfig($base_url = false, $options = array())
    {
        if ($base_url) {
            self::$base_url = $base_url;
            self::$base_url = rtrim(self::$base_url, '/') . '/';
        } else {
            if (!self::$base_url) {
                self::$base_url = Config::get('api.base_url');
                self::$base_url = rtrim(self::$base_url, '/') . '/';
            }
        }
    }

    public static function baseUrl($base_url)
    {
        self::$base_url = rtrim($base_url, '/') . '/';

        return new static();
    }

    /**
     * [get description]
     * @param  [type] $uri        [description]
     * @param  [type] $parameters [description]
     * @return [type] [description]
     */
    public static function get($uri, $parameters = array(), $options = array())
    {
        $base_options = [
            'config' => [
                'curl' => [
                    CURLOPT_TCP_NODELAY => true,
                    CURLOPT_VERBOSE     => false,
                    CURLOPT_ENCODING    => ''
                ]
            ],
            'query' => $parameters
        ];

        if (is_array($options) && count($options) > 0) {
            foreach ($options as $k => $v) {
                $base_options[$k] = $v;
            }
        }

        $async = false;
        if (isset($base_options['future']) && $base_options['future']) {
            $async = true;
        }

        try {
            $request = self::$client->createRequest('GET', self::$base_url . $uri, $base_options);

            $request->getQuery()->setEncodingType(false);

            if (!$async) {
                $request->getEmitter()->on('complete', function (CompleteEvent $e) {
                    if (self::$debug) {
                        $transfer = $e->getTransferInfo();
                        $response = $e->getResponse();

                        if (isset($transfer['total_time']) && $transfer['total_time'] > 2) {
                            Log::info('Client-Slow: ' . $transfer['total_time'], array('context' => $transfer));
                        }

                        if (self::$env == 'production') {
                            Log::info(self::$tran_date . ' : Client-get: ' . $transfer['total_time'], array('context' => $response->getEffectiveUrl()));
                        } else {
                            echo "Client::get: " . $transfer['total_time'] . " \n";
                            alert($response->getEffectiveUrl());
                        }
                    }
                });
            }

            if ($async) {
                self::$client->send($request);

                return true;
            } else {
                $response = self::$client->send($request);

                return $response->getBody();
            }
        } catch (RequestException $e) {
            Log::error('Client-RequestException', array('context' => $e->getMessage()));
        } catch (ClientException $e) {
            Log::error('Client-ClientException', array('context' => $e->getMessage()));
        } catch (ConnectException $e) {
            Log::error('Client-ConnectException', array('context' => $e->getMessage()));
        } catch (ServerException $e) {
            Log::error('Client-ServerException', array('context' => $e->getMessage()));
        } catch (BadResponseException $e) {
            Log::warning('Client-BadResponseException', array('context' => $e->getMessage()));

            if ($e->hasResponse()) {
                return $e->getResponse()->getBody();
            }
        }

        return false;
    }

    public static function getParallel($key, $uri, $parameters = array(), $subscriber = array())
    {
        $request = self::$client->createRequest('GET', self::$base_url . $uri, [
            'config' => [
                'curl' => [
                    CURLOPT_TCP_NODELAY => true,
                    CURLOPT_VERBOSE     => false,
                    CURLOPT_ENCODING    => ''
                ]
            ],
            'query' => $parameters
        ]);

        $request->getQuery()->setEncodingType(false);

        $hash = md5($request->getUrl());

        self::$register_parallel[$hash]     = $request;
        self::$register_parallel_key[$hash] = $key;

        return $request;
    }

    public static function postParallel($key, $uri, $parameters = array(), $subscriber = array())
    {
        $request = self::$client->createRequest('POST', self::$base_url . $uri, [
            'config' => [
                'curl' => [
                    CURLOPT_TCP_NODELAY => true,
                    CURLOPT_VERBOSE     => false,
                    CURLOPT_ENCODING    => ''
                ]
            ],
            'body' => $parameters
        ]);

        $hash = md5($request->getUrl());

        self::$register_parallel[$hash]     = $request;
        self::$register_parallel_key[$hash] = $key;

        return $request;
    }

    /**
     * [post description]
     * @param  [type] $uri        [description]
     * @param  array  $parameters [description]
     * @return [type] [description]
     */
    public static function post($uri, $parameters = array(), $options = array())
    {
        $base_options = [
            'config' => [
                'curl' => [
                    CURLOPT_TCP_NODELAY => true,
                    CURLOPT_VERBOSE     => false,
                    CURLOPT_ENCODING    => ''
                ]
            ],
            'body' => $parameters
        ];

        if (is_array($options) && count($options) > 0) {
            foreach ($options as $k => $v) {
                $base_options[$k] = $v;
            }
        }

        $async = false;
        if (isset($base_options['future']) && $base_options['future']) {
            $async = true;
        }

        try {
            $request = self::$client->createRequest('POST', self::$base_url . $uri, $base_options);

            if (!$async) {
                $request->getEmitter()->on('complete', function (CompleteEvent $e) {
                    if (self::$debug) {
                        $transfer = $e->getTransferInfo();
                        $response = $e->getResponse();

                        if (isset($transfer['total_time']) && $transfer['total_time'] > 2) {
                            Log::info('Client-Slow: ' . $transfer['total_time'], array('context' => $transfer));
                        }

                        if (self::$env == 'production') {
                            Log::info(self::$tran_date . ' : Client-post: ' . $transfer['total_time'], array('context' => $response->getEffectiveUrl()));
                        } else {
                            echo "Client::post: " . $transfer['total_time'] . " \n";
                            alert($response->getEffectiveUrl());
                        }
                    }
                });
            }

            if ($async) {
                self::$client->send($request);

                return true;
            } else {
                $response = self::$client->send($request);

                return $response->getBody();
            }
        } catch (RequestException $e) {
            Log::error('Client-RequestException', array('context' => $e->getMessage()));
        } catch (ClientException $e) {
            Log::error('Client-ClientException', array('context' => $e->getMessage()));
        } catch (ConnectException $e) {
            Log::error('Client-ConnectException', array('context' => $e->getMessage()));
        } catch (ServerException $e) {
            Log::error('Client-ServerException', array('context' => $e->getMessage()));
        } catch (BadResponseException $e) {
            Log::warning('Client-BadResponseException', array('context' => $e->getMessage()));

            if ($e->hasResponse()) {
                return $e->getResponse()->getBody();
            }
        }

        return false;
    }

    /**
     * [put description]
     * @param  [type] $uri        [description]
     * @param  array  $parameters [description]
     * @return [type] [description]
     */
    public static function put($uri, $parameters = array(), $options = array())
    {
        $base_options = [
            'config' => [
                'curl' => [
                    CURLOPT_TCP_NODELAY => true,
                    CURLOPT_VERBOSE     => false,
                    CURLOPT_ENCODING    => ''
                ]
            ],
            'body' => $parameters
        ];

        if (is_array($options) && count($options) > 0) {
            foreach ($options as $k => $v) {
                $base_options[$k] = $v;
            }
        }

        $async = false;
        if (isset($base_options['future']) && $base_options['future']) {
            $async = true;
        }

        try {
            $request = self::$client->createRequest('PUT', self::$base_url . $uri, $base_options);

            if (!$async) {
                $request->getEmitter()->on('complete', function (CompleteEvent $e) {
                    if (self::$debug) {
                        $transfer = $e->getTransferInfo();
                        $response = $e->getResponse();

                        if (isset($transfer['total_time']) && $transfer['total_time'] > 2) {
                            Log::info('Client-Slow: ' . $transfer['total_time'], array('context' => $transfer));
                        }

                        if (self::$env == 'production') {
                            Log::info(self::$tran_date . ' : Client-put: ' . $transfer['total_time'], array('context' => $response->getEffectiveUrl()));
                        } else {
                            echo "Client::put: " . $transfer['total_time'] . " \n";
                            alert($response->getEffectiveUrl());
                        }
                    }
                });
            }

            if ($async) {
                self::$client->send($request);

                return true;
            } else {
                $response = self::$client->send($request);

                return $response->getBody();
            }
        } catch (RequestException $e) {
            Log::error('Client-RequestException', array('context' => $e->getMessage()));
        } catch (ClientException $e) {
            Log::error('Client-ClientException', array('context' => $e->getMessage()));
        } catch (ConnectException $e) {
            Log::error('Client-ConnectException', array('context' => $e->getMessage()));
        } catch (ServerException $e) {
            Log::error('Client-ServerException', array('context' => $e->getMessage()));
        } catch (BadResponseException $e) {
            Log::warning('Client-BadResponseException', array('context' => $e->getMessage()));

            if ($e->hasResponse()) {
                return $e->getResponse()->getBody();
            }
        }

        return false;
    }

    /**
     * [delete description]
     * @param  [type] $uri        [description]
     * @param  array  $parameters [description]
     * @return [type] [description]
     */
    public static function delete($uri, $parameters = array(), $options = array())
    {
        $base_options = [
            'config' => [
                'curl' => [
                    CURLOPT_TCP_NODELAY => true,
                    CURLOPT_VERBOSE     => false,
                    CURLOPT_ENCODING    => ''
                ]
            ],
            'body' => $parameters
        ];

        if (is_array($options) && count($options) > 0) {
            foreach ($options as $k => $v) {
                $base_options[$k] = $v;
            }
        }

        $async = false;
        if (isset($base_options['future']) && $base_options['future']) {
            $async = true;
        }

        try {
            $request = self::$client->createRequest('DELETE', self::$base_url . $uri, $base_options);

            if (!$async) {
                $request->getEmitter()->on('complete', function (CompleteEvent $e) {
                    if (self::$debug) {
                        $transfer = $e->getTransferInfo();
                        $response = $e->getResponse();

                        if (isset($transfer['total_time']) && $transfer['total_time'] > 2) {
                            Log::info('Client-Slow: ' . $transfer['total_time'], array('context' => $transfer));
                        }

                        if (self::$env == 'production') {
                            Log::info(self::$tran_date . ' : Client-delete: ' . $transfer['total_time'], array('context' => $response->getEffectiveUrl()));
                        } else {
                            echo "Client::delete: " . $transfer['total_time'] . " \n";
                            alert($response->getEffectiveUrl());
                        }
                    }
                });
            }

            if ($async) {
                self::$client->send($request);

                return true;
            } else {
                $response = self::$client->send($request);

                return $response->getBody();
            }
        } catch (RequestException $e) {
            Log::error('Client-RequestException', array('context' => $e->getMessage()));
        } catch (ClientException $e) {
            Log::error('Client-ClientException', array('context' => $e->getMessage()));
        } catch (ConnectException $e) {
            Log::error('Client-ConnectException', array('context' => $e->getMessage()));
        } catch (ServerException $e) {
            Log::error('Client-ServerException', array('context' => $e->getMessage()));
        } catch (BadResponseException $e) {
            Log::warning('Client-BadResponseException', array('context' => $e->getMessage()));

            if ($e->hasResponse()) {
                return $e->getResponse()->getBody();
            }
        }

        return false;
    }

    /**
     * [patch description]
     * @param  [type] $uri        [description]
     * @param  array  $parameters [description]
     * @return [type] [description]
     */
    public static function patch($uri, $parameters = array(), $subscriber = array())
    {
        try {
            $request = self::$client->createRequest('PATCH', self::$base_url . $uri, [
                'body' => $parameters
            ]);

            $request->getEmitter()->on('complete', function (CompleteEvent $e) {
                if (self::$debug) {
                    $transfer = $e->getTransferInfo();
                    $response = $e->getResponse();

                    if (isset($transfer['total_time']) && $transfer['total_time'] > 2) {
                        Log::info('Client-Slow: ' . $transfer['total_time'], array('context' => $transfer));
                    }

                    if (self::$env == 'production') {
                        Log::info(self::$tran_date . ' : Client-patch: ' . $transfer['total_time'], array('context' => $response->getEffectiveUrl()));
                    } else {
                        echo "Client::patch:: " . $transfer['total_time'] . " \n";
                        alert($response->getEffectiveUrl());
                    }
                }
            });

            $response = self::$client->send($request);

            return $response->getBody();
        } catch (RequestException $e) {
            Log::error('Client-RequestException', array('context' => $e->getMessage()));
        } catch (ClientException $e) {

        } catch (ConnectException $e) {

        } catch (ServerException $e) {

        } catch (BadResponseException $e) {

        }

        return false;
    }

    public static function execParallel()
    {
        $muti      = self::$register_parallel;
        $muti_keys = array_keys($muti);

        $completes = [];
        $errors    = [];

        self::$client->sendAll(self::$register_parallel, [
            'complete' => function (CompleteEvent $event) use (&$completes) {
                $url = $event->getRequest()->getUrl();
                $completes[md5($url)] = $event;
            },
            'error' => function (ErrorEvent $event) use (&$errors) {
                $url = $event->getRequest()->getUrl();
                $errors[md5($url)] = $event;
            }
        ]);

        $_responses = array();
        foreach ($muti_keys as $key) {
            $res_key = 0;
            if (isset(self::$register_parallel_key[$key])) {
                $res_key = self::$register_parallel_key[$key];
            }

            $complete = false;
            if (isset($completes[$key])) {
                $complete = $completes[$key]->getResponse()->getBody();
            }

            $_responses[$res_key] = $complete;

            if (isset($completes[$key])) {
                if (self::$debug) {
                    $transfer = $completes[$key]->getTransferInfo();
                    $response = $completes[$key]->getResponse();

                    if (isset($transfer['total_time']) && $transfer['total_time'] > 2) {
                        Log::info('Client-Slow: ' . $transfer['total_time'], array('context' => $transfer));
                    }

                    if (self::$env == 'production') {
                        Log::info(self::$tran_date . ' : Client-parallel: ' . $transfer['total_time'], array('context' => $response->getEffectiveUrl()));
                    } else {
                        echo "Client::parallel: " . $transfer['total_time'] . " \n";
                        alert($response->getEffectiveUrl());
                    }
                }
            }
        }

        self::$register_parallel     = array();
        self::$register_parallel_key = array();

        self::$result_parallel = $_responses;

        return $_responses;
    }

    public static function resultParallel($key = false)
    {
        if ($key) {
            if (isset(self::$result_parallel[$key])) {
                return self::$result_parallel[$key];
            } else {
                return false;
            }
        }

        return self::$result_parallel;
    }

    /**
     * Create API response.
     *
     * @param  mixed   $messages
     * @param  integer $code
     * @return string
     */
    public static function createResponse($messages, $code = 200)
    {
        return self::make($messages, $code);
    }

    /**
     * Make json data format.
     *
     * @param  mixed   $data
     * @param  integer $code
     * @param  boolean $overwrite
     * @return string
     */
    public static function make($data, $code, $overwrite = false)
    {
        // Status returned.
        $status = (preg_match('/^2/', $code)) ? 'success' : 'error';

        // Change object to array.
        if (is_object($data)) {
            $data = $data->toArray();
        }

        if ($overwrite === true) {
            $response = $data;
        } else {

            $error_code = \Config::get('api.error_code');

            // Available data response.
            $response = array(
                'status_code' => (isset($error_code[$code])) ? $code : "99999",
                'status_txt' => (isset($error_code[$code])) ? $error_code[$code] : "undefined error code",
                'data'       => $data,
                'pagination' => null
            );

            // Merge if data has anything else.
            if (is_array($data) and isset($data['data'])) {
                $response = array_merge($response, $data);
            }

            // Remove empty array.
            $response = array_filter($response, function ($value) {
                return ! is_null($value);
            });
        }

        // Always return 200 header.
        return \Response::json($response, 200);
    }
}
