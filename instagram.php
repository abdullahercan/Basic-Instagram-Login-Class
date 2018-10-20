<?php
class instagram{

    /**
     * Basic Instagram Login Class
     * Instagram API Documentation: https://www.instagram.com/developer/
     * @author Abdullah Ercan
     * @version 1.0
     * @license BSD http://www.opensource.org/licenses/bsd-license.php
     */

    /**
     * API base URL
     * */
    const API_URL = 'https://api.instagram.com/v1/';

    /**
     * OAuth URL
     * */
    const OAUTH_URL = 'https://api.instagram.com/oauth/authorize';

    /**
     * Token OAuth URL
     * */
    const OAUTH_TOKEN_URL = 'https://api.instagram.com/oauth/access_token';

    private $_apikey;
    private $_apisecret;
    private $_apicallback;
    private $_accesstoken;

    /**
     * @param $config
     */
    public function __construct($config){
        $this->setApiKey($config["key"]);
        $this->setSecret($config["secret"]);
        $this->setCallback($config["callback"]);
    }

    /**
     * @param $token
     */
    public function setAccessToken($token){
        $this->_accesstoken = $token;
    }

    /**
     * @return mixed
     */
    public function getAccessToken(){
        return $this->_accesstoken;
    }

    /**
     * @param $code
     * @return mixed
     * @throws CurlException
     */
    public function getOAuthToken($code){
        return $this->getOAuthRequest([
            "client_id" => $this->getApiKey(),
            "client_secret" => $this->getSecret(),
            "grant_type" => "authorization_code",
            "redirect_uri" => $this->getCallback(),
            "code" => $code
        ]);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws CurlException
     * @throws Exception
     */
    public function getUser($id = "self"){
        return $this->getRequest("users/" . $id);
    }

    /**
     * @param string $id
     * @param string $limit
     * @return mixed
     * @throws CurlException
     * @throws Exception
     */
    public function getMedia($id = "self", $limit = "20"){
        return $this->getRequest("users/".$id."/media/recent/", ["count" => $limit]);
    }

    /**
     * @param $base
     * @param null $params
     * @param string $method
     * @return mixed
     * @throws CurlException
     * @throws Exception
     */
    private function getRequest($base, $params = null, $method = "GET"){
        $requestUrl = self::API_URL;

        if( empty($this->_accesstoken) ){
            throw new Exception("Access Token Required!");
        }else{
            $token = "?access_token=" . $this->getAccessToken();
        }

        if (isset($params) && is_array($params)) {
            $string = '&' . http_build_query($params);
        } else {
            $string = null;
        }

        $requestUrl.= $base . $token . ( ($method == "GET") ? $string : null );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, ltrim($string, '&'));
        }

        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpcode == 403) {
            $error = json_decode($data, true);
            throw new CurlException($error['error_message']);
        }
        if (false === $data) {
            throw new CurlException('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode($data);
    }

    /**
     * @param $data
     * @return mixed
     * @throws CurlException
     */
    public function getOAuthRequest($data = []){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::OAUTH_TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $jsonData = curl_exec($ch);
        if (false === $jsonData) {
            throw new CurlException('getOAuthRequest() - cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode($jsonData);
    }

    /**
     * @return bool
     */
    public function isLogin(){
        return $this->getAccessToken() ? false : true;
    }

    /**
     * @return string
     */
    public function loginUrl(){
        $url_data = [
            "client_id" => $this->getApiKey(),
            "redirect_uri" => $this->getCallback(),
            "response_type" => "code",
            "scope" => "basic"
        ];

        return self::OAUTH_URL . "?" . http_build_query($url_data);
    }

    /**
     * @param $apikey
     */
    public function setApiKey($apikey){
        $this->_apikey = $apikey;
    }

    /**
     * @param $secret
     */
    public function setSecret($secret){
        $this->_apisecret = $secret;
    }

    /**
     * @param $callback
     */
    public function setCallback($callback){
        $this->_apicallback = $callback;
    }

    /**
     * @return mixed
     */
    public function getApiKey(){
        return $this->_apikey;
    }

    /**
     * @return mixed
     */
    public function getSecret(){
        return $this->_apisecret;
    }

    /**
     * @return mixed
     */
    public function getCallback(){
        return $this->_apicallback;
    }
}