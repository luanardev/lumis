# Luanar MIS (LUMIS)

## Requirements

The current package requirements are:

- Laravel >= 8.x
- PHP >= 8.0

## Installation


Run the command

```console
composer create-project luanardev/lumis
```

## Configuration
Lumis MIS is built using Livewire. 
Therefore you need to configure Livewire Assets URL.

Open `livewire.php` in the config directory.

Set `asset_url` to the path of Livewire JavaScript assets, for cases where
your app's domain root is not the correct path. 
By default, Livewire will load its JavaScript assets from the app's "relative root".

```html
asset_url = '/lumis/public'
```

## Usage

To install LUMIS, enter the following URL in the browser (Replace URL with your domain name).

`http://localhost/lumis/public/install`

Run

```console
php artisan storage:link
```
