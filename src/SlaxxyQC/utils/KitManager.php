<?php

namespace SlaxxyQC\utils;

use pocketmine\player\Player;
use pocketmine\item\ItemFactory;
use pocketmine\utils\TextFormat;

class KitManager {

    public function __construct() {}

    public function getKitStaff(Player $player) {
        $teleport = ItemFactory::getInstance()->get(345, 0, 1);
        $teleport->setCustomName(TextFormat::colorize("&6Ce TP sur un Joueur(s)"));
        $teleport->getNamedTag()->setString("staff-tool", "staff-tools");

        $randomtp = ItemFactory::getInstance()->get(341, 0, 1);
        $randomtp->setCustomName(TextFormat::colorize("&6Ce TP aléatoirement sur un Joueur(s)"));
        $randomtp->getNamedTag()->setString("staff-tool", "staff-tools");

        $pinfo = ItemFactory::getInstance()->get(352, 0, 1);
        $pinfo->setCustomName(TextFormat::colorize("&6Obtenir les Info du joueur"));
        $pinfo->getNamedTag()->setString("staff-tool", "staff-tools");

        $kick = ItemFactory::getInstance()->get(336, 0, 1);
        $kick->setCustomName(TextFormat::colorize("&6Kick le Joueur(s)"));
        $kick->getNamedTag()->setString("staff-tool", "staff-tools");

        $vanish = ItemFactory::getInstance()->get(351, 10, 1);
        $vanish->setCustomName(TextFormat::colorize("&6Activée le Vanish"));
        $vanish->getNamedTag()->setString("staff-tool", "staff-tools");

        $freeze = ItemFactory::getInstance()->get(174, 0, 1);
        $freeze->setCustomName(TextFormat::colorize("&6Freeze un Joueur(s)"));
        $freeze->getNamedTag()->setString("staff-tool", "staff-tools");

        $player->getInventory()->setItem(0, $teleport);
        $player->getInventory()->setItem(1, $randomtp);
        $player->getInventory()->setItem(2, $pinfo);
        $player->getInventory()->setItem(3, $kick);
        $player->getInventory()->setItem(4, $vanish);
        $player->getInventory()->setItem(5, $freeze);
    }

    public function getKitVanish(Player $player) {
        $teleport = ItemFactory::getInstance()->get(345, 0, 1);
        $teleport->setCustomName(TextFormat::colorize("&6Ce TP sur un Joueur(s)"));
        $teleport->getNamedTag()->setString("staff-tool", "staff-tools");

        $randomtp = ItemFactory::getInstance()->get(341, 0, 1);
        $randomtp->setCustomName(TextFormat::colorize("&6Ce TP aléatoirement sur un Joueur(s)"));
        $randomtp->getNamedTag()->setString("staff-tool", "staff-tools");

        $pinfo = ItemFactory::getInstance()->get(352, 0, 1);
        $pinfo->setCustomName(TextFormat::colorize("&6Obtenir les Info du joueur"));
        $pinfo->getNamedTag()->setString("staff-tool", "staff-tools");

        $kick = ItemFactory::getInstance()->get(336, 0, 1);
        $kick->setCustomName(TextFormat::colorize("&6Kick le Joueur(s)"));
        $kick->getNamedTag()->setString("staff-tool", "staff-tools");

        $vanish = ItemFactory::getInstance()->get(351, 1, 1);
        $vanish->setCustomName(TextFormat::colorize("&6Activée le Vanish"));
        $vanish->getNamedTag()->setString("staff-tool", "staff-tools");

        $freeze = ItemFactory::getInstance()->get(174, 0, 1);
        $freeze->setCustomName(TextFormat::colorize("&6Freeze un Joueur(s)"));
        $freeze->getNamedTag()->setString("staff-tool", "staff-tools");

        $player->getInventory()->setItem(0, $teleport);
        $player->getInventory()->setItem(1, $randomtp);
        $player->getInventory()->setItem(2, $pinfo);
        $player->getInventory()->setItem(3, $kick);
        $player->getInventory()->setItem(4, $vanish);
        $player->getInventory()->setItem(5, $freeze);
    }
}