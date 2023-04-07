# LUANAR IMIS (LUMIS)

## Requirements

The current package requirements are:

- Laravel >= 9.x
- PHP >= 8.1

## Installation


Run the command

```console
composer create-project luanardev/lumis
```

## Configuration

Open `livewire.php` in the config directory.

Set `asset_url` to the path of Livewire JavaScript assets, for cases where
your app's domain root is not the correct path. 
By default, LUMIS will load its JavaScript assets from the app's "relative root".

```html
asset_url = '/lumis/public'
```
### Database Migration

#### Open `.env` file at the root of the directory

Add database connection details
````
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lumis
DB_USERNAME=root
DB_PASSWORD=
````
### Run Migrations

```console
php artisan migrate
```
```console
php artisan db:seed
```
### Creating Users

#### Create Super User
```console
php artisan create-superuser
```

#### Create Any User
```console
php artisan create-user
```
