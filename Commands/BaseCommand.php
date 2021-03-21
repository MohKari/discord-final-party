<?php

namespace Commands;

class BaseCommand{

	/**
	 * Key word to run the command
	 * @var string
	 */
	public $key_word;

	/**
	 * Alias for commands
	 * @var array
	 */
	public $aliasis = [];

	/**
	 * Any additional options
	 * @var array
	 */
	public $options =  [
		// "description" => "desc",
		// "usage" => "us",
	];

	/**
	 * Command to be run.
	 * @return callable function
	 */
	public function command()
	{

		return function($data, $params){
			return "hello world";
		};

	}

}
