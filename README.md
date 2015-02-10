# yali-translate
Переводчик для Ubuntu(Linux)

##Небольшой онлайн-переводчик, для линукса.
Использует сервисы Яндекс.Переводчик и Яндекс.Словарь.



## Установка.
- Установить нужные штуки **sudo apt-get install libnotify-bin xsel**
- Так же должны быть установлены  **php5 и composer**
- Любым убодным способом копировать все в **/home/username/.yali**
- Открыть терминал перейти в папку **.yali**
- Выполнить **composer install**
- Далее дать нужные права **chmod +x seltr trans**
- Переместить файл **seltr** в **/usr/local/bin** выполнив **sudo mv seltr /usr/local/bin** или в любую другую, при условии что она есть в $PATH
- Далее идем в настройки System Settings -> Keyboard -> Shortcuts
- Создаем новый shortcut в поле Command вписываем путь до seltr (**/usr/local/bin/seltr**)

## Советую немного прокачать notify-osd если оболочка Unity.
Ссылка на прокачку [CLOSABLE / MOVABLE NOTIFYOSD ](http://www.webupd8.org/2012/06/closable-movable-notifyosd.html)