<?php

Namespace Services;

class Trello{

    /**
     * Base API UL
     * @var string
     */
    private $api_url = "https://api.trello.com/1/";

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

        $this->url = $this->api_url . "/" . preg_replace(
        	["{{board}}", "{{key}}", "{{token}}", "{{member_list}}", "{{sign_up_list}}"],
        	[en("BOARD"), en("KEY"), en("TOKEN"), en("MEMBER_LIST"), en("SIGN_UP_LIST")],
        	$this->url);

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

        	// ddd($error, false);
        	throw new \Exception($error);

        }else{

        	// ddd($response, false);
        	$this->state = "success";
        	$this->data = json_decode($response);

        }

        // close the curl
        curl_close($curl);

    }

    /**
     * Make a new card on trello in
     * @return [type] [description]
     */
	public function newCard(){

		$this->request = "POST";
		$this->url = "cards?key={key}&token={token}&idList={member_list}";

		$this->curlInit();

	}

	/**
	 * Add new member label to card
	 * @param [type] $card_id [description]
	 */
	public function addLabel($card_id){

		$this->request = "POST";
		$this->url = "cards/" . $card_id . "/idLabels?key={key}&token={token}&value=" . en("NEW_MEMBER_LABEL");

		$this->curlInit();

	}

	/**
	 * [addName description]
	 * @param [type] $card_id [description]
	 * @param [type] $name    [description]
	 */
	public function addName($card_id, $name){

		$this->request = "PUT";
		$this->url = "cards/" . $card_id . "?key={key}&token={token}&name=" . urlencode($name);

		$this->curlInit();

	}

	/**
	 * [getCards description]
	 * @return [type] [description]
	 */
	public function getCards(){

		$this->request = "GET";
		$this->url = "boards/{board}/cards?key={key}&token={token}";

		$this->curlInit();

	}

	/**
	 * [moveCard description]
	 * @param  [type] $card_id [description]
	 * @return [type]          [description]
	 */
	public function moveCard($card_id, $list_id){

		$this->request = "PUT";
		$this->url = "cards/" . $card_id . "?key={key}&token={token}&idList=" . $list_id;

		$this->curlInit();

	}

}
