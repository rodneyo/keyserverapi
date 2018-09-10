CREATE DATABASE `rollup`;
CREATE DATABASE `apikey`;

CREATE USER '--DB_USER_NAME--'@'localhost' IDENTIFIED BY '--DB_USER_PASSWORD--';
GRANT ALL PRIVILEGES ON *.* TO '--DB_USER_NAME--'@'localhost' WITH GRANT OPTION;

/* USE `rollup`;

// UPDATE `user_master` SET `user_profile` = '--DS USER--', `email` = '--DS EMAIL--', `carlib_shortname` = '--DS USER--' WHERE `id` = '20648859'; */