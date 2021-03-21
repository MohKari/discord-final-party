<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;
use Services\Trello;

class Staging extends BaseCommand{

    public $key_word = "end";
    public $aliasis = ["reset"];

    public $options = [
        "description" => "Move all cards back to the 'Member List' - Admin Only",
        "usage" => " | !reset",
    ];

    public function command(){

        return function($data, $params){

            // make sure only admin
            if(!Helper::isAuthorAdmin($data)){
                // \:emoji
                return "you're not my boss! <:pepeHeart:662681944659853323>";
            }

            // new trello...
            $trello = new Trello();

            try{

                // check to see if any cards with their name already exist...
                $trello->getCards();
                $cards = $trello->data;

                // move all cards to member list
                foreach($cards as $card){
                   $trello->moveCard($card->id, en("MEMBER_LIST"));
                }

            }catch(\Exception $e){
                return "error: " . $e->message;
            }

            return "I move them all boss!";

        };

    }

}
