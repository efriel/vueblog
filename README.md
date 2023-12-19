# INSTALLATION GUIDE - Simple Blog App

# Installation

## Getting the sources from Repository

### Clone the Sources Code 

### Create or copy .env from .env.sample

> #### cp .env.sample .env
```bash
cp .env.sample .env
```
> #### update the database credential
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=databasename
DB_USERNAME=root
DB_PASSWORD=rootpassword
```

### Database Migration
> #### Database migration
```bash
php artisan migrate
```
### Packages Install
> #### NPM Install
```bash
npm install
```

### Build File and Run the Server
> #### open the terminal and set the working directory to the root folder of application
```bash
npm run watch
```
> #### open new terminal cd to the root folder of application and run the server
```bash
php artisan serve
browse http://localhost:8000/
```

###End
