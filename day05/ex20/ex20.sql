SELECT
  `film`.`id_genre` as 'id_genre',
  `genre`.`name` as 'name_genre',
  `film`.`id_distrib` as 'id_distrib',
  `distrib`.`name` as 'name_distrib',
  `film`.`title` as 'title_film'
FROM `film`
LEFT JOIN `distrib` ON `film`.`id_distrib` = `distrib`.`id_distrib`
LEFT JOIN `genre` ON `film`.`id_genre` = `genre`.`id_genre`
WHERE `film`.`id_genre` BETWEEN 4 AND 8;
