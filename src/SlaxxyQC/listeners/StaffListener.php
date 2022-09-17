<?php

namespace SlaxxyQC\listeners;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\entity\EntityCombustEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;

use SlaxxyQC\StaffMode;

class StaffListener implements Listener {

    public StaffMode $plugin;

    public function __construct(StaffMode $plugin) {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        $messages = new Config(StaffMode::getInstance()->getDataFolder()."messages.yml", Config::YAML);
        foreach (StaffMode::getInstance()->getServer()->getOnlinePlayers() as $players) {
            if ($players->hasPermission("staffmode.join")) {
                $players->sendMessage(TextFormat::colorize(str_replace(["{player}"], [$player->getName()], $messages->get("staff-join"))));
            }
        }
        return;
    }

    public function onQuit(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
        $messages = new Config(StaffMode::getInstance()->getDataFolder()."messages.yml", Config::YAML);
        foreach (StaffMode::getInstance()->getServer()->getOnlinePlayers() as $players) {
            if ($players->hasPermission("staffmode.join")) {
                $players->sendMessage(TextFormat::colorize(str_replace(["{player}"], [$player->getName()], $messages->get("staff-quit"))));
            }
        }

        if (in_array ($player->getName(), $this->plugin->staffmode)) {
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->getEffects()->clear();
            $player->extinguish();
            $player->setFlying(false);
            $player->setAllowFlight(false);
            $player->setSilent(false);

            unset($this->plugin->staffmode[array_search($player->getName(), $this->plugin->staffmode)]);
            $player->getInventory()->setContents($this->plugin->backup_items[$player->getName()]);
            $player->getInventory()->setContents($this->plugin->backup_armor[$player->getName()]);
            $player->setGamemode($this->plugin->backup_gamemode[$player->getName()]);
            foreach (StaffMode::getInstance()->getServer()->getOnlinePlayers() as $players) {
                $players->showPlayer($player);
            }
        }
        return;
    }

    public function onExhaust(PlayerExhaustEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array ($player->getName(), $this->plugin->staffmode)) {
            $event->cancel();
        }
        return;
    }

    public function onDeath(PlayerDeathEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array ($player->getName(), $this->plugin->staffmode)) {
            $event->setDrops([]);
        }
        return;
    }

    public function onRespawn(PlayerRespawnEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array ($player->getName(), $this->plugin->staffmode)) {
            StaffMode::getKitManager()->getKitStaff($player);
        }
        return;
    }

    public function onKick(PlayerKickEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array ($player->getName(), $this->plugin->staffmode)) {
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->getEffects()->clear();
            $player->extinguish();
            $player->setFlying(false);
            $player->setAllowFlight(false);

            unset($this->plugin->staffmode[array_search($player->getName(), $this->plugin->staffmode)]);
            $player->getInventory()->setContents($this->plugin->backup_items[$player->getName()]);
            $player->getInventory()->setContents($this->plugin->backup_armor[$player->getName()]);
            $player->setGamemode($this->plugin->backup_gamemode[$player->getName()]);
        }
        return;
    }

    public function onDrop(PlayerDropItemEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array ($player->getName(), $this->plugin->staffmode)) {
            $event->cancel();
        }
        return;
    }

    public function onInteract(PlayerInteractEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array ($player->getName(), $this->plugin->staffmode)) {
            $event->cancel();
        }
        return;
    }

    public function onBreak(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array ($player->getName(), $this->plugin->staffmode)) {
            $event->cancel();
        }
        return;
    }

    public function onPlace(BlockPlaceEvent $event) : void {
        $player = $event->getPlayer();
        if (in_array ($player->getName(), $this->plugin->staffmode)) {
            $event->cancel();
        }
        return;
    }

    public function onDamage(EntityDamageEvent $event) : void {
        if(!($event instanceof EntityDamageByEntityEvent)) {
            return;
        }
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        if(!($entity instanceof Player) || !($damager instanceof Player)) {
            return;
        }

        if (in_array ($entity->getName(), $this->plugin->staffmode)) {
            $event->cancel();
        }

        if (in_array ($damager->getName(), $this->plugin->staffmode)) {
            $event->cancel();
        }
        return;
    }

    public function onPickup(EntityItemPickupEvent $event) : void {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            if (in_array ($entity->getName(), $this->plugin->staffmode)) {
                $event->cancel();
            }
        }
        return;
    }

    public function onCombust(EntityCombustEvent $event) : void {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            if (in_array ($entity->getName(), $this->plugin->staffmode)) {
                $event->cancel();
            }
        }
        return;
    }

    public function onTransaction(InventoryTransactionEvent $event) : void {
        $player = $event->getTransaction()->getSource();
        if ($player instanceof Player) {
            if (in_array ($player->getName(), $this->plugin->staffmode)) {
                $event->cancel();
            }
        }
    }
}