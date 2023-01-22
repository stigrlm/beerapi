## Basic info

Sample RESTFUL JSON API providing access to beer ratings as provided on external api source https://api.sampleapis.com/beers/stouts.

API has exposed following endpoints:
1. /api/beer
- GET, POST, PUT, DELETE
1. /api/beers
- GET

## How to run the project

Idea of this project is to run beerapi_server in docker without the need of installing any dependencies on local machine (except docker and docker-compose).

Application consists of following containers:
- beerapi_server: running the laravel server, for development purposes just with `php artisan serve`
- beerapi_utility: for running artisan commands
- mysql_db: mysql database persisting data

There are two database schemas created at first container startup - beerapi (for development) and beerapi_testing (for running automated tests)

All database data is persisted via volume in `mysql_data` folder in project root.

### Steps to run:
1. cd into project root directory - where docker-compose.yaml is located
1. Change `user` and `uid` in `docker-compose.yaml` to match your local user
1. Build the images: `docker-compose build`
1. Run the the containers: `docker-compose up`
1. Wait until containers are up and running - watch for possible exit code messages on the console or use `docker ps` command to verify all containers are running
1. Run following artisan commands for initial application and database setup, since it is running in docker, you need to invoke the commands from the container:
    - `docker exec -it beerapi_utility php artisan migrate` - create migration for beerapi database
    - `docker exec -it beerapi_utility php artisan migrate --env=testing` - create migration for beerapi_testing database

Its also possible to attach to the container shell via `docker exec -it beerapi_utility bash` and then run just artisan commands as if it were on your local machine.

### Additonal functionality and commands:
- run test suite: `docker exec -it beerapi_utility php artisan test`
- run command to fetch data from external api: `docker exec -it beerapi_utility php artisan beers:fetch`
- this command is also scheduled to automaticaly fetch data, you can verify by running `docker exec -it beerapi_utility php artisan schedule:work`