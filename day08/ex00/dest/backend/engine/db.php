<?php

class MDB {
  const DB = 'rush01';
  static public
  function get() {
    $mongo = new MongoDB\Client(
      sprintf(
        'mongodb://%s:%s@172.19.0.3:27017',
        $_ENV['MONGODB_ADMINUSERNAME'],
        $_ENV['MONGODB_ADMINPASSWORD']
      )
    );
    return $mongo -> selectDatabase(self::DB);
  }
}
