<?php

declare(strict_types=1);

namespace MultiVersionV2;

use pocketmine\plugin\PluginBase;

/**
 * Handles protocol-specific item/block data.
 */
class ProtocolHandler{

    private PluginBase $plugin;
    private ConfigLoader $configLoader;
    private array $data = [];
    private array $defaultData = [];

    public function __construct(PluginBase $plugin, ConfigLoader $configLoader){
        $this->plugin = $plugin;
        $this->configLoader = $configLoader;
        $this->loadDefaultData();
    }

    private function loadDefaultData(): void{
        $defaultFile = $this->plugin->getFile() . "resources/default.json";
        $this->defaultData = json_decode(file_get_contents($defaultFile), true) ?? [];
    }

    public function loadDataForProtocol(int $protocol): bool{
        $fileName = $this->configLoader->getVersionMap()[$protocol] ?? null;
        if(!$fileName){
            $this->data = $this->defaultData;
            return false;
        }

        $filePath = $this->plugin->getFile() . "resources/" . $fileName;
        if(!file_exists($filePath)){
            $this->data = $this->defaultData;
            return false;
        }

        $this->data = json_decode(file_get_contents($filePath), true) ?? $this->defaultData;
        return true;
    }

    public function getRuntimeId(string $key): ?int{
        return $this->data[$key]["runtime_id"] ?? null;
    }
}