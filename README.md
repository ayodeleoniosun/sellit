# Sellit

Sellit is a REST API clone of a popular listing website in Nigeria called [jiji.ng](https://jiji.ng)

# Getting Started

* Development Requirements
* Installation
* Starting Devevelopment Server
* Documentation
* Testing

## Development Requirements

This application currently runs on <b>Laravel 9.20</b> and the development requirements to get this application up and
running are as follow:

* PHP 8.1+
* Sqlite
* NPM
* MySQL
* git
* Composer

## Installation

#### Step 1: Clone the repository

```bash
git clone https://github.com/ayodeleoniosun/sellit.git
```

#### Step 2: Switch to the repo folder

```bash
cd sellit
```

#### Step 3: Install all composer dependencies

```bash
composer install
```

#### Step 4: Install all npm dependencies

```bash
npm install
```

#### Step 5: Setup environment variable

- Copy `.env.example` to `.env` i.e `cp .env.example .env`
- Update all the variables as needed

#### Step 6: Generate a new application key

```bash
php artisan key:generate
``` 

#### Step 7: Run database migration alongside the seeders

```bash
php artisan migrate:fresh --seed
``` 

Ensure that your mysql server is up before running the above command

## Starting Development Server

After the installation of the packages and running migrations, then, it's time to start the development server.

Development server can be started in three ways:

* Using ```php artisan serve```
* Using [Valet](https://laravel.com/docs/8.x/valet)
* Docker via [Laravel sail](https://laravel.com/docs/8.x/sail)

I recommend using valet or Laravel sail to start the development server to ensure that the application works perfectly
across all developers' machines regardless of their operating systems.

#### Note:

* If you are using Laravel sail to start your development server, your default database configuration in the .env should
  be as follow:

```bash
  DB_CONNECTION=mysql
  DB_HOST=mysql
  DB_PORT=3306
  DB_DATABASE=your_database_name
  DB_USERNAME=sail
  DB_PASSWORD=password
```

However, if you want to change the DB username and password after springing forth a docker container using the laravel
sail, update the DB_USERNAME and DB_PASSWORD in the .env with the new details and then run this:

```bash
./vendor/bin/sail down -v
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```

### Documentation

The Postman API collection is available [Here](/public/postman_collection.json). <br/>

### Testing

You must have installed Pest in the project before running the test suites. If you haven't, kindly follow the
documentation [Here](https://pestphp.com/docs/installation). <br/>
The application is currently made up of feature tests and only the RESTful endpoints were tested. <br/><br/>The tests
can be run using:

```bash
./vendor/bin/pest
```
