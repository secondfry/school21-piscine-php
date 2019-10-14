SELECT REVERSE(SUBSTRING(`phone_number`, 2)) as 'rebmunenohp'
FROM `distrib`
WHERE `phone_number` LIKE '05%';
