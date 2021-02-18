<?php

Namespace Services;

class ChuckNorris{

    /**
     * Base API UL
     * @var string
     */
    private $api_url = "https://api.chucknorris.io/jokes";

    /**
     * URL that is generated as part of the curl requests.
     * @var string
     */
    private $url;

    /**
     * Type of request for curl to perform.
     * @var string
     */
    private $request = "GET";

    /**
	 * State of titan response
	 * @var string
	 */
	public $state;

	/**
	 * Data of successful titan response
	 * @var object/array
	 */
	public $data;

	/**
	 * Error message of failed call to titan
	 * @var string
	 */
	public $error;

	public function __construct(){

	}

	/**
     * Initiate the base for a curl request
     */
    private function curlInit(){

        // create blank curl object
        $curl = curl_init();


        $this->url = $this->api_url . "/" . $this->url;

        // set a bunch of properties
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST => false,        // because target host doesn't have valid SSL cert
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,                   // maximum 5 seconds to make the call
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->request,
        ));

        // get the response
        $response = curl_exec($curl);

        // get any errors
        $error = curl_error($curl);

        // if any errors, set these properties
        if($error){

        	throw new \Exception($error);

        }else{

        	$this->state = "success";
        	$this->data = json_decode($response);

        }

        // close the curl
        curl_close($curl);

    }

    /**
     * Get random fact
     * @return [type] [description]
     */
	public function randomFact(){

		$this->url = "random";

		$this->curlInit();

	}

}
