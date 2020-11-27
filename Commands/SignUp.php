<?php

namespace Commands;

use Classes\Member;
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

            // for messages, "new", "signed up", "already signed up"
            $state = "";

            // convert author to my member...
            $member = new Member($data->author);

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

                // if card id has not been found, make a new card
                if($found_card == false){

                    // make new card
                    $trello->newCard();

                    // get card id
                    $card_id = $trello->data->id;

                    // add new member label
                    $trello->addLabel($card_id);

                    // add name
                    $trello->addName($card_id, $member->name);

                    // move into signed up
                    $trello->moveCard($card_id, en("SIGN_UP_LIST"));

                    // new state
                    return "Its your first time? Don't worry, I'll look after you. ( If its not your first time signing up, the bot has made a hiccup... )";

                // if card is currently in member list, move it to sign up
                }else if($found_card->idList == en("MEMBER_LIST")){

                    $state = "signed up";

                    $trello->moveCard($found_card->id, en("SIGN_UP_LIST"));

                }else{

                    // messages for when you are already signed up
                    $array = [
                        "slow down tiger, you're already signed up!",
                        "I know you love me, but theres no need to sign up multiple times.",
                        "maybe if you where this enthusiastic about actually turning up on time, we would win sometimes?",
                        "Boop, Beep, Boob, I R COMPUTER .... Go away.",
                        "stop hassling me. You're in already.",
                        "go check Trello...",
                    ];

                    return $array[array_rand($array)];

                }

            }catch(\Exception $e){
                ddd($e->getMessage(), false);
                return "sign-up error, poke MohKari.";
            }

            // sucessfully signed up messages
            $array = [
                "you son of a... You're in!",
                "well, look who decided to sign up!",
                "it's about time you did your part.",
                "now sashay away",
                "all aboard the chuchu train~",
                "I always knew this day would come.",
                "tonight ( or tomorrow? ) we dine in hell!",
                "do you have plans for after the war? A few friends of mine are... Oh, you have plans...",
                "I look forward to seeing your blood on the battlefield.",
                "fresh meat for the ginder",
            ];

            return $array[array_rand($array)];

        };

    }

}
