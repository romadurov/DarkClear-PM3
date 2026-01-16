<?php


/*    _____        ___      _        _      __
 *   |  ___ \    /  _  \   | \      / |   / __ \ 
 *   | |   \ \  / /   \ \  |  \    /  |  / /  \ \
 *   | |___/ / | |     | | | |\\  //| | | |____| |
 *   |  _  _/  | |     | | | | \\// | | |  ____  |
 *   | | \ \   | |     | | | |  \/  | | | |    | |
 *   | |  \ \   \ \ _ / /  | |      | | | |    | |
 *   |_|   \_\   \_____/   |_|      |_| |_|    |_|
 *
 *  ------------------------------------------------
 *
 *    ____     _    _   _____        ___      _   _
 *   |  _ \   | |  | | |  ___ \    /  _  \   | | | |
 *   | | \ \  | |  | | | |   \ \  / /   \ \  | | | |
 *   | |  \ | | |  | | | |___/ / | |     | | | | | |
 *   | |  | | | |  | | |  _  _/  | |     | | | | | |
 *   | |  / | | |  | | | | \ \   | |     | | | \_/ |
 *   | |_/ /  | |__| | | |  \ \   \ \ _ / /   \   /
 *   |____/    \____/  |_|   \_\   \_____/     \_/
 *
 *
 * Этот плагин является частным программным обеспечением и разработан Roma Durov (vk.com/romadurov).
 * Публикация этой программы со сменой автора запрещена и будет наказуема.
 * Этот плагин можно публиковать только с сохранением информации об авторе.
 * Данный плагин был разработан для моего проекта DarkFlame, но я решил сделать его общедоступным.
 *
 *
 *
 * This plugin is private software and was developed by Roma Durov (vk.com/romadurov).
 * Publication of this program with a changed author is forbidden and will be punished.
 * This plugin may only be published with the original author's credit intact.
 * This plugin was originally developed for my project DarkFlame, but I decided to make it publicly available.
 *

 *******************************************************

© 2026 ROMADUROV. All rights reserved.

You may:
- Use this software for personal or commercial purposes
- Modify the code as you like

You may NOT:
- Redistribute or publish the software under your name
- Remove or alter the original author's copyright notice

*********************************************************

© 2026 ROMADUROV. Все права защищены.

Вы можете:
- Использовать это программное обеспечение в личных или коммерческих целях
- Модифицировать код как вам угодно

Вы не можете:
- Распространять или публиковать программное обеспечение под своим именем
- Удалять или изменять уведомление об авторских правах оригинального автора

*********************************************************************************


 * @author Roma Durov
 * @Me - vk.com/romadurov | My server - vk.com/darkflame


 */



namespace RomaDurov;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\CallbackTask;
use pocketmine\tile\Tile;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\entity\Human;
use pocketmine\entity\ItemEntity;
use pocketmine\entity\Entity;
use pocketmine\entity\Creature;
use pocketmine\entity\Living;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;

class DarkClear extends PluginBase implements Listener
{



    // Everything is configured in the config / Все настраивается в конфиге @romadurov
    // resources/config.yml  @romadurov

	public $entities = [];




public int $timeLeft = 0;
public int $interval = 600; // this can be configured in the config / можно настроить это в конфиге  @romadurov
public bool $enabled = true;

public array $protectMode = [];
public array $unprotectMode = [];


private float $lastTPS = 20.0;
private bool $tpsMode = false;




public function onEnable(){
    $this->saveDefaultConfig();
    $this->reloadConfig();

    $this->interval = (int)$this->getConfig()->get("clear-interval");
    $this->enabled = (bool)$this->getConfig()->get("enabled", true);

    $this->timeLeft = $this->interval;

    $this->getServer()->getPluginManager()->registerEvents($this, $this);

    $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "update"]), 20);
}







