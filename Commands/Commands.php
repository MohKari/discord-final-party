<?php

namespace Commands;

use Discord\DiscordCommandClient;

class Commands{

	/**
	 * instance of DiscordCommandClient
	 * @var DiscordCommandClient
	 */
	public $discord;

	/**
	 * Array of classes that contain commands that we need to load in...
	 * @var [type]
	 */
	public $commands = [
		"Commands\\DemoCommand",
		"Commands\\Abort",
		"Commands\\SignUp",
		"Commands\\Staging",
		"Commands\\ChuckNorrisCommand",
	];

	/**
	 * on __construct, set properties
	 * @param DiscordCommandClient $discord [description]
	 */
	public function __construct(DiscordCommandClient $discord){
		$this->discord = $discord;
	}

	/**
	 * Load all discord commands
	 */
	public function load()
	{

		// try to load all commands in
		foreach($this->commands as $command){

			// ToDo: error checks etc
			$class = new $command();

			// register the command
			$this->discord->registerCommand(
				$class->key_word,
				$class->command(),
				$class->options
			);

			// register all alias's
			foreach($class->aliasis as $alias){
				$this->discord->registerAlias($alias, $class->key_word);
			}

		}

	}

}