# Install

 - install Docker on your PC
 - make sure you have the `docker-compose` installed

# Run

 - Run `docker-compose up`
 - enter the  generated container
 - run `php index.php app:timetable`

## How to enter the container

 - run `docker ps`
 - from the table look for `NAMES`
 - it should have a container with name that contains `antiloop-test-app`
 - run `docker exec -it <name of the container> bash`