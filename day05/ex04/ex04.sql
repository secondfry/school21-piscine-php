UPDATE `ft_table`
SET `creation_date` = DATE_ADD(`creation_date`, INTERVAL 5 YEAR)
WHERE `id` > 5;
