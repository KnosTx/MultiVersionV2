<?php

declare(strict_types=1);

namespace MultiVersionV2;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

/**
 * Manages plugin configurations.
 */
class ConfigLoader{

    private PluginBase $plugin;
    private array $config;
    private array $versionMap;

    public function __construct(PluginBase $plugin){
        $this->plugin = $plugin;
        $this->loadConfigs();
    }

    /**
     * Loads configuration files.
     *
     * @return void
     */
    private function loadConfigs(): void{
        $configPath = $this->plugin->getDataFolder() . "config.yml";
        $versionMapPath = $this->plugin->getDataFolder() . "versionMap.yml";

        $this->config = (new Config($configPath, Config::YAML))->getAll();
        $this->versionMap = (new Config($versionMapPath, Config::YAML))->getAll();
    }

    /**
     * Returns the version map configuration.
     *
     * @return array
     */
    public function getVersionMap(): array{
        return $this->versionMap;
    }

    /**
     * Checks if players should be notified.
     *
     * @return bool
     */
    public function shouldNotifyPlayers(): bool{
        return $this->config["notifyPlayers"] ?? false;
    }

    /**
     * Checks if a welcome message should be sent.
     *
     * @return bool
     */
    public function shouldSendWelcomeMessage(): bool{
        return $this->config["sendWelcomeMessage"] ?? false;
    }
}
