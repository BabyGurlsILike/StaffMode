<?php

namespace SlaxxyQC\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use SlaxxyQC\StaffMode;

class ChatCommand extends Command {

    public StaffMode $plugin;

    public function __construct(StaffMode $plugin) {
        parent::__construct("sc", "Cette command envoie un message a tout les staff !");
        parent::setPermission("staffmode.chat.cmd");
        parent::setAliases(["staffchat"]);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Cette command est disponible juste ig-game !");
            return;
        }

        if (!$sender->hasPermission("staffmode.chat.cmd")) {
            $sender->sendMessage(TextFormat::colorize("&6[Staff] &dTu n'a pas la permission pour executé cette command !"));
            return;
        }

        if (!isset ($args[0])) {
            $sender->sendMessage(TextFormat::colorize("&6[Staff] &dVérifiez que l’argument est placé correctement"));
            return;
        }
        
        $messages = new Config(StaffMode::getInstance()->getDataFolder()."messages.yml", Config::YAML);
        foreach (StaffMode::getInstance()->getServer()->getOnlinePlayers() as $players) {
            if ($players->hasPermission("staffmode.chat.cmd")) {
                $players->sendMessage(TextFormat::colorize(str_replace(["{staff}", "{message}"], [$sender->getName(), implode(" ", $args)], $messages->get("staffchat-send-message"))));
            }
            return;
        }
        return;
    }
}