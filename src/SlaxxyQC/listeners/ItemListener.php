<?php

namespace SlaxxyQC\listeners;

use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use SlaxxyQC\StaffMode;
use jojoe77777\FormAPI\SimpleForm;

class ItemListener implements Listener {

    public StaffMode $plugin;
    public Array $iplayers;
    public Array $teleport;

    public function __construct(StaffMode $plugin) {
        $this->plugin = $plugin;
    }

    public function onItem(PlayerItemUseEvent $event) : void {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if ($item->getName() === TextFormat::colorize("&6Ce TP sur un Joueur(s)")) {
            $this->getTeleport($player);
        }

        if ($item->getName() === TextFormat::colorize("&6Ce TP aléatoirement sur un Joueur(s)")) {
            $players = [];
            foreach (StaffMode::getInstance()->getServer()->getOnlinePlayers() as $iplayers) {
                $players[] = $iplayers;
            }

            $iplayer = $players[array_rand($players)];

            if (!$iplayer instanceof Player) {
                $player->sendMessage(TextFormat::colorize("&6[Staff] &dIl y a aucun joueur de Connecté actuellement !"));
            }

            if ($player->getName() === $iplayer->getName()) {
                return;
            }
            
            if ($iplayer instanceof Player) {
                $player->teleport($iplayer->getPosition());
                $player->sendMessage(TextFormat::colorize("&6[Staff] &dTu tes TP au joueur: &f{$iplayer->getName()}"));
            }
        }

        if ($item->getName() === TextFormat::colorize("&6Activée le Vanish")) {
            $player->sendMessage(TextFormat::colorize("&6[Staff] &dTu est en mode Vanish !"));
            StaffMode::getKitManager()->getKitVanish($player);
            foreach (StaffMode::getInstance()->getServer()->getOnlinePlayers() as $players) {
                $players->hidePlayer($player);
            }
        }

        if ($item->getName() === TextFormat::colorize("&dActivée le Vanish")) {
            $player->sendMessage(TextFormat::colorize("&6[Staff] &dTu n'est plus en mode Vanish !"));
            StaffMode::getKitManager()->getKitStaff($player);
            foreach (StaffMode::getInstance()->getServer()->getOnlinePlayers() as $players) {
                $players->showPlayer($player);
            }
        }
        return;
    }

    public function getTeleport(Player $player) : SimpleForm {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) {
                return;
            }

            $this->teleport[$player->getName()] = $data;
            if ($this->teleport[$player->getName()] === $player->getName()) {
                return;
            }

            if (isset ($this->teleport[$player->getName()])) {
                $iplayer = StaffMode::getInstance()->getServer()->getPlayerExact($this->teleport[$player->getName()]);
                if ($iplayer instanceof Player) {
                    $player->teleport($iplayer->getPosition());
                    $player->sendMessage(TextFormat::colorize("&6[Staff] &dTu tes TP sur le joueur: &f{$iplayer->getName()}"));
                }
            }
        });

        $form->setTitle("&6Liste de les joueur connecté");
        foreach (StaffMode::getInstance()->getServer()->getOnlinePlayers() as $players) {
            $form->addButton($players->getName(), -1, "", $players->getName());
        }
        $player->sendForm($form);
        return $form;
    }

    public function onEntity(EntityDamageByEntityEvent $event) : void {
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        $messages = new Config(StaffMode::getInstance()->getDataFolder()."messages.yml", Config::YAML);
        if ($entity instanceof Player and $damager instanceof Player) {
            $item = $damager->getInventory()->getItemInHand();
            if ($item->getName() === TextFormat::colorize("&6Obtenir les Info du joueur")) {
                $damager->sendMessage(TextFormat::colorize(str_replace(["{player}", "{ping}", "{health}", "{address}", "{platform}"], [$entity->getName(), $entity->getNetworkSession()->getPing(), (int)$entity->getHealth(), $entity->getNetworkSession()->getIp(), StaffMode::getInstance()->getUtilsManager()->getPlayerPlatform($entity)], $messages->get("pinfo-message"))));
                $event->cancel();
            }

            if ($item->getName() === TextFormat::colorize("&6Kick le Joueur(s)")) {
                $entity->kick(TextFormat::colorize(str_replace(["{player}", "{staff}"], [$entity->getName(), $damager->getName()], $messages->get("kicked-player"))));
                $event->cancel();
            }

            if ($item->getName() === TextFormat::colorize("&6Freeze un Joueur(s)")) {
                if (!in_array ($entity->getName(), $this->plugin->freeze)) {
                    $this->plugin->freeze[] = $entity->getName();

                    $entity->sendTitle(TextFormat::colorize($messages->get("freeze-title")));
                    $entity->sendMessage(TextFormat::colorize(str_replace(["{player}", "{staff}"], [$entity->getName(), $damager->getName()], $messages->get("freeze-message"))));
                    StaffMode::getInstance()->getServer()->broadcastMessage(TextFormat::colorize(str_replace(["{player}", "{staff}"], [$entity->getName(), $damager->getName()], $messages->get("server-broadcast-freeze"))));
                    $event->cancel();
                } else if (in_array ($entity->getName(), $this->plugin->freeze)) {
                    unset($this->plugin->freeze[array_search($entity->getName(), $this->plugin->freeze)]);

                    $entity->sendTitle(TextFormat::colorize($messages->get("unfreeze-title")));
                    $entity->sendMessage(TextFormat::colorize(str_replace(["{player}", "{staff}"], [$entity->getName(), $damager->getName()], $messages->get("unfreeze-message"))));
                    StaffMode::getInstance()->getServer()->broadcastMessage(TextFormat::colorize(str_replace(["{player}", "{staff}"], [$entity->getName(), $damager->getName()], $messages->get("server-broadcast-unfreeze"))));
                    $event->cancel();
                }
            }
        }
        return;
    }
}