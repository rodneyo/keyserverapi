USE `mysql`;

UPDATE `mysql`.`proc`
SET DEFINER = 'dbuser@localhost'
WHERE DEFINER = 'User@%' || DEFINER = 'User@%' || DEFINER = 'domain@localhost';
