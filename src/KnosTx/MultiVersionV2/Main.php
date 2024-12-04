<?php

declare(strict_types=1);

namespace KnosTx\MultiVersionV2;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\RequestNetworkSettingsPacket;

class Main extends PluginBase implements Listener {

    private ProtocolHandler $protocolHandler;
    private ConfigLoader $configLoader;
    private PlayerManager $playerManager;
    private ?RequestNetworkSettingsPacket $requestNetworkSettings = null;

    public function onEnable() : void {
        $this->saveDefaultResources();

        $this->configLoader = new ConfigLoader($this);
        $this->protocolHandler = new ProtocolHandler($this, $this->configLoader);
        $this->playerManager = new PlayerManager($this->protocolHandler, $this->configLoader);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->requestNetworkSettings = new RequestNetworkSettingsPacket();
    }

    private function saveDefaultResources() : void {
        $resources = ["config.yml", "versionMap.yml"];
        foreach ($resources as $resource) {
            if (!$this->getResource($resource)) {
                $this->saveResource($resource);
            }
        }
    }

    public function onPlayerPreLogin(PlayerPreLoginEvent $event) : void {
        $playerInfo = $event->getPlayerInfo();

        $protocol = $this->requestNetworkSettings?->getProtocolVersion() ?? 0;

        if (!$this->protocolHandler->loadDataForProtocol($protocol)) {
            $this->getLogger()->warning("Unsupported protocol {$protocol}. Using default fallback for {$playerInfo->getUsername()}.");
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        $this->playerManager->handlePlayerJoin($player);
    }

    public function getRequestNetworkSettings() : ?RequestNetworkSettingsPacket {
        return $this->requestNetworkSettings;
    }

    public function getPluginFile() : string {
        return $this->getFile();
    }
}
