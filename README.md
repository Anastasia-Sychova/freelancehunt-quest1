## This is solution for the task https://github.com/freelancehunt/code-test
# How use Quest1 at localhost

PHP version 7.4+
MySQL version 5.7

## Install composer dependencies
```bash
# composer install
```

## Add your Mysql credentials
Open src/Database/Mysql.php, edit credentials at __construct() if needed.

## Create database
Execute the migrations/init.sql file in your database.

## Run default PHP server
```bash
# php -S localhost:8000
```

## Parse projects
Open http://localhost:8000/src/parse.php. There will be total projects count.

## Open index
Open http://localhost:8000/src/index.php to see table and pie chart.

### TODOs
* Make PHPUnit tests
* Make page navigation without page reloading
* Make error handling
* Fix problem with permissions in docker nginx
* Use currency converter API


# How use Quest1 with docker (not working now)

## Install docker and docker-compose at first.

To install docker (CE)
https://docs.docker.com/engine/installation/linux/ubuntu/

To install docker-compose
https://docs.docker.com/compose/install/

or install from terminal:

```
curl -sSL https://get.docker.com/ | sudo sh
sudo curl -L -o /usr/local/bin/docker-compose $(curl https://api.github.com/repos/docker/compose/releases/latest | grep browser_download_url | cut -d '"' -f 4 | grep `uname -s`-`uname -m`) &&\
    sudo chmod +x /usr/local/bin/docker-compose
```

## Clone project to your projects/ directory

## Register hostname
```bash
# echo '127.0.0.1    quest1.develop' >> /etc/hosts
```

## Provide CA certificate
Copy files from ssl folder to /usr/local/share/ca-certificates.
```bash
sudo update-ca-certificates
```

## Install composer dependencies
```bash
# composer install
```

## Create and start container
> From project directory
```bash
docker-compose up -d
```

## Stop container
> From project directory
```bash
docker-compose stop
```

## Start container
> From project directory
```bash
docker-compose start
```

## Remove container
> From project directory
```bash
docker-compose down -v
```

## Rebuild image & container
> From project directory
```bash
docker-compose build --pull
docker-compose pull
docker-compose up -d
```
