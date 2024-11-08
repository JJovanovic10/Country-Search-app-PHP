DROP SCHEMA IF EXISTS `Drzave`;

CREATE SCHEMA IF NOT EXISTS `Drzave` DEFAULT CHARACTER SET utf8mb4;
USE `Drzave`;

-- -----------------------------------------------------
-- Table `Drzave`.`country`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Drzave`.`country`(
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` CHAR(52) NOT NULL,
  `Code` CHAR(3) NOT NULL,
  `CapitalCity` CHAR(60) NOT NULL,
  `Population` INT(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Code` (`Code`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `Drzave`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Drzave`.`users` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `username` CHAR(35) NOT NULL,
  `email` CHAR(100) NOT NULL,
  `password` CHAR(50) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `email` (`email` ASC),
  UNIQUE INDEX `username` (`username` ASC)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Table `Drzave`.`userlevel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Drzave`.`userlevel` (
  `userId` INT(11) NOT NULL,
  `userLevel` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`userId`),
  INDEX `fk_userLevel_users_idx` (`userId` ASC),
  CONSTRAINT `fk_userLevel_users` FOREIGN KEY (`userId`) REFERENCES `Drzave`.`users` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data into country table
INSERT INTO `Drzave`.`country` (`Name`, `Code`, `CapitalCity`, `Population`) VALUES
  ('United States', 'USA', 'Washington, D.C.', 331449281),
  ('United Kingdom', 'GBR', 'London', 66040229),
  ('France', 'FRA', 'Paris', 67595000),
  ('Japan', 'JPN', 'Tokyo', 125720000),
  ('Germany', 'DEU', 'Berlin',  83536115);

-- Insert data into users table
INSERT INTO `Drzave`.`users` (`username`, `email`, `password`) VALUES
  ('Nikola123', 'Nikola@example.com', 'password123'),
  ('Jovana456', 'Jovana@example.com', 'pass456'),
  ('Marko789', 'admin@example.com', 'adminpass789');

-- Insert data into userlevel table
INSERT INTO `Drzave`.`userlevel` (`userId`, `userLevel`) VALUES
  (1, 'user'),
  (2, 'manager'),
  (3, 'admin');
