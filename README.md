# wapp_avto_express_debugger

[![](https://asdertasd.site/counter/wapp_avto_express_debugger?a=1)](https://asdertasd.site/counter/wapp_avto_express_debugger)

Веб-приложение, отладчик для проектов.

## Использовано

- easyui https://www.jeasyui.com/
- jquery
- иконки https://famfamfam.com
- redbeans https://redbeanphp.com/index.php

## Как использовать

Нужно подключить к проекту файл Debugger.class.php.
И прописать все гужные директории в самом файле.

В проектах указать путь к директории логов. Логи ведутся в формате jsonl.

Для лога можно использовать функции

```
__d(DWS_MESSAGE, '$_SERVER', $_SERVER);
```

Для логирования и перехвата параметров
```
func(__log($a),__log($b));
```

## Быстрый запуск

```
php -S 0.0.0.0:80 -t .
# или 
docker-compose up -d
```

## Скриншоты

![](/screenshots/screenshot_01.png)

