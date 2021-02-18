<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;
use Services\ChuckNorris;

class ChuckNorrisCommand extends BaseCommand{

    public $key_word = "fact";

    public $options = [
        "description" => "Learn about amazing facts about Chuck Norris.",
        "usage" => "",
    ];

    public function command(){

        return function($data, $params){

            // new trello...
            $chuck = new ChuckNorris();

            try{

                // get fact
                $chuck->randomFact();

                // get fact from data
                $fact = $chuck->data->value;

                // return
                return $fact;

            }catch(\Exception $e){

                ddd($e->getMessage(), false);
                return "Couldn't get you an amazing fact, sorry!.";

            }

        };

    }

}
