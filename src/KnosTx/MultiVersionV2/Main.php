<?php

declare(strict_types=1);

namespace MultiVersionV2;

use pocketmine\player\OfflinePlayer;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use MultiVersionV2\ConfigLoader;
use MultiVersionV2\ProtocolHandler;
use MultiVersionV2\PlayerManager;

/**
 * Main class for MultiVersionV2.
 * Handles plugin initialization and event registration.
 */
class Main extends PluginBase implements Listener{

    /** @var ProtocolHandler */
    private ProtocolHandler $protocolHandler;

    /** @var ConfigLoader */
    private ConfigLoader $configLoader;

    /** @var PlayerManager */
    private PlayerManager $playerManager;

    /**
     * Called when the plugin is enabled.
     *
     * @return void
     */
    public function onEnable(): void{
        $this->saveDefaultResources();

        $this->configLoader = new ConfigLoader($this);
        $this->protocolHandler = new ProtocolHandler($this, $this->configLoader);
        $this->playerManager = new PlayerManager($this->protocolHandler, $this->configLoader);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * Saves default resources from the plugin's `resources` folder.
     *
     * @return void
     */
    private function saveDefaultResources(): void{
        $this->saveResource("config.yml");
        $this->saveResource("versionMap.yml");
    }

    /**
     * Handles the PlayerPreLoginEvent to load protocol-specific data.
     *
     * @param PlayerPreLoginEvent $event
     * @param Player $players
     * @return void
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event, OfflinePlayer $players): void{
        $player = $players->getPlayer();
        $protocol = $players->getNetworkSession()->getProtocol();

        if(!$this->protocolHandler->loadDataForProtocol($protocol)){
            $this->getLogger()->warning("Unsupported protocol {$protocol}. Using default fallback for {$players->getName()}.");
        }
    }

    /**
     * Handles the PlayerJoinEvent to send messages or notifications to players.
     *
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void{
        $player = $event->getPlayer();
        $this->playerManager->handlePlayerJoin($player);
    }
}
