<?php

/**
 * A sample client library for the Onnistuu.fi API.
 *
 * You may need to extend this if on a windows host and/or using
 * an older version of PHP. Check method comments for details.
 *
 * Tested with PHP 5.3 on a linux host.
 *
 * Written to use version 0.6 of the Onnistuu.fi API.
 * Doesn't implement document checking and some other smaller functionality.
 *
 * Usage example:
 * // include library / use autoloader
 * $client = new Onnistuu_External_Client(
 *     'ede412ec-5a86-4cf5-8f9d-25e6b6dfae9d',
 *     'sE1RdFPXZXaDrIpZ3XiFYdEQlF63Yt9GaEtVFCBQmcU='
 * );
 * $encrypted = $client->encryptRequest(array(
 *     'stamp' => '123456789',
 *     'return_success' => 'https://your.address.tld/onnistuu-return-path',
 *     'document' => 'https://your.address.tld/document-path-123456789',
 *     'requirements' => array(
 *         array(
 *             'type' => 'person',
 *             // Nordea test identifier, won't work on the live service
 *             'identifier' => '010100-123D',
 *         ),
 *         array(
 *             'type' => 'email',
 *             'identifier' => '010100-123D',
 *             'email' => 'address.to@invite.tld',
 *             'sms' => '+358401234567', // optional
 *         ),
 *     ),
 *     // 'afterwards_invite_email' => 'you@your.address.tld',
 *     // 'document_check_url => 'https://your.address.tld/check-document-path',
 * ));
 * // create POST form with $encrypted['data'], $encrypted['iv'], customer id,
 * // return_failure url and optional auth_service selection
 *
 * @version OnnistuuAPI-0.6-Client-0.3
 */
class Onnistuu_External_Client
{
    /**
     * @var string Customer ID provided by Onnistuu.fi
     */
    protected $_customerId;

    /**
     * @var string Crypt key base64 encoded provided by Onnistuu.fi
     */
    protected $_cryptKey;

    /**
     * @var string A cipher supported by the php mcrypt module.
     */
    protected $_mcryptCipher = MCRYPT_RIJNDAEL_256;

    /**
     * @var string A mode supported by the selected cipher.
     */
    protected $_mcryptMode = MCRYPT_MODE_CBC;
    
    /**
     * @param string $customerId Customer ID provided by Onnistuu.fi
     * @param string $cryptKey Crypt key base64 encoded provided by Onnistuu.fi
     */
    public function __construct($customerId, $cryptKey)
    {
        $this->_customerId = $customerId;
        $this->_cryptKey = $cryptKey;
    }

    /**
     * Encrypts your Onnistuu.fi API request.
     *
     * Expects a request array with:
     * stamp, return_success, document, requirements (array), type,  identifier
     *
     * You can also include in the request:
     * afterwards_invite_email, document_check_url
     *
     * Returns an array with:
     * data, iv
     *
     * Remember to use in the final POST request:
     * data, iv, customer (your customer id, provided by Onnistuu.fi),
     * return_failure, auth_service (when using as a button straight to a bank)
     *
     * @param array $request
     * @return array
     */
    public function encryptRequest($request)
    {
        if (!is_array($request)) {
            throw new Exception('Request is not an array');
        }
        if (!isset($request['stamp'])) {
            throw new Exception('Stamp not defined');
        }
        if (!isset($request['return_success'])) {
            throw new Exception('Return success url not defined');
        }
        if (!isset($request['document'])) {
            throw new Exception('Document url not defined');
        }
        if (!isset($request['requirements'])) {
            throw new Exception('Requirements not defined');
        }
        if (!is_array($request['requirements'])) {
            throw new Exception('Requirements are not an array');
        }
        if (!count($request['requirements'])) {
            throw new Exception('Requirements array is empty');
        }
        foreach ($request['requirements'] as $requirement) {
            if (!is_array($requirement)) {
                throw new Exception('A requirement is not an array');
            }
            if (!isset($requirement['type'])) {
                throw new Exception('A requirement has no type');
            }
            if (!isset($requirement['identifier'])) {
                throw new Exception('A requirement has no identifier');
            }
        }

        return $this->_encryptRequest($request);
    }

    /**
     * Does the actual work of encrypting the request.
     *
     * @param array $request
     * @return array
     */
    protected function _encryptRequest($request)
    {
        $iv = $this->_createIv();
        $data = base64_encode(mcrypt_encrypt(
            $this->_mcryptCipher,
            base64_decode($this->_cryptKey),
            json_encode($request),
            $this->_mcryptMode,
            $iv
        ));

        return array('data' => $data, 'iv' => base64_encode($iv));
    }

    /**
     * Decrypts a return message from Onnistuu.fi.
     *
     * @param string $data The base64 encoded data returned by Onnistuu.fi
     * @param string $iv The base64 encoded iv returned by Onnistuu.fi
     * @return array The decrypted return values array
     */
    public function decryptReturn($data, $iv)
    {
        return json_decode(trim(mcrypt_decrypt(
            $this->_mcryptCipher,
            base64_decode($this->_cryptKey),
            base64_decode($data),
            $this->_mcryptMode,
            base64_decode($iv)
        )));
    }

    /**
     * Create an encryption initialization vector (iv).
     * 
     * At least prior to PHP 5.3.0, this will not work on windows hosts.
     * Consider MCRYPT_RAND instead of MCRYPT_DEV_URANDOM.
     */
    protected function _createIv($source = MCRYPT_DEV_URANDOM)
    {
        $size = mcrypt_get_iv_size($this->_mcryptCipher, $this->_mcryptMode);
        return mcrypt_create_iv($size, $source);
    }

    /**
     * Fetch an array of authentication services.
     *
     * array(
     *     array(
     *         'name' => 'sampo',
     *         'value' => 'tupas-sampo',
     *         'img' => 'https://www.sampopankki.fi/verkkopalvelu/logo.gif',
     *     ),
     *     ...
     * )
     *
     * @return array
     */
    public function getAuthServices()
    {
        return json_decode(file_get_contents(
            'https://www.onnistuu.fi/external/auth-services/customer/'
          . $this->_customerId
        ));
    }
}

