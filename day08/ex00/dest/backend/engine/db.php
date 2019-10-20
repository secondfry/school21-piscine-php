<?php

class MDB {
  const DB = 'rush01';
  static public
  function get() {
    $mongo = new MongoDB\Client(
      sprintf(
        'mongodb://%s:%s@mongo:27017',
        $_ENV['MONGODB_ADMINUSERNAME'],
        $_ENV['MONGODB_ADMINPASSWORD']
      )
    );
    return $mongo -> selectDatabase(self::DB);
  }
}
