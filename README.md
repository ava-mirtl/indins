### Шаг 1: Клонирование репозитория
git clone https://github.com/your-repository.git
cd your-repository


### Шаг 2: Установка зависимостей
composer install


### Шаг 3: Настройка базы данных
Отредактируйте файл .env и укажите параметры подключения к вашей базе данных MySQL.

### Шаг 4: Создание базы данных и выполнение миграций
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate


### Шаг 5: Запуск веб-сервера
symfony serve
