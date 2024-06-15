<?php

namespace Itb\Assets;

use Bitrix\Main\Page\Asset;

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

    /**
     * инициализация параметров необходимых для работы класса
     */
    private function initialize($basePath, $manifestPath, $isProduction, $vitePort)
    {
        $this->basePath = $basePath;
        $this->manifestPath = $manifestPath;
        $this->isProduction = $isProduction;
        if ($isProduction) {
            $this->loadManifest();
        } else {
            $this->localhostBasePath = "http://localhost:{$vitePort}{$basePath}";
        }
    }

    /**
     * Получает объект класса и меняет параметры если переданные были изменены
     * @param string $basePath определяем в .env - VITE_BASE_PATH
     * @param string $manifestPath $_SERVER['DOCUMENT_ROOT'] . $basePath . '.vite/manifest.json';
     * @param bool $isProduction определяем в .env - MODE и передаем getenv('MODE') === 'production';
     * @param int $vitePort определяем в .env - VITE_PORT, нужен только для подключения в режиме разработки;
     */
    public static function getInstance(string $basePath, string $manifestPath, bool $isProduction, int $vitePort = 5173)
    {
        if (static::$instance === null) {
            static::$instance = new static();
            static::$instance->initialize($basePath, $manifestPath, $isProduction, $vitePort);
        } else {
            if (
                static::$instance->basePath !== $basePath ||
                static::$instance->manifestPath !== $manifestPath ||
                static::$instance->isProduction !== $isProduction
            ) {
                static::$instance->initialize($basePath, $manifestPath, $isProduction, $vitePort);
            }
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
