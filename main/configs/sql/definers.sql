USE `mysql`;

UPDATE `mysql`.`proc`
SET DEFINER = 'dbuser@localhost'
WHERE DEFINER = 'ghopw@%' || DEFINER = 'roliv@%' || DEFINER = 'corpstruct@localhost';
