<?php

namespace SlaxxyQC\listeners;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use SlaxxyQC\StaffMode;

class PlayerListener implements Listener {

    public StaffMode $plugin;

    public function __construct(StaffMode $plugin) {
        $this->plugin = $plugin;
    }

    public function onQuit(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
        $config = new Config(StaffMode::getInstance()->getDataFolder()."config.yml", Config::YAML);
        $messages = new Config(StaffMode::getInstance()->getDataFolder()."messages.yml", Config::YAML);
        if (in_array ($player->getName(), $this->plugin->freeze)) {
            unset($this->plugin->freeze[array_search($player->getName(), $this->plugin->freeze)]);
            
            if ($config->get("allow-quit-broadcast-freeze") === true) {
                $event->setQuitMessage(TextFormat::colorize(str_replace(["{player}"], [$player->getName()], $messages->get("freeze-message-leave"))));
            } else {
                $event->setQuitMessage($event->getQuitMessage());
            }
        }
        return;
    }

    public function onMove(PlayerMoveEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array ($player->getName(), $this->plugin->freeze)) {
            $event->cancel();
        }
        return;
    }

    public function onDamage(EntityDamageByEntityEvent $event) : void {
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        if ($damager != null) {
            if ($entity instanceof Player and $damager instanceof Player) {
                if (in_array ($damager->getName(), $this->plugin->freeze)) {
                    $event->cancel();
                }
            }
        }
        return;
    }
}