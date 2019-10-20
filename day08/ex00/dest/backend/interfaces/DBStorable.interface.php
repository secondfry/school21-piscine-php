<?php

interface DBStorable
{

  public
  function store(): void;

  public static
  function recreate(
    string $id
  );

}
