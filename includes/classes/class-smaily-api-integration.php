<?php

namespace CreditLoanCalculator\Classes;

/**
 *  Smaily Api Integration
 */
class SmailyApiIntegration
{

    /**
     * Host
     *
     * @var string
     */
    const HOST = 'https://%s.sendsmaily.net/api/';

    /**
     * Base URL
     *
     * @var string
     */
    private $base_url;

    /**
     * Domain
     *
     * @var string
     */
    private $domain;

    /**
     * Username
     *
     * @var string
     */
    private $username;

    /**
     * Password
     *
     * @var string
     */
    private $password;

    /**
     *  Constructor
     */
    public function __construct($domain, $username, $password)
    {
        $this->domain = $domain;
        $this->username = $username;
        $this->password = $password;

        $this->base_url = sprintf(self::HOST, $this->domain);
    }

    /**
     * Create Default Header Arguments
     *
     * @param $args
     * @return mixed
     */
    private function create_default_args($args)
    {
        $default_headers = array(
            'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
            'Content-Type' => 'application/json',
        );

        $args['headers'] = isset($args['headers']) ? wp_parse_args($args['headers'], $default_headers) : $default_headers;

        return $args;
    }

    /**
     * Get Data
     *
     * @param $endpoint
     * @param $args
     * @return false|mixed
     */
    public function get_data($endpoint, $args = array())
    {
        $url = $this->base_url . $endpoint;

        $args = $this->create_default_args($args);

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);

        return json_decode($body, true);
    }

    /**
     * Post Data
     *
     * @param $endpoint
     * @param $data
     * @param $args
     * @return false|mixed
     */
    public function post_data($endpoint, $data, $args = array())
    {
        $url = $this->base_url . $endpoint;
        $args = $this->create_default_args($args);

        $args['body'] = json_encode($data);

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);

        return json_decode($body, true);
    }

    /**
     * Create Subscriber
     *
     * @param array $data Data
     * @return false|mixed
     */
    public function create_subscriber($data) {
        return $this->post_data('contact.php', $data);
    }
}
