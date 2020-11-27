<?php

Namespace Helpers;

use Classes\Member;
use Discord\Parts\Channel\Message;

class Helper{

    /**
     * Is author of supplied message an admin.
     * @param  Message $message
     * @return boolean
     */
    public static function isAuthorAdmin(Message $message)
    {

        // loop through all roles of the author of the message...
        foreach($message->author->roles as $role){

            // if the name of the role matches the admin role...
            if($role->name == en("ADMIN_ROLE")){
                return true;
            }

        }

        return false;

    }

    /**
     * Extract the name of all roles supplied within the message
     * @param  Message $message [description]
     * @return [type]           [description]
     */
    public static function getRoleNamesFromMessage(Message $message)
    {

        $roles = [];

        foreach($message->mention_roles as $role){
            $roles[] = $role->name;
        }

        return $roles;

    }

    public static function getMemberIdsFromMessage(Message $message){

        $ids = [];

        foreach($message->mentions as $member){
            $ids[] = $member->id;
        }

        return $ids;

    }

    /**
     * Get all members from server
     * @return
     */
    public static function getAllMembers()
    {

        $members = [];

        $object = self::getObject();

        // loop through every "server"?
        foreach($object->guilds as $guild){

            // loop through every member
            foreach($guild->members as $member){

                // make new member object
                $new_member = new Member($member);
                $members[] = $new_member;

            }
        }

        return $members;

    }

    /////////
    // NEW //
    /////////

    public static function getObject()
    {
        return $GLOBALS["object"];
    }

    /////////////
    // CURRENT //
    /////////////

    /**
     * Get roles of user
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function getRoles($data)
    {

        $roles = [];

        // loop through
        foreach($data->author->roles as $role){
            $roles[] = $role->name;
        }

        return $roles;

    }

    /**
     * Get mentioned roles
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function getMentionedRoles($data)
    {


    }

    /**
     * Get mentioned users
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function getMentionedUsers($data)
    {

        $users = [];

        foreach($data->mentions as $user){
            $users[$user->id] = $user->username;
        }

        return $users;

    }

    /**
     * return array of usernames => roles[]
     * @param  [type] $discord [description]
     * @return [type]          [description]
     */
    public static function getUsers($discord)
    {

        // username => roles []
        $users = [];

        // list all users/roles
        foreach($discord->guilds as $guild){
            foreach($guild->members as $member){
                    // username of member
                $username = $member->username;
                $users[$username] = [];

                foreach($member->roles as $role){
                    // all roles of member
                    $role = $role->name;
                    $users[$username][] = $role;

                }
            }
        }

        return $users;

    }

}
