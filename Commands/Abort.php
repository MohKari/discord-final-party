<?php

namespace Commands;

use Classes\Member;
use Commands\BaseCommand;
use Helpers\Helper;
use Services\Trello;

class Abort extends BaseCommand{

    public $key_word = "out";
    public $aliasis = ["abort"];

    public $options = [
        "description" => "Leave current party / sign out.",
        "usage" => " | abort",
    ];

    public function command(){

        return function($data, $params){

            // you tried to run this command at a bad time
            $bad_time = Helper::isBadTime();
            if($bad_time == true){

                // sucessfully signed up messages
                $array = [
                    "It's too late for that now, Armageddon is upon us! To Arms!",
                    "Ha! You think you have a choice in war!?",
                    "Ever heard of forced conscription?",
                    "It's too late for that, the ship has already set sail!",
                ];

                $response = $array[array_rand($array)] . PHP_EOL . "You can't 'sign out' on Thursday or Sunday between 6:45-8:00(GMT). If you can't make it, don't worry though!";

                return $response;

            }

            // convert author to my member...
            $member = new Member($data->author);

            // I think your this person...
            // ddd("abort", false);
            // ddd($member, false);
            // return "Sorry, I was trying to do something, but kind of ended up breaking this bot... So... Erm... Someone will need to manually move or create your card in Trello. <:pepeHeart:662681944659853323>";

            // new trello...
            $trello = new Trello();

            try{

                // check to see if any cards with their name already exist...
                $trello->getCards();
                $cards = $trello->data;

                // find card if a card has this users name on it
                $found_card = false;
                foreach($cards as $card){
                    if($card->name == $member->name){
                        $found_card = $card;
                    }
                }

                // if user doesnt have a card
                if($found_card == false){

                    // no card exists for user
                    return "I can't find a card for you. You're " . $member->name . " right? Or did I get that wrong?";

                // is user is already in member list
                }else if($found_card->idList == en("MEMBER_LIST")){

                    $array = [
                        "we already have you down as 'Have better things to do.'",
                        "you're not currently signed up, so...",
                        "I can't remove a name from the list, if the name was never on it!",
                    ];

                    return $array[array_rand($array)];

                // all other eventualities
                }else{

                    // move into member list
                    $trello->moveCard($found_card->id, en("MEMBER_LIST"));

                    $array = [
                        "you are now on the 'Will probably ask for party last minute' list.",
                        "hope you enjoy your evening without us...",
                        "last minute changes? You are now, off the list!",
                        "was that a mistake? Did you mean '!sign-up'?",
                    ];

                    return $array[array_rand($array)];

                }

            }catch(\Exception $e){
                ddd($e->getMessage(), false);
                return "abort error, poke MohKari.";
            }

        };

    }

}
