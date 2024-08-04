# accredify-assessment

1. [Installation](#Installation)


## Installation
After Cloning the repository into your local machine, use command prompt to go to the root of the project and install the dependencies with composer. 
If you do not have composer you may install it at https://getcomposer.org/download/
```bash
composer install
```
After that you may migrate and seed the database tables and user data (be sure a local MySQL database on port 3306 is active)
```bash
php artisan migrate --seed
```
Because we uses Laravel Breeze to save time for Authentication, you will need to install npm dependencies and build it
if you do not have npm and/or node you could refer to this site to download and install
https://docs.npmjs.com/downloading-and-installing-node-js-and-npm?ref=sfeir.dev

```bash
npm install
npm run build
```
after that you may run the application by serving the site or if you have laragon / herd, place them in their designated folders for projects and you may start using it after starting the respective application up
if you do not use these programs you may also run the project with
```bash
npm run serve
```
