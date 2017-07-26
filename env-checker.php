#!/usr/bin/env php
<?php

/*
 * This file is responsible for checking test env status.
 * If env is ready this command will return code = 0.
 * If env is NOT ready this command will return code = 1.
 */

$mysqli = @(new mysqli('127.0.0.1', 'root', 'root', 'testdb', 3306));

if ($mysqli->connect_errno) {
	exit(1);
}

if ($mysqli->ping()) {
} else {
	exit(1);
}

$mysqli->close();

exit(0);
