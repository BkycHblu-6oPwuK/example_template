<?php

namespace Itb\Helpers;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Loader;

class IblockHelper
{
    static $iblockCodeIdMap = [];

    /**
     * Получает id инфоблока по его коду
     *
     * @param string $iblockCode
     *
     * @return int
     */
    public static function getIblockIdByCode(string $iblockCode): int
    {
        if (!isset(static::$iblockCodeIdMap[$iblockCode])) {
            Loader::includeModule('iblock');

            $id = IblockTable::getList([
                'select' => ['ID'],
                'filter' => ['CODE' => $iblockCode],
                'cache' => ['ttl' => 86400]
            ])->fetch()['ID'];

            if (!$id) {
                throw new \Exception("Iblock with code {$iblockCode} not found");
            }

            static::$iblockCodeIdMap[$iblockCode] = $id;
        }

        return static::$iblockCodeIdMap[$iblockCode];
    }

}
