<?php

namespace Itb\Helpers;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;

class HlblockHelper
{
    static $hlblockCodeIdMap = [];
    static $hlblockCodeClassMap = [];

    /**
     * Получает id хайлоадблока по его имени
     *
     * @param string $hlblockName
     *
     * @return int
     */
    public static function getHlblockIdByName(string $hlblockName): int
    {
        if (!isset(static::$hlblockCodeIdMap[$hlblockName])) {
            $id = HighloadBlockTable::getList([
                'select' => ['ID'],
                'filter' => ['NAME' => $hlblockName],
                'cache' => ['ttl' => 86400]
            ])->fetch()['ID'];

            if (!$id) {
                throw new \Exception("hlblock with name $hlblockName not found");
            }

            static::$hlblockCodeIdMap[$hlblockName] = $id;
        }

        return static::$hlblockCodeIdMap[$hlblockName];
    }

    /**
     * Получает класс хайлоад блока по его имени
     *
     * @param string $hlblockName
     *
     * @return string
     */
    public static function getHlblockByName(string $hlblockName): string
    {
        if (!isset(static::$hlblockCodeClassMap[$hlblockName])) {
            Loader::includeModule("highloadblock"); 

            $hlblock = HighloadBlockTable::getList([
                'select' => ['*'],
                'filter' => ['NAME' => $hlblockName],
                'cache' => ['ttl' => 86400]
            ])->fetch(); 

            if (!$hlblock) {
                throw new Exception("hlblock with name $hlblockName not found");
            }
    
            $entity = HighloadBlockTable::compileEntity($hlblock); 
            $entity_data_class = $entity->getDataClass(); 

            static::$hlblockCodeClassMap[$hlblockName] = $entity_data_class;
        }

        return static::$hlblockCodeClassMap[$hlblockName];
    }
}
