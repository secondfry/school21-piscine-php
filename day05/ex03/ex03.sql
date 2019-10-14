INSERT INTO `ft_table` (`login`, `group`, `creation_date`)
  SELECT
    `last_name` as `login`,
    'other',
    `birthdate` as `creation_date`
  FROM `user_card`
  WHERE
    `last_name` LIKE '%a%'
    AND LENGTH(`last_name`) < 9
  ORDER BY `last_name` ASC
  LIMIT 10;
