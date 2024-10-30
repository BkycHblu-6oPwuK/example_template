## Версия для vue ssr

Когда делаете build на продакшен, убедитесь что занесли файл в build.rollupOptions.input в vite.config.client.js для клиентского кода и в vite.config.server.js для серверных страниц

метод для получения html с node ssr ```getSsrContent```, параметром передается страница которая должа быть получена название страницы соответствует ключу из build.rollupOptions.input файла vite.config.server.js. Второй параметр - это данные для вашей vue страницы

Метод выполняет обычный http запрос с помощью curl на сервер c node.

Вариант ssr с grpc - https://git.itb-dev.ru/ITB-dev/ssr_grpc

```php
Vite::getSsrContent('test', []|null)
```

При разработке точки входа для приложения на ssr сервере в вашем файле должна экспортироваться функция ```render```, сервер первым параметром будет прокидывать переданные данные 

```js
export function render(data) {
  const app = createApp(data)
  const ctx = {}
  const stream = renderToWebStream(app, ctx)
  return { stream }
}
```

## node js сервер

файл с node js сервером располагается в local/js/vite/server.js

страницы соответствуют ключам из build.rollupOptions.input (vite.config.server.js)

на продакшен сервере этот сервер должен быть запущен, а порт сервера задается в файле .env

для запуска сервера можно использовать пакет pm2 - https://www.npmjs.com/package/pm2

так же запустить сервер можно командой ```npm run ssr-server``` в local/js/vite

## Пример страницы с vue ssr

```php
use Bitrix\Main\Web\Json;
use Itb\Core\Assets\Vite;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Интернет-магазин \"Одежда\"");
Vite::getInstance()->includeAssets([
    'src/pages/test/entry-client.js',
    'src/common/js/bundle.js'
]);
$data = ['msg' => 'текст для вывода1'];
$content = Vite::getSsrContent('test', $data);
echo "<div id='app'>{$content}</div>" ?? '<div id="app"></div>';
?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.vueApps.createTestPage(<?=Json::encode($data)?>).mount('#app')
    })
</script>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
```


