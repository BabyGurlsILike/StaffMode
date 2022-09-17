<?php

namespace SlaxxyQC;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use SlaxxyQC\listeners\PlayerListener;
use SlaxxyQC\listeners\StaffListener;
use SlaxxyQC\listeners\ItemListener;

use SlaxxyQC\commands\StaffCommand;
use SlaxxyQC\commands\ChatCommand;
use SlaxxyQC\commands\FreezeCommand;
use SlaxxyQC\commands\PInfoCommand;

use SlaxxyQC\utils\KitManager;
use SlaxxyQC\utils\UtilsManager;

class StaffMode extends PluginBase implements Listener {

    public $staffmode = [];
    public $backup_items = [];
    public $backup_armor = [];
    public $backup_gamemode = [];

    public $freeze = [];

    private static $instance;

    public static function getInstance() : StaffMode {
        return self::$instance;
    }

    public function onLoad() : void {
        self::$instance = $this;

        $this->saveResource("config.yml");
        $this->saveResource("messages.yml");
    }

    public function onEnable() : void {

        $plugin = $this->getServer()->getPluginManager();
        $this->getLogger()->notice("StaffMode By SlaxxyQC");
        $plugin->registerEvents($this, $this);
        $plugin->registerEvents(new PlayerListener($this), $this);
        $plugin->registerEvents(new StaffListener($this), $this);
        $plugin->registerEvents(new ItemListener($this), $this);

        $command = $this->getServer()->getCommandMap();
        $command->register("StaffMode", new StaffCommand($this));
        $command->register("StaffMode", new ChatCommand($this));
        $command->register("StaffMode", new FreezeCommand($this));
        $command->register("StaffMode", new PInfoCommand($this));
    }

    public static function getKitManager() : KitManager {
        return new KitManager();
    }

    public static function getUtilsManager() : UtilsManager {
        return new UtilsManager();
    }
}