-- MySQL Script generated by MySQL Workbench
-- Fri Mar  6 10:14:00 2020
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema dbBCBB
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema dbBCBB
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `dbBCBB` DEFAULT CHARACTER SET utf8mb4 ;
USE `dbBCBB` ;

-- -----------------------------------------------------
-- Table `dbBCBB`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbBCBB`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nickname` VARCHAR(255) NOT NULL,
  `signature` TEXT NULL,
  `avatar` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `nickname_UNIQUE` (`nickname` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBCBB`.`boards`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbBCBB`.`boards` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBCBB`.`topics`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbBCBB`.`topics` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NULL,
  `content` TEXT NULL,
  `created_at` DATETIME NULL,
  `boards_id` INT UNSIGNED NOT NULL,
  `users_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_topics_boards_idx` (`boards_id` ASC),
  INDEX `fk_topics_users1_idx` (`users_id` ASC),
  CONSTRAINT `fk_topics_boards`
    FOREIGN KEY (`boards_id`)
    REFERENCES `dbBCBB`.`boards` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_topics_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `dbBCBB`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbBCBB`.`messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbBCBB`.`messages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  `topics_id` INT UNSIGNED NOT NULL,
  `users_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_messages_topics1_idx` (`topics_id` ASC),
  INDEX `fk_messages_users1_idx` (`users_id` ASC),
  CONSTRAINT `fk_messages_topics1`
    FOREIGN KEY (`topics_id`)
    REFERENCES `dbBCBB`.`topics` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_messages_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `dbBCBB`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;