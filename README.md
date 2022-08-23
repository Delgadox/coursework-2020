1. Git clone https://github.com/Delgadox/coursework-2020 - Клонирования репозитория 
2. Composer u - скачивание необходимых пакетов
3. php artisan storage:link - создание псевдо ссылки для сохранения изображений
4. Создайте базу данных. И запомните её название
5. Теперь необходимо скопировать файл .env.example и переименовать его копию в .env. Далее прописать команду php artisan key:generate. Теперь зайти в скопированный файл и найти строки TELEGRAM_BOT_TOKEN и TELEGRAM_BOT_GROUP. 
TELEGRAM_BOT_TOKEN необходимо записать уникальный токен бота, его можно получить создав бота с помощью Bot Father. 
TELEGRAM_BOT_GROUP необходимо записать ссылку на канал, без "@".
Далее необходимо записать название базы данных в строку DB_DATABASE, логин для базы данных в DB_USERNAME и пароль в DB_PASSWORD
6. php artisan migrate - миграция таблиц в базу данных.
7. php artisan db:seed --class=PermissionTableSeeder и php artisan db:seed --class=CreateAdminUserSeeder - добавление необходимых элементов в базу данных
Теперь вы можете авторизоваться - почта: admin@gmail.com пароль: 123456
