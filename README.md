## Версия для vue ssr

Когда делаете build на продакшен, убедитесь что занесли файл в build.rollupOptions.input в vite.config.client.js для клиентского кода и в vite.config.server.js для серверных страниц

метод для получения html с node ssr ```getSsrContent```, параметром передается страница которая должа быть получена название страницы соответствует ключу из build.rollupOptions.input файла vite.config.server.js

```php
Vite::getSsrContent('test')
```

## node js сервер

файл с node js сервером располагается в local/js/vite/server.js

страницы соответствуют ключам из build.rollupOptions.input (vite.config.server.js)

на продакшен сервере этот сервер должен быть запущен, а порт сервера задается в файле .env

для запуска сервера можно использовать пакет pm2 - https://www.npmjs.com/package/pm2

так же запустить сервер можно командой ```npm run ssr-server``` в local/js/vite

## Пример страницы с vue ssr

```php
use Itb\Core\Assets\Vite;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
Vite::getInstance()->includeAssets([ // подключаем ассеты
    'src/pages/test/entry-client.js',
    'src/common/js/bundle.js'
]);
$content = Vite::getSsrContent('test'); // делаем запрос на node сервер за html текущей страницы, страница test - это ключ из build.rollupOptions.input
echo "<div id='app'>{$content}</div>" ?? '<div id="app"></div>'; // если html вернулся то помещаем его в контейнер иначе создаем пустой контейнер
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
```


