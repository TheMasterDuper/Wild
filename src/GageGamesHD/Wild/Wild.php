<?php
/**
 *
 *   _____                   _____                           _    _ _____  
 *  / ____|                 / ____|                         | |  | |  __ \ 
 * | |  __  __ _  __ _  ___| |  __  __ _ _ __ ___   ___  ___| |__| | |  | |
 * | | |_ |/ _` |/ _` |/ _ \ | |_ |/ _` | '_ ` _ \ / _ \/ __|  __  | |  | |
 * | |__| | (_| | (_| |  __/ |__| | (_| | | | | | |  __/\__ \ |  | | |__| |
 *  \_____|\__,_|\__, |\___|\_____|\__,_|_| |_| |_|\___||___/_|  |_|_____/ 
 *               __/ |                                                    
 *              |___/                                                     
 *
 * Copyright (C) 2018 iiFlamiinBlaze
 *
 * iiFlamiinBlaze's plugins are licensed under MIT license!
 * Made by iiFlamiinBlaze, GageGamesHD for the PocketMine-MP Community!
 *
 * @author GageGamesHD
 * 
 */
declare(strict_types=1);

namespace GageGamesHD\Wild;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Wild extends PluginBase implements Listener{

    private const VERSION = "1.2";
    private const PREFIX = TextFormat::AQUA . "Wild" . TextFormat::GOLD . " > ";

    /** @var array $isInWild */
    private $isInWild = [];

    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Wild 1.2 " . self::VERSION . " by GageGamesHD has been enabled");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if($command->getName() === "wild"){
            if(!$sender instanceof Player){
                $sender->sendMessage(self::PREFIX . TextFormat::RED . "You can only use this command in game.");
                return false;
            }
            if(!$sender->hasPermission("wild.command")){
                $sender->sendMessage(self::PREFIX . TextFormat::RED . "Sorry, you do not have permission to use this command.");
                return false;
            }
            $x = rand(1, 1500);
            $z = rand(1, 1500);
            $sender->teleport(new Position($x, 128, $z, $sender->getLevel()));
            $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You have been teleported to the coords " . TextFormat::AQUA . "X: $x | Y: 128 | Z: $z" . TextFormat::GREEN . " in the wild!");
            $this->isInWild[] = $sender->getName();
        }
        return true;
    }

    public function onDamage(EntityDamageEvent $event) : void{
        $entity = $event->getEntity();
        if(!$entity instanceof Player) return;
        if($event->getCause() === EntityDamageEvent::CAUSE_FALL){
            if(in_array($entity->getName(), $this->isInWild)){
                unset($this->isInWild[array_search($entity->getName(), $this->isInWild)]);
                $event->setCancelled(true);
            }
        }
    }
}