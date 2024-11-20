# SSR с помощью grpc и в целом работа с grpc

## Создание клиентских классов

Модуль grpc должен быть установлен т.к. он предоставляет php классы необходимые для работы.

1. через apt get или какой-либо другой пакетный менеджен на сервере должны быть установлены пакеты - ``` protobuf-compiler ``` и ```protobuf-compiler-grpc```. 
2. установить и активировать php расширения - ``` protobuf ``` и ``` grpc ```
Пример установки в dockerfile: 

```dockerfile
RUN apt-get update && apt-get install -y \
    apt-utils \
    protobuf-compiler \
    protobuf-compiler-grpc \
    && docker-php-ext-install pdo_mysql mysqli bcmath

RUN pecl install protobuf
RUN pecl install grpc
RUN docker-php-ext-enable grpc
RUN docker-php-ext-enable protobuf
```

3. в php.ini включить расширение - ``` extension=protobuf.so ```
4. В консоли можно проверить доступность плагина - ``` which grpc_php_plugin ``` эта команда должна вернуть путь к плагину, который нужно использовать в команде для генерации классов
5. Для создания классов нужно создать файл .proto, пример можно увидеть в /local/proto/ssr.proto
6. Для создания классов перейти в local и выполнить команду:
7. Установить пакеты из composer.json и настроить базовый autoload на lib.
8. Сгенерировать классы с помощью команды, в примере команда выполняется в директории ```local```

```bash
protoc --php_out=./lib --grpc_out=./lib ./proto/ssr.proto --plugin=protoc-gen-grpc={path/to/grpc_php_plugin}
```

в данном случае классы должны появиться в в директории local/lib. Но по умолчанию в репозитории я эти классы уже оставил.

Пример использования созданных классов надодиться в local/lib/Itb/Ssr/SsrService.php

## Пример создания сервера grpc

Пример файла server.js для node можно найти в local/js/vite/server.js

для его работы необходимо установить пакеты - ```@grpc/grpc-js``` и ```@grpc/proto-loader``` (в package.json все описано)

## Общие настройки 
 
- Необходимо создать файл .env в local/php_interface/include на основе файла .env.example
- Установить модуль itb.core - https://git.itb-dev.ru/ITB-dev/itb.core

## Получения html
функция ```getContent``` принимает название страницы и массив данных.

```php
Itb\Ssr\SsrService::getContent('test', null|$data[])
```

Название страницы соответствует ключу из ```build.rollupOptions.input``` из ```vite.config.server```