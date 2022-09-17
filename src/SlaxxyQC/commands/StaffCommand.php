<?php

namespace SlaxxyQC\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

use SlaxxyQC\StaffMode;
use pocketmine\player\GameMode;

class StaffCommand extends Command {

    public StaffMode $plugin;

    public function __construct(StaffMode $plugin) {
        parent::__construct("staff", "Cette command ouvre le Menu StaffMode");
        parent::setPermission("staffmode.staff.cmd");
        parent::setAliases(["mod", "staffmode", "sm"]);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Cette command est juste disponible ig-game !");
            return;
        }

        if (!$sender->hasPermission("staffmode.staff.cmd")) {
            $sender->sendMessage(TextFormat::colorize("&6[Staff] &dTu n'a pas la permission de executé cette command !"));
            return;
        }
        
        $config = new Config(StaffMode::getInstance()->getDataFolder()."config.yml", Config::YAML);
        $messages = new Config(StaffMode::getInstance()->getDataFolder()."messages.yml", Config::YAML);
        if (!in_array ($sender->getName(), $this->plugin->staffmode)) {
            $this->plugin->staffmode[] = $sender->getName();
            $this->plugin->backup_items[$sender->getName()] = $sender->getInventory()->getContents();
            $this->plugin->backup_armor[$sender->getName()] = $sender->getArmorInventory()->getContents();
            $this->plugin->backup_gamemode[$sender->getName()] = $sender->getGamemode();

            if ($config->get("change-gamemode-in-staffmode") === true) {
                $sender->setGamemode(GameMode::CREATIVE());
            }
            
            if ($config->get("allow-flight-in-staffmode") === true) {
                $sender->setAllowFlight(true);
            }
            
            if ($config->get("allow-title-staffmode") === true) {
                $sender->sendTitle(TextFormat::colorize($messages->get("staffmode-title-enabled")));
            }
            
            if ($config->get("allow-message-staffmode") === true) {
                $sender->sendMessage(TextFormat::colorize($messages->get("staffmode-message-enabled")));
            }

            $sender->getInventory()->clearAll();
            $sender->getArmorInventory()->clearAll();
            $sender->getEffects()->clear();
            $sender->extinguish();
            $sender->setSilent(true);

            StaffMode::getKitManager()->getKitStaff($sender);
        } else if (in_array ($sender->getName(), $this->plugin->staffmode)) {
            $sender->getInventory()->clearAll();
            $sender->getArmorInventory()->clearAll();
            $sender->getEffects()->clear();
            $sender->extinguish();
            $sender->setFlying(false);
            $sender->setAllowFlight(false);
            $sender->setSilent(false);

            unset($this->plugin->staffmode[array_search($sender->getName(), $this->plugin->staffmode)]);
            $sender->getInventory()->setContents($this->plugin->backup_items[$sender->getName()]);
            $sender->getArmorInventory()->setContents($this->plugin->backup_armor[$sender->getName()]);
            $sender->setGamemode($this->plugin->backup_gamemode[$sender->getName()]);

            if ($config->get("allow-title-staffmode") === true) {
                $sender->sendTitle(TextFormat::colorize($messages->get("staffmode-title-disabled")));
            }
            
            if ($config->get("allow-message-staffmode") === true) {
                $sender->sendMessage(TextFormat::colorize($messages->get("staffmode-message-disabled")));
            }

            foreach (StaffMode::getInstance()->getServer()->getOnlinePlayers() as $players) {
                $players->showPlayer($sender);
            }
        }
        return;
    }
}