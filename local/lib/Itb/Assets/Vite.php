<?php

namespace Itb\Assets;

use Bitrix\Main\Page\Asset;
use InvalidArgumentException;

/**
 * Класс для подключения js и css из vite. В vite.config должен быть manifest: true
 */
class Vite
{
    private $manifestPath;
    private $basePath;
    private $manifest;
    private $isProduction = true;
    private $localhostBasePath;
    private $viteClientIsIncluded = false;
    private static $instance;

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    /**
     * инициализация параметров необходимых для работы класса
     */
    private function initialize()
    {
        $this->basePath = $this->getEnvVar('VITE_BASE_PATH');
        $this->isProduction = $this->getEnvVar('MODE') === 'production';
        $this->manifestPath = $_SERVER['DOCUMENT_ROOT'] . $this->basePath . '.vite/manifest.json';
    
        if ($this->isProduction) {
            $this->loadManifest();
        } else {
            $vitePort = $this->getEnvVar('VITE_PORT');
            $this->localhostBasePath = "http://localhost:{$vitePort}{$this->basePath}";
        }
    }

    protected function getEnvVar($varName)
    {
        $value = getenv($varName);
        if ($value === false) {
            throw new InvalidArgumentException("Environment variable '{$varName}' is not set or is empty.");
        }
        return $value;
    }
    

    /**
     * Возвращает объект класса
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
            static::$instance->initialize();
        }
        return static::$instance;
    }


    /**
     * Загружает manifest.json
     *
     * @return void
     */
    private function loadManifest(): void
    {
        if (!file_exists($this->manifestPath)) {
            throw new \Exception("Manifest file not found: " . $this->manifestPath);
        }

        $this->manifest = json_decode(file_get_contents($this->manifestPath), true);
        if ($this->manifest === null) {
            throw new \Exception("Failed to decode manifest file: " . json_last_error_msg());
        }
    }

    /**
     * Рекурсивно обходит импорты и заносит пути до css файлов в массив
     *
     * @param string $entry
     * @param array $cssFiles
     *
     * @return void
     */
    private function collectCssImports($entry, &$cssFiles): void
    {
        if (isset($this->manifest[$entry])) {
            $asset = $this->manifest[$entry];
            if (isset($asset['css'])) {
                foreach ($asset['css'] as $cssFile) {
                    $cssFiles[] = $this->basePath . $cssFile;
                }
            }
            if (isset($asset['imports'])) {
                foreach ($asset['imports'] as $import) {
                    $this->collectCssImports($import, $cssFiles);
                }
            }
        }
    }

    /**
     * Получает пути до js и css файлов. Для prod среды js и css. Для dev только js, css импортируем в js
     *
     * @param array $entries
     *
     * @return array
     */
    protected function getAssetPaths(array $entries): array
    {
        $assets = [
            'js' => [],
            'css' => []
        ];

        if ($this->isProduction) {
            foreach ($entries as $entry) {
                if (isset($this->manifest[$entry])) {
                    $asset = $this->manifest[$entry];
                    $assets['js'][] = $this->basePath . $asset['file'];
                    $this->collectCssImports($entry, $assets['css']);
                }
            }
        } else {
            if(!$this->viteClientIsIncluded){
                $assets['js'][] = $this->localhostBasePath . '@vite/client';
                $this->viteClientIsIncluded = true;
            }
            foreach ($entries as $entry) {
                $assets['js'][] = $this->localhostBasePath . $entry;
            }
        }

        return $assets;
    }

    /**
     * Подключает js type module через Asset::addString и css через Asset::addCss. Для prod среды js и css. Для dev только js, css импортируем в js
     *
     * @param string[] $entries относительно директории в которой расположен vite
     *
     * @return void
     */
    public function includeAssets(array $entries): void
    {
        $assets = $this->getAssetPaths($entries);
        $bitrixAssetObj = Asset::getInstance();
        foreach ($assets['js'] as $jsFile) {
            $jsFile = htmlspecialchars($jsFile, ENT_QUOTES);
            $bitrixAssetObj->addString("<script type='module' src='{$jsFile}'></script>");
        }
        if ($this->isProduction && !empty($assets['css'])) {
            foreach ($assets['css'] as $cssFile) {
                $bitrixAssetObj->addCss(htmlspecialchars($cssFile, ENT_QUOTES));
            }
        }
    }
}
