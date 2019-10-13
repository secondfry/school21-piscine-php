<?php

$command = 'docker exec mysqldb mysql -proot1 --execute="SOURCE /root/sql/rush00.sql"';
system($command);
