#!/usr/bin/env bash

docker run -d --name mysql-for-tests -p="3306:3306" -e MYSQL_ROOT_PASSWORD=root -eMYSQL_DATABASE=testdb mysql:8.0.1

for i in {1..60}
do

    if ./env-checker.php; then
        break;
    fi

    echo $i
    sleep 1

done

./vendor/bin/phpunit ./tests/integration
TEST_COMMAND_RESULT=$?

docker stop mysql-for-tests
docker rm mysql-for-tests

exit $TEST_COMMAND_RESULT
