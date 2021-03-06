<?php

/*
 *
 *  _                       _           _ __  __ _             
 * (_)                     (_)         | |  \/  (_)            
 *  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___  
 * | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \ 
 * | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/ 
 * |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___| 
 *                     __/ |                                   
 *                    |___/                                                                     
 * 
 * This program is a third party build by ImagicalMine.
 * 
 * PocketMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.net/
 * 
 *
*/

namespace pocketmine\command\defaults;

use pocketmine\command\CommandSender;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TitleCommand extends VanillaCommand
{

    public function __construct($name)
    {
        parent::__construct(
            $name,
            "%pocketmine.command.title.description",
            "%commands.title.usage",
            ["title"]
        );
        $this->setPermission("pocketmine.command.title");
    }

    public function execute(CommandSender $sender, $currentAlias, array $args)
    {
        if (!$this->testPermission($sender)) {
            return true;
        }

        if (count($args) < 3) {
            $sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

            return false;
        }

        $name = strtolower($args[0]);
        
        $type = strtolower($args[1]);

        $player = $sender->getServer()->getPlayer($name);

        if ($player instanceof Player) {
            unset($args[0]);
            unset($args[1]);
            $jsonargs = json_decode(implode(" ", $args), true);
            $text = [];
            if(json_last_error() == JSON_ERROR_NONE) {
                foreach($jsonargs as $key => $jsonarg) {
                    switch($key) {
                        case "text":
                        array_push($text, $jsonarg);
                        break;
                        case "color":
                        array_push($text, constant('C::'. strtoupper($jsonarg)));
                        break;
                        case "bold":
                        if($jsonarg) { // Detect if it's true
                            array_push($text, C::BOLD);
                        }
                        break;
                        case "italic":
                        if($jsonarg) { // Detect if it's true
                            array_push($text, C::ITALIC);
                        }
                        break;
                        case "reset":
                        if($jsonarg) { // Detect if it's true
                            array_push($text, C::ITALIC);
                        }
                        break;
                        default:
                        if(is_numeric($key)) {
                            array_push($text, $jsonarg);
                        }
                        break;
                    }
                }
            }
            switch($type) {
                case "title":
                $player->sendTip(implode(" ", $text));
                Command::broadcastCommandMessage($sender, new TranslationContainer("%commands.title.success", [$player, implode(" ", $text)]));
                break;
                case "subtitle":
                $player->sendPopup(implode(" ", $text));
                Command::broadcastCommandMessage($sender, new TranslationContainer("%commands.title.success", [$player, implode(" ", $args)]));
                break;
                default:
                $sender->sendMessage(new TranslationContainer("%commands.title.notvalidtype", [$player, $type]));
                break;
            }
        } else {
            $sender->sendMessage(new TranslationContainer("commands.generic.player.notFound"));
        }

        return true;
    }
}
