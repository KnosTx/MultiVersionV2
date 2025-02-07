<?php

declare(strict_types=1);

namespace KnosTx\MultiVersionV2;

/**
 * Handles protocol-specific item/block data.
 */
class ProtocolHandler{

    private Main $plugin;
    private ConfigLoader $configLoader;
    private array $data = [];
    private array $defaultData = [];

    public function __construct(Main $plugin, ConfigLoader $configLoader){
        $this->plugin = $plugin;
        $this->configLoader = $configLoader;
        $this->loadDefaultData();
    }

    private function loadDefaultData(): void{
        $defaultFile = $this->plugin->getPluginFile() . "resources/default.json";
        $this->defaultData = json_decode(file_get_contents($defaultFile), true) ?? [];
    }

    public function loadDataForProtocol(int $protocol): bool{
        $fileName = $this->configLoader->getVersionMap()[$protocol] ?? null;
        if(!$fileName){
            $this->data = $this->defaultData;
            return false;
        }

        $filePath = $this->plugin->getPluginFile() . "resources/" . $fileName;
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
