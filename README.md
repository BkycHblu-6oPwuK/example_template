typesctipt версия сборки vite c ветки мастер.

Настроена работа с typescript как для обычных файлов ts так и для vue

Сборка проекта ничем не отличается.

Подключение js/ts через класс Vite так же работает.

$vite = Vite::getInstance(getenv('VITE_BASE_PATH'), MANIFEST_PATH, IS_PRODUCTION, getenv('VITE_PORT'));

$vite->includeAssets([
	'src/common/js/bundle.ts',
]);