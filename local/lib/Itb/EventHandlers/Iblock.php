<?php
namespace Itb\EventHandlers;

class Iblock
{
    public static function onAfterIBlockElementSetPropertyValues($elementId, $iblockId)
    {
        \CIBlock::clearIblockTagCache($iblockId);
    }

    public static function onAfterIBlockElementSetPropertyValuesEx($elementId, $iblockId)
    {
        \CIBlock::clearIblockTagCache($iblockId);
    }

    public static function onBeforeIBlockElementAdd(&$arParams)
    {
        
    }

    public static function onBeforeIBlockElementUpdate(&$arParams)
    {
        
    }
}