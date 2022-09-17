<?php

namespace SlaxxyQC\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use SlaxxyQC\StaffMode;

class FreezeCommand extends Command {

    public StaffMode $plugin;

    public function __construct(StaffMode $plugin) {
        parent::__construct("freeze", "Cette command freeze un joueur(s)");
        parent::setPermission("staffmode.freeze.cmd");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Cette command est juste disponible ig-game !");
            return;
        }

        if (!$sender->hasPermission("staffmode.freeze.cmd")) {
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

        $config = new Config(StaffMode::getInstance()->getDataFolder()."config.yml", Config::YAML);
        $messages = new Config(StaffMode::getInstance()->getDataFolder()."messages.yml", Config::YAML);
        if ($config->get("not-freeze-yourself") === true) {
            if ($player->getName() === $sender->getName()) {
                $sender->sendMessage(TextFormat::colorize("&6[Staff] &dUne erreur c'est produite quand vous avez essayer de freezer ce joueur !"));
                return;
            }
        }
        if (!in_array ($player->getName(), $this->plugin->freeze)) {
            $this->plugin->freeze[] = $player->getName();

            if ($config->get("allow-title-freeze") === true) {
                $player->sendTitle(TextFormat::colorize($messages->get("freeze-title")));
            }
                
            if ($config->get("allow-message-freeze") === true) {
                $player->sendMessage(TextFormat::colorize(str_replace(["{player}", "{staff}"], [$player->getName(), $sender->getName()], $messages->get("freeze-message"))));
            }
                
            if ($config->get("allows-broadcast-freeze") === true) {
                StaffMode::getInstance()->getServer()->broadcastMessage(TextFormat::colorize(str_replace(["{player}", "{staff}"], [$player->getName(), $sender->getName()], $messages->get("server-broadcast-freeze"))));
            }
        } else if (in_array ($player->getName(), $this->plugin->freeze)) {
            unset($this->plugin->freeze[array_search($player->getName(), $this->plugin->freeze)]);

            if ($config->get("allow-title-freeze") === true) {
                $player->sendTitle(TextFormat::colorize($messages->get("unfreeze-title")));
            }
                
            if ($config->get("allow-message-freeze") === true) {
                $player->sendMessage(TextFormat::colorize(str_replace(["{player}", "{staff}"], [$player->getName(), $sender->getName()], $messages->get("unfreeze-message"))));
            }
                
            if ($config->get("allows-broadcast-freeze") === true) {
                StaffMode::getInstance()->getServer()->broadcastMessage(TextFormat::colorize(str_replace(["{player}", "{staff}"], [$player->getName(), $sender->getName()], $messages->get("server-broadcast-unfreeze"))));
            }
        }
        return;
    }
}