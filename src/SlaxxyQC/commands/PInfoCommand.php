<?php

namespace SlaxxyQC\commands;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use SlaxxyQC\StaffMode;

class PInfoCommand extends Command {

    public StaffMode $plugin;

    public function __construct(StaffMode $plugin) {
        parent::__construct("pinfo", "Cette command donne des information sur un joueur(s)");
        parent::setPermission("staffmode.pinfo.cmd");
        parent::setAliases(["playerinfo"]);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Cette command est juste disponible ig-game !");
            return;
        }

        if (!$sender->hasPermission("staffmode.pinfo.cmd")) {
            $sender->sendMessage(TextFormat::colorize("&6[Staff] &dTu n'a pas la permission de executé cette command !"));
            return;
        }

        if (!isset ($args[0])) {
            $sender->sendMessage(TextFormat::colorize("&6[Staff] &dVérifiez que l’argument est placé correctement"));
            return;
        }

        $player = StaffMode::getInstance()->getServer()->getPlayerByPrefix(array_shift($args));
        if (!$player instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&6[Staff] &dCe joueur n'existe pas !"));
            return;
        }

        $messages = new Config(StaffMode::getInstance()->getDataFolder()."messages.yml", Config::YAML);
        $sender->sendMessage(TextFormat::colorize(str_replace(["{player}", "{ping}", "{health}", "{address}", "{platform}"], [$player->getName(), $player->getNetworkSession()->getPing(), (int)$player->getHealth(), $player->getNetworkSession()->getIp(), StaffMode::getInstance()->getUtilsManager()->getPlayerPlatform($player)], $messages->get("pinfo-message"))));
        return;
    }
}