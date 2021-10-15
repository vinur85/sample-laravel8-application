## Sample Laravel application

---
## About

 This is a small containerised product service with an HTTP API.

---
## Technologies
- `php: ^7.3|^8.0`
- `laravel/framework: ^8.54`
- `laravel/sanctum: ^2.11`
---
## Installation

- Clone this repository and then  run `docker-compose up --build`.
- We don't need to manually run `composer install` and `php artisan generate:key` as we are handling it in our `Docker` file.
- Once the process is complete, use the following command to list all the running containers:
  - `docker ps`
- Make a copy of `.env.example` in the project root folder and rename it to `.env`
- Make changes to `.env` file. Add the following changes:
  - `DB_HOST=db` \
    `DB_PORT=3306` \
    `DB_DATABASE=product_db` \
    `DB_USERNAME=product_db_user` \
    `DB_PASSWORD=sample@assignment`\
  These are the values we have added in `docker-compose.yml` file while setting up database.
- You can visit [http://127.0.0.1/](http://127.0.0.1/) to see your application home page.
- Next step is creating a user for mysql database:
  - `docker-compose exec db bash`
  - Inside the container, log into the MySQL root administrative account: `mysql -u root -p`
  - You will be prompted for the password you set for the MySQL root account during installation in your docker-compose file (**Password: `sample@assignment`**).
  - Next run `show databases;`
  - You can see the list of available databases.
  - Run `GRANT ALL ON product_db.* TO 'product_db_user'@'%' IDENTIFIED BY 'sample@assignment';`
  - Flush the privileges to notify the MySQL server of the changes: `FLUSH PRIVILEGES;`
  - Exit mysql and container.
- Next step is running the migration commands and seeder to import data:
  - `docker-compose exec app php artisan migrate`
  - `docker-compose exec app php artisan db:seed`
#### **_From next time onwards you may run `docker-compose up -d` to start docker in detached mode._**

## Incase, building docker failed:
- Clone the repository in a folder in your `localhost` folder and open in terminal.
- Manually run `composer install` and `php artisan generate:key`
- - Make a copy of `.env.example` in the project root folder and rename it to `.env`
- Make changes to `.env` file. Add the following changes:
  - `DB_HOST=localhost` \
    `DB_PORT=3306` \
    `DB_DATABASE=product_db` \
    `DB_USERNAME=product_db_user` \
    `DB_PASSWORD=sample@assignment`\
- Run `php artisan migrate`.
- Run `php artisan db:seed`.
- Now run `php artisan serve`.You can visit the application at [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **_From next time onwards you may run `php artisan serve` to start the application._**
---
**NOTE**

_Now the application is loaded with data. You can follow the API Documentation to look into the endpoints._

---
## Unit Test

- PHPUnit is being used for unit tests.
- You may run `docker-compose exec app vendor/bin/phpunit` for unit tests.
- Incase, if not using docker, you can run the unit tests using: `vendor/bin/phpunit` 

---
## Credits
<a href="https://www.digitalocean.com/community/tutorials/how-to-set-up-laravel-nginx-and-mysql-with-docker-compose">Setup Docker</a><br/>
<a href="https://laravel.com/docs/8.x/sanctum">Laravel Sanctum</a>

    
