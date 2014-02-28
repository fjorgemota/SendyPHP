<?php
namespace SendyPHP;

use Guzzle\Http\Client;
use SendyPHP\Exceptions\InvalidArgument;
use SendyPHP\ValueObject\SendyResponse;

/**
* SendyPHP Class
*/
class SendyPHP
{
    /**
     * The installation URL of the Sendy
     *
     * @var Client $client
     */
    protected $client;

    /**
     * The API Key used by this instance to request Sendy.
     *
     * @var string $api_key
     */
    protected $api_key;

    /**
     * The list ID used by this instance.
     *
     * @var string $list_id
     */
    protected $list_id;

    /**
     * Constructs a object receiving List ID, installation URL and API Key.
     * @throws InvalidArgument
     */
    public function __construct($installation_url_or_client, $api_key, $list_id)
    {
        //error checking
        if (!isset($installation_url_or_client) || !$installation_url_or_client) {
            throw new InvalidArgument("Required config parameter [installation_url] is not set", 1);
        }
        if (!isset($api_key) || !$api_key) {
            throw new InvalidArgument("Required config parameter [api_key] is not set", 1);
        }
        if (!isset($list_id) || !$list_id) {
            throw new InvalidArgument("Required config parameter [list_id] is not set", 1);
        }
        if ($installation_url_or_client instanceof Client) {
            $this->client = $installation_url_or_client;
        }
        else {
            $this->client = new Client($installation_url);
        }
        $this->api_key = $api_key;
        $this->list_id = $list_id;

    }

    /**
     * Update the List ID used by the class.
     *
     * @param $list_id
     * @throws InvalidArgument
     * @internal param $string $list_id
     */
    public function setListId($list_id)
    {
        if (!isset($list_id) || !$list_id) {
            throw new InvalidArgument("Required config parameter [list_id] is not set", 1);
        }
        $this->list_id = $list_id;
    }

    /**
     * Returns the actual list ID managed by this class.
     *
     * @return string
     */
    public function getListId()
    {
        return $this->list_id;
    }

    /**
     * Send a subscribe request to Sendy with the parameters specified
     *
     * @param array $values
     * @return SendyResponse
     */
    public function subscribe(array $values)
    {

        //Send the subscribe
        $result = $this->sendRequest('subscribe', $values);

        //Handle results
        switch ($result) {
            case 'true':
            case '1':
                return new SendyResponse(true, $result);
                break;
            case 'Already subscribed.':
                return new SendyResponse(true, $result);
                break;
            default:
                return new SendyResponse(false, $result);
                break;
        }
    }

    /**
     * Unsubscribes the user from the list used by the instance of this class
     *
     * @param string $email The e-mail to unsubscribe from the list
     * @return SendyResponse
     */
    public function unsubscribe($email)
    {
        //Send the unsubscribe request
        $parameters = array(
            'email' => $email
        );
        $result = $this->sendRequest('unsubscribe', $parameters);
        //Handle results
        if ($result == '1' || $result == 'true') {
            return new SendyResponse(true, $result);
        }
        return new SendyResponse(false, $result);
    }

    /**
     * Get status of the e-mail specified in the actual list used by this instance
     *
     * @param string $email
     * @return SendyResponse
     */
    public function getStatus($email)
    {
        $parameters = array(
            'email' => $email,
            'api_key' => $this->api_key,
            'list_id' => $this->list_id
        );
        //Send the request for status
        $result = $this->sendRequest('api/subscribers/subscription-status.php', $parameters);
        //Handle the results
        switch ($result) {
            case 'Subscribed':
            case 'Unsubscribed':
            case 'Unconfirmed':
            case 'Bounced':
            case 'Soft bounced':
            case 'Complained':
                return new SendyResponse(true, $result);
                break;
            default:
                return new SendyResponse(false, $result);
                break;
        }
    }

    /**
     * Get the subscriber count in the actual list used by this instance
     * @return SendyResponse
     * @throws InvalidArgument
     */
    public function getSubscribersCount()
    {
        //Send request for subcount
        $parameters = array(
            'api_key' => $this->api_key,
            'list_id' => $this->list_id
        );
        $result = $this->sendRequest('api/subscribers/active-subscriber-count.php', $parameters);

        //Handle the results
        return new SendyResponse(is_numeric($result), $result);
    }

    /**
     * Central method that makes request to Sendy
     *
     * @param $type
     * @param array $values
     * @return string
     * @throws Exceptions\InvalidArgument
     */
    protected function sendRequest($type, array $values)
    {
        //Global options for return
        $return_options = array(
            'list' => $this->list_id,
            'boolean' => 'true'
        );

        //Merge the passed in values with the options for return
        $content = array_merge($values, $return_options);

        return $this->client->post($type, [], $content)->send()->getBody(true);
    }
}
