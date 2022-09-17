<?php

namespace SlaxxyQC\utils;

use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\player\Player;

class UtilsManager {

    public function __construct() {}

    public function getPlayerPlatform(Player $player) : string {
        $data = $player->getPlayerInfo()->getExtraData();
        if ($data["DeviceOS"] === DeviceOS::ANDROID && $data["DeviceModel"] === "Not Registered") {
            return "Linux";
        }

        return match($data["DeviceOS"]) {
            DeviceOS::ANDROID => "Android", DeviceOS::IOS => "iOS", DeviceOS::OSX => "Mac", DeviceOS::XBOX => "Xbox", DeviceOS::PLAYSTATION => "PlayStation", DeviceOS::WINDOWS_10 => "Windows 10", DeviceOS::WIN32 => "Windows 32", DeviceOS::NINTENDO => "Nintendo", default => "Unknown"
        };
    }
}