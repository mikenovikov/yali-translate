# yali-translate
Переводчик для Ubuntu(Linux)

![YaLi-Translate One Word](https://github.com/mikenovikov/yali-translate/blob/master/resources/example-word.jpg)
##Небольшой онлайн-переводчик, для линукса.
Использует сервисы Яндекс.Переводчик и Яндекс.Словарь.

*Пока умеет переводить только с английского и только на русский.*

![YaLi-Translate Sentence Word](https://github.com/mikenovikov/yali-translate/blob/master/resources/example-sentence.jpg)


## Установка.
- Установить нужные штуки **sudo apt-get install libnotify-bin xsel php5-cli**
- Любым убодным способом копировать все в **/home/username/.yali**
- Открыть терминал перейти в папку **.yali**
- Создать в этой папке файл **.env.php** в него добавить:

	```php
		<?php

		return [
			'TRANS_KEY' => 'КЛЮЧ_ПЕРЕВОДЧИКА',
			'DICT_KEY' => 'КЛЮЧ_СЛОВАРЯ'
		];
	```
	Ключи генерируем тут [Яндек.Перевочик](https://tech.yandex.ru/keys/get/?service=trnsl) и тут
	[Яндекс.Словарь](https://tech.yandex.ru/keys/get/?service=dict)
- Далее дать нужные права **chmod +x yali**
- Переместить файл **yali** в **/usr/local/bin** выполнив **sudo mv yali /usr/local/bin** или в любую другую, при условии что она есть в $PATH
- Далее идем в настройки System Settings -> Keyboard -> Shortcuts
- Создаем новый shortcut в поле Command вписываем путь до **yali** (**/usr/local/bin/yali**)

## Советую немного прокачать notify-osd если оболочка Unity.
Ссылка на прокачку [CLOSABLE / MOVABLE NOTIFYOSD ](http://www.webupd8.org/2012/06/closable-movable-notifyosd.html)

### Вдохновение брал отсюда [habr](http://habrahabr.ru/post/137215/)
