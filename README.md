# Описание
Тестовое задание на создание чата.
# Запуск
composer install
php bin\console doctrine:schema:create
symfony server:start

запуск сервера веб соккета
php bin\console chat:server:start

клиент
yarn install
yarn start

# Идея
 Пользователь создает скрытый чат для общения. Получает ссылку на чат, и
 отправляет ее всем участникам. Для того чтобы подключится к чату, необходимо
 ввести никнейм. После этого пользователи могут начать общение.

 - При подключении пользователь получает историю сообщений.
 - Возможность проматывать сообщения в хронологическом порядке.
