<?php

declare(strict_types=1);

namespace MultiVersionV2;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\RequestNetworkSettingsPacket;

class Main extends PluginBase implements Listener {

	private ProtocolHandler $protocolHandler;
	private ConfigLoader $configLoader;
	private PlayerManager $playerManager;
	private RequestNetworkSettingsPacket $networkSession;

    public function onEnable() : void {
        $this->saveDefaultResources();

        $this->configLoader = new ConfigLoader($this);
        $this->protocolHandler = new ProtocolHandler($this, $this->configLoader);
        $this->playerManager = new PlayerManager($this->protocolHandler, $this->configLoader);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    private function saveDefaultResources() : void {
        $this->saveResource("config.yml");
        $this->saveResource("versionMap.yml");
    }

    public function onPlayerPreLogin(PlayerPreLoginEvent $event) : void {
        $playerInfo = $event->getPlayerInfo();
        $protocol = $this->getNetworkSession()->getProtocolVersion();

        if (!$this->protocolHandler->loadDataForProtocol($protocol)) {
            $this->getLogger()->warning("Unsupported protocol {$protocol}. Using default fallback for {$playerInfo->getUsername()}.");
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        $this->playerManager->handlePlayerJoin($player);
    }

    public function getNetworkSession() : RequestNetworkSettingsPacket{
        return $this->networkSession;
    }

    public function getRequestNetworkSettings() : RequestNetworkSettingsPacket{
	 $networkSession = $this->networkSession;
	 return $this->networkSession->getProtocolVersion();
    }
}
