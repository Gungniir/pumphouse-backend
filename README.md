<p align="center"><img src="https://github.com/Gungniir/pumphouse-backend/blob/57b34bf7874c6f0b15bf944c980db96ac3f52268/public/Logo.png" width="400" alt="Логотип"></p>

## Система Водокачка+ (Backend)
Данная система была разработана в качестве тестового задания.

## Порядок запуска

### Настройка
Нужно скопировать .env.example в .env

В файле .env нужно установить ADMIN_LOGIN и ADMIN_PASSWORD. Эти данные будут
использованы при создании аккаунта администратора.

А также сгенерировать случайную строку и установить её в APP_KEY.
### Запуск

```shell 
docker-compose up -d # Запуск контейнеров pgsql и backend
docker-compose exec backend php artisan migrate --force # Создание таблиц в базе данных

docker-compose exec backend php artisan db:seed # Создание аккаунта администратора и случайных резидентов
# ИЛИ
docker-compose exec backend php artisan db:seed --class AdminSeeder # Создание только аккаунта администратора
```

## License

The project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
