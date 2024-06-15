node 20.7.0

Настроенная конфигурация 

По умолчанию размещаем в /local/js/vite. При необходимости можно изменить расположение, так же изменив путь в файле .env в local/php/interface/include/

VITE_BASE_PATH определяет путь до папки dist. Если используете докер, то соответствующие изменения пути нужно и внести там (там определяем до директории с package.json)

в public можно размещать общие ассеты которые будуте подключать в php файлах, например картинку подключить из php файла - "<div class="img"><img src="/local/js/vite/public/images/11.png"></div>"
Но не обязательно хранить такие ассеты в директории public
 
если работаете с ассетами в директории src, то там используется модульность, ассеты размещаете в директории assets и имортируете нужные ассеты в нужный файл. Например в main scss - background-image: url(@/assets/images/11.png);
(не забывайте что импорт картинок так же работает и в js/vue файлах). И тоже самое с шрифтами и прочим.

Эта картинка при билде на продакшен будет помещена в папку dist и сборщик сам установит нужный путь до файла в конечном css файле.

Для использования vue должны быть раскомментирован плагин vue

Для удобновго подключения js и css был разработан класс Itb\Assets\Vite который подключит css и js файлы как в режиме разработки и на боевом сервере

Пример использования в header.php:

$basePath = getenv('VITE_BASE_PATH');

$manifestPath = $_SERVER['DOCUMENT_ROOT'] . $basePath . '.vite/manifest.json';

$vite = new Vite($basePath,$manifestPath, IS_PRODUCTION, getenv('VITE_PORT'));

$vite->includeAssets([
	'src/common/js/bundle.js',
]);

Переменную IS_PRODUCTION можно определить в файле init.php - define('IS_PRODUCTION', getenv('MODE') === 'production'); (По умолчанию определена)