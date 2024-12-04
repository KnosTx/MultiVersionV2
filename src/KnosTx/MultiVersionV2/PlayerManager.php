<?php

declare(strict_types=1);

namespace MultiVersionV2;

use pocketmine\player\Player;

/**
 * Handles player-specific interactions.
 */
class PlayerManager{
    private ConfigLoader $configLoader;

    public function __construct(ConfigLoader $configLoader){
        $this->configLoader = $configLoader;
    }

    public function handlePlayerJoin(Player $player): void{
        if($this->configLoader->shouldSendWelcomeMessage()){
            $player->sendMessage("Welcome to the server, {$player->getName()}!");
        }
    }
}
