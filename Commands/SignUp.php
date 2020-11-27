<?php

namespace Commands;

use Commands\BaseCommand;
use Helpers\Helper;
use Services\Trello;

class SignUp extends BaseCommand{

    public $key_word = "sign-up";

    public $options = [
        "description" => "Sign up for GvG",
        "usage" => "",
    ];

    public function command(){

        return function($data, $params){

            // authors name and id
            $name = $data->author->user->username;
            $u_id = $data->author->user->id;

            // new trello...
            $trello = new Trello();

            try{

                // check to see if any cards with their name already exist...
                $trello->getCards();
                $cards = $trello->data;

                // find card if a card has this users name on it
                $found_card = false;
                foreach($cards as $card){
                    if($card->name == $name){
                        $found_card = $card;
                    }
                }

                // if card id has not been found, make a new card
                if($found_card == false){

                    // make new card
                    $trello->newCard();

                    // get card id
                    $card_id = $trello->data->id;

                    // add new member label
                    $trello->addLabel($card_id);

                    // add name
                    $trello->addName($card_id, $name);

                // if card is currently in member list, move it to sign up
                }else if($found_card->idList == en("MEMBER_LIST")){

                    $trello->moveCard($found_card->id, en("SIGN_UP_LIST"));

                }

            }catch(\Exception $e){
                return "error: " . $e->message;
            }

            $array = [
                "You son of a... You're in!",
                "Well, look who decided to sign up!",
                "It's about time you did your part.",
                "now sashay away",
                "All aboard the chuchu train~",
            ];

            return $array[array_rand($array)];

        };

    }

}
