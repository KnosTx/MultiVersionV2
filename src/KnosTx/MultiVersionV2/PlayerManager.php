<?php

declare(strict_types=1);

namespace MultiVersionV2;

use pocketmine\player\Player;

/**
 * Handles player-specific interactions.
 */
class PlayerManager{

    private ProtocolHandler $protocolHandler;
    private ConfigLoader $configLoader;

    public function __construct(ProtocolHandler $protocolHandler, ConfigLoader $configLoader){
        $this->protocolHandler = $protocolHandler;
        $this->configLoader = $configLoader;
    }

    public function handlePlayerJoin(Player $player): void{
        if($this->configLoader->shouldSendWelcomeMessage()){
            $player->sendMessage("Welcome to the server, {$player->getName()}!");
        }
    }

    public function getProtocolHandler() : ProtocolHandler{
        return $this->protocolHandler;
    }
}
