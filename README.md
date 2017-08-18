### Про события

Используются дефолтный механизм event's

#### Механика

При изменении модели, на нее навешивается слушатель события и тригирет его:
* ищем если событие активно, то используем шаблон события
* производим поиск всех подписанных на данное событие пользователей и генерериуем текст на основании шаблона и отправляем по заданным каналам web/email или оба

#### Реализация

`
$model->on(News::EVENT_AFTER_CREATE, [$model, 'sendNotification'], [
    'code' => News::EVENT_AFTER_CREATE,
]);
$model->trigger(News::EVENT_AFTER_CREATE);
`

### Архитектурные решения

https://goo.gl/Ue2RHi - схема дб


### Развертывание

Проект собран используя шаблон yii2 basic.
`composer create-project ...`

Cледом необходимо настроить доступы к БД, SMTP, etc в файле `.env`

Cледом необходимо выполнить миграции для инциализации данных в БД и добавить дефолтные роли созданным пользвателям

`
yii migrate
yii rbac/init
yii rbac/asset admin admin
yii rbac/asset moderator moderator
yii rbac/asset user user
`

### Local project serving

`yii serve` будет слушать на порту 8080
`yii serve --port=8888` будет слушать на порту 8888, если вдруг порт 8080 занят


### Дефольные аккаунты создаваемые после инциализации rbac

Login: admin
Password: 123456
Role: admin

Login: moderator
Password: 123456
Role: moderator

Login: user
Password: 123456
Role: user

### Примерное время: **32 часа**
