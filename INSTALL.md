# Roundapp - Backend

## REQUIREMENTS
- PHP 7.4
- PHP extensions: php-mbstring php-xml php-pgsql ...
- Web Server (Nginx or Apache)
- RDBMS (Postgres)
- Composer
- Git


## INSTALLATION (MANUAL)

### 1. Download project

Go to project's parent directory

    $ cd /path/to/project/parent

Clone or copy the repository ["roundapp/back"](https://gitlab.com/fucking-rocket/roundapp/back)

Via SSH

    $ git clone git@github.com:round-app/backend.git

Via HTTPS

    $ git clone https://github.com/round-app/backend.git    

### 2. Permission

Change folders permission (according web server user)

    $ cd back    
    $ sudo chmod -R 755 .
    $ sudo chmod -R 776 storage
    $ sudo chmod -R 776 bootstrap/cache

### 3. Database

Settings

    $ DB_DATABASE=roundapp
    $ DB_USERNAME=roundapp
    $ DB_PASSWORD=roundapp

Create database

    $ psql -d postgres -c "\x" -c "CREATE DATABASE $DB_DATABASE;"
    CREATE DATABASE
    
Create user

    $ psql -d postgres -c "\x" -c "CREATE USER $DB_USERNAME WITH ENCRYPTED PASSWORD '$DB_PASSWORD';"
    CREATE ROLE
    
Grant permission

    $ psql -d postgres -c "\x" -c "GRANT ALL ON DATABASE $DB_DATABASE TO $DB_USERNAME;"
    GRANT
    
### 4. Configuration

Create a .env configuration file (if not exists) copying .env.example

    $ cp .env.example .env
    
Edit .env configuration file

    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=roundapp
    DB_USERNAME=roundapp
    DB_PASSWORD=roundapp
    
   
Generate application encryption key

    $ php artisan key:generate

### 5. Dependencies

    $ composer install

### 6. Migrations

Start migration

    $ php artisan migrate
    
Install Laravel Passport

This command will create the encryption keys needed to generate secure access tokens. In addition, the command will create "personal access" and "password grant" clients which will be used to generate access tokens

    $ php artisan passport:install

Keys "oauth-private.key" and "oauth-public.key" are store in storage/ by default 

To regenerate Passport encryption keys (implicit in install command)

    $ php artisan passport:keys
    
Generate password grant allows your other first-party clients

    $ php artisan passport:client --password
    

## DEPLOY

Generate Passport encryption keys

    $ php artisan passport:keys

Generate password grant allows your other first-party clients, such as a mobile application, to obtain an access token using an e-mail address / username and password

    $ php artisan passport:client --password

## MAINTENANCE

### Passport

Purge revoked and expired tokens and auth codes...

    $ php artisan passport:purge

Only purge revoked tokens and auth codes...
    
    $ php artisan passport:purge --revoked

Only purge expired tokens and auth codes...

    $ php artisan passport:purge --expired

### Users

    $ php artisan tinker
    
    DB::table('advertisers')->insert(['created_at'=>'2020-01-01']);
    DB::table('users')->insert(['name'=>'admin','email'=>'admin@roundapp.local','password'=>Hash::make('12345678'),'someone_id'=>1,'someone_type'=>'App\Models\Advertiser']);

### Reload classes after seed

    $ composer dump-autoload

### API DOC

Regenerate Open Api documentation

    $ php artisan l5-swagger:generate

### CODE GENERATOR

Generate model

    $ php artisan make:model Models\\<MyModel> --force
    
Generate controller

    $ php artisan make:controller Api\\<MyController>Controller --model="App\Models\<MyModel>" --api --force

Generate api doc model

    $ php artisan make:oa-model Models\\<MyModel> --force

Generate api doc controller

    $ php artisan make:oa-controller \\Http\\Controllers\\Api\\<MyController>Controller --force
    
Generate api doc models

    $ php artisan make:oa-model Models --force -f
    
Generate api doc models

    $ php artisan make:oa-controller Http\\Controllers\\Api --force -f


## PERMISSIONS

### Nginx
...

### Fpm
...

### Project folders
...

### Postgres
...
