## Репозиторий кода связанного с картами

### Сборка контейнера

> [!WARNING] замените trickyfoxy на ваш аккаунт в Docker HUB, если продолжаете разработку этого всего 

```bash
docker build . -t trickyfoxy/itmo_pracice_geo
```

### Запуск

```bash
docker run --rm -p 9999:80 trickyfoxy/itmo_pracice_geo
```

Если вы «счастливый» обладатель Apple Silicon:

Читаем вот [это](https://blog.jaimyn.dev/how-to-build-multi-architecture-docker-images-on-an-m1-mac/)

```bash
docker buildx create --use
docker buildx build --platform linux/amd64,linux/arm64 --push -t trickyfoxy/itmo_pracice_geo .
```


### Пояснения

Главный файл — map.html. Он встраивается iframe'ом в сервис. Чтобы что-то дополнительно отрисовать на карте в iframe отправляются сообщения. Ответ от iframe так же передаётся сообщениями.

Почему iframe?

- незавимость от кода сервиса
- возможность разместить на своём сервере и обновлять код независимо от кода сервиса
- независимость от React

Минусы: упоротая логика общения сервиса и карты

Единственный исполняемый файл ради которого нужен контейнер — mbtiles.php. Планы корпусов хранятся в файлах .mbtiles. Это sqlite база с кусочкам карт. Обычно эти файлы большие, поэтому их не запрашивают целиком, а кусочкми. Поэтому на клиенте с ними не работают, а отдаются с сервера частями. Что и делает скрипт.

Откуда планы корупсов? Дизассемблируем APK [itmo.map](https://play.google.com/store/apps/details?id=ru.itmo.campus&hl=en_US) находим где-то в assets нужные файлы. 