public function update(){
    if(!$this->enabled) return;

    $tps = $this->getServer()->getTicksPerSecond();

    if($this->getConfig()->get("tps-cleaner", false)){
       if($tps <= $this->getConfig()->get("tps-force-clean", 13)){
         $this->clearEntities();
         $this->getServer()->broadcastMessage("§c[TPS] Экстренная очистка! TPS: " . round($tps, 1)); // Emergency cleaning @romadurov
       return;
   }
 
      $this->tpsMode = ($tps <= $this->getConfig()->get("tps-trigger", 16.5));
   }

    $this->timeLeft--;

  if(in_array($this->timeLeft, (array)$this->getConfig()->get("warn-seconds", []))){
     $this->getServer()->broadcastMessage("§7[§cDarkClear§7] Очистка через §e{$this->timeLeft} сек."); // Cleaning in {$this->timeLeft} seconds @romadurov
    }

    if($this->timeLeft <= 0){
      $this->clearEntities();
      $this->timeLeft = $this->interval;
    }
}






public function onDamageProtect(EntityDamageEvent $event){
    if(!$event instanceof EntityDamageByEntityEvent){
        return;
    }

    $damager = $event->getDamager();
    $entity = $event->getEntity();

    if(!$damager instanceof Player){
        return;
    }

    $name = $damager->getName();

  
    if(isset($this->protectMode[$name])){
        $event->setCancelled();

        if($entity->namedtag->getByte("noclear", 0) === 1){
            $damager->sendMessage("§cЭтот моб уже защищён."); // the mobile is already protected @romadurov
            unset($this->protectMode[$name]);
            return;
        }

        $entity->namedtag->setByte("noclear", 1);
        $entity->saveNBT();

        $damager->sendMessage("§aМоб защищён от очистки."); // The mob is protected from cleaning @romadurov
        unset($this->protectMode[$name]);
        return;
    }


    if(isset($this->unprotectMode[$name])){
        $event->setCancelled();

        if($entity->namedtag->getByte("noclear", 0) !== 1){
            $damager->sendMessage("§cЭтот моб не защищён."); // This mob is not secure  @romadurov
            unset($this->unprotectMode[$name]);
            return;
        }

        $entity->namedtag->removeTag("noclear");
        $entity->saveNBT();

        $damager->sendMessage("§aЗащита снята."); // Protection is lifted
        unset($this->unprotectMode[$name]);
        return;
    }
}





	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{

    if($cmd->getName() !== "darkclear") return true;

    if(!isset($args[0])){
        $sender->sendMessage("§e/darkclear help");
        return true;
    }

    switch(strtolower($args[0])){

        case "help":
            $sender->sendMessage("darkclear команды:");
            $sender->sendMessage("§e/darkclear clear §7- очистить сейчас"); // clear it now @romadurov
            $sender->sendMessage("§e/darkclear set <сек> §7- установить таймер"); // set a timer @romadurov
            $sender->sendMessage("§e/darkclear on §7- включить автоочистку"); // on auto cleaning @romadurov
            $sender->sendMessage("§e/darkclear off §7- выключить автоочистку"); // off auto cleaning @romadurov
            $sender->sendMessage("§e/darkclear protect §7- добавить энтити в исключение"); // add an entity to an exception @romadurov
            $sender->sendMessage("§e/darkclear unprotect §7- удалить энтити из исключения"); //remove an entity from an exception @romadurov
        break;

         case "clear":
            $this->clearEntities();
            $sender->sendMessage("§aОчистка выполнена."); // Quick cleaning  @romadurov
         break;

         case "set":
            if(!isset($args[1]) || !is_numeric($args[1])){
                $sender->sendMessage("§cИспользование: /darkclear set <секунды>"); // set a timer @romadurov
                return true;
            }

            $this->interval = (int)$args[1];
            $this->timeLeft = $this->interval;

            $this->getConfig()->set("clear-interval", $this->interval);
            $this->getConfig()->save();

            $sender->sendMessage("§aТаймер установлен: {$this->interval} сек."); // The timer is set to {$this->interval} seconds.  @romadurov
         break;

       case "on":
            $this->enabled = true;
            $this->timeLeft = $this->interval;
            $this->getConfig()->set("enabled", true);
            $this->getConfig()->save();
            $sender->sendMessage("§aАвтоочистка включена.");
       break;

          case "off":
            $this->enabled = false;
            $this->getConfig()->set("enabled", false);
            $this->getConfig()->save();
            $sender->sendMessage("§cАвтоочистка выключена.");
         break;


     case "protect":
   			 $this->protectMode[$sender->getName()] = true;
  			 $sender->sendMessage("§eУдарь по мобу, чтобы поставить исключение."); // Hit the mob to set an exception. @romadurov
		 break;

		case "unprotect":
    		 $this->unprotectMode[$sender->getName()] = true;
    		 $sender->sendMessage("§eУдарь по мобу, чтобы снять исключение."); // Hit the mob to remove the exception. @romadurov
		  break;

		  case "list":
    		 $page = isset($args[1]) ? (int)$args[1] : 1;
   			 $this->sendProtectedList($sender, $page);
		  break;

        default:
            $sender->sendMessage("§e/darkclear help");
    }

    return true;
}





