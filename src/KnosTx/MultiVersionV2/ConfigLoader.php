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

    private function loadConfigs(): void{
        $this->config = (new Config($this->plugin->getFile() . "resources/config.yml", Config::YAML))->getAll();
        $this->versionMap = (new Config($this->plugin->getFile() . "resources/versionMap.yml", Config::YAML))->getAll();
    }

    public function getVersionMap(): array{
        return $this->versionMap;
    }

    public function shouldNotifyPlayers(): bool{
        return $this->config["notifyPlayers"] ?? false;
    }

    public function shouldSendWelcomeMessage(): bool{
        return $this->config["sendWelcomeMessage"] ?? false;
    }
}