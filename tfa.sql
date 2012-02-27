
CREATE TABLE  `mydemodb`.`users` (
    `id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `username` VARCHAR( 25 ) NOT NULL ,
    `password` VARCHAR( 32 ) NOT NULL ,
    `phone_number` VARCHAR( 15 ) NOT NULL ,
    `force_update` INT( 1 ) NOT NULL
) ENGINE = MYISAM ;