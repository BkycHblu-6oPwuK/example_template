<?php

use Itb\EventHandlers\Iblock;
use Itb\EventHandlers\Main;
use Itb\EventHandlers\Sale;

$eventManager = \Bitrix\Main\EventManager::getInstance();

// main
$eventManager->addEventHandler('main', 'OnBeforeProlog', [Main::class, 'onBeforeProlog']);
$eventManager->addEventHandler('main', 'OnBeforeUserAdd', [Main::class, 'onBeforeUserAdd']);
$eventManager->addEventHandler('main', 'OnAfterUserAdd', [Main::class, 'onAfterUserAdd']);

// sale
$eventManager->addEventHandler('sale', 'OnSaleOrderSaved', [Sale::class, 'onSaleOrderSaved']);
$eventManager->addEventHandler('sale', 'OnSaleOrderBeforeSaved', [Sale::class, 'OnSaleOrderBeforeSaved']);

// iblock
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementSetPropertyValues', [Iblock::class, 'onAfterIBlockElementSetPropertyValues']);
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementSetPropertyValuesEx', [Iblock::class, 'onAfterIBlockElementSetPropertyValuesEx']);
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', [Iblock::class, 'onBeforeIBlockElementAdd']);
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [Iblock::class, 'onBeforeIBlockElementUpdate']);