private function sendProtectedList(CommandSender $sender, int $page){
    $list = [];

 foreach($this->getServer()->getLevels() as $level){
     foreach($level->getEntities() as $e){
             if($e->namedtag->getByte("noclear", 0) === 1){
                $list[] = $e;
            } 
         }
 }

   $perPage = 5;
  $max = max(1, ceil(count($list) / $perPage));
 $page = max(1, min($page, $max));

  $sender->sendMessage("§6Защищённые сущности §7($page/$max)"); // Protected entities @romadurov
    foreach(array_slice($list, ($page - 1) * $perPage, $perPage) as $e){
     $pos = $e->getPosition();
    $sender->sendMessage("§e{$e->getNameTag()} §7@ {$pos->getFloorX()}, {$pos->getFloorY()}, {$pos->getFloorZ()}");
}
}

	
public function clearEntities(){
    $countItems = 0;
    $countMobs = 0;
     $countEntity = 0;

    $smart = $this->getConfig()->get("smart-clean", true);
      $removeNear = $this->getConfig()->get("smart-remove-even-near", false);
        $radius = (int)$this->getConfig()->get("smart-radius", 24);

        foreach($this->getServer()->getLevels() as $level){
    foreach($level->getEntities() as $entity){

            
               if($entity instanceof Human){
             continue;
        }

           
        if($entity->namedtag->getByte("noclear", 0) === 1){
                continue;
        }

          
if($smart && !$removeNear){
    if(!$this->tpsMode){
        $nearest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach($level->getPlayers() as $p){
            $dist = $p->distance($entity->getPosition());
            if($dist <= $radius && $dist < $minDistance){
                $minDistance = $dist;
                $nearest = $p;
            }
        }

        if($nearest !== null){
            continue; 
        }
    }
}


         
      if($entity instanceof Living){
               if(!$this->getConfig()->get("remove-mobs", true)){
                  continue;
           }

          $entity->close();
          $countMobs++;
       continue;
        }


      if($entity instanceof Entity && !$entity instanceof Living && !$entity instanceof ItemEntity){
               if(!$this->getConfig()->get("remove-all-entity", true)){
                  continue;
           }

          $entity->close();
          $countEntity++;
       continue;
        }

         
     if(!$this->getConfig()->get("remove-items", true)){
             continue;
         }


if($entity instanceof ItemEntity){
        $entity->close();
          $countItems++;
      }
    }
  }

$tiles = 0;
if($this->getConfig()->get("remove-bugged-tiles", true)){
    $tiles = $this->clearBuggedTiles();
}

$this->getServer()->broadcastMessage("§7[§c§lDarkClear§r§7] §4> §cУдалено §e{$countItems} предметов, §e{$countMobs} мобов, §e{$countEntity} прочих энтити и §e{$tiles} тайлов"); // §cDeleted §e{$countItems} items, §e{$countMobs} mobs, §e{$countEntity} other entities ,AND §e{$tiles} tiles. @romadurov
}




private function clearBuggedTiles(): int{
$count = 0;

foreach($this->getServer()->getLevels() as $level){
    foreach($level->getTiles() as $tile){

     $pos = $tile->getBlock();

           
     if($pos->getId() === Block::AIR){
             $tile->close();
            $count++;
         }
    }
 }
    return $count;
}



}
