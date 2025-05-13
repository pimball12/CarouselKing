-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema carousel_king
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema carousel_king
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `carousel_king` DEFAULT CHARACTER SET utf8 ;
USE `carousel_king` ;

-- -----------------------------------------------------
-- Table `carousel_king`.`ck_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `carousel_king`.`ck_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(2000) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `role` ENUM('A', 'D') NOT NULL DEFAULT 'D' COMMENT 'A => Administrator, D => Default',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `carousel_king`.`ck_companies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `carousel_king`.`ck_companies` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `doc` VARCHAR(255) NULL,
  `address` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_ck_companies_ck_users_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_ck_companies_ck_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `carousel_king`.`ck_users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `carousel_king`.`ck_carousel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `carousel_king`.`ck_carousel` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` VARCHAR(1000) NULL,
  `status` ENUM('A', 'I') NOT NULL DEFAULT 'A' COMMENT 'A => Active, I => Inactive',
  `custom_css` TEXT NULL,
  `transition_type` ENUM('S', 'F', 'Z') NOT NULL DEFAULT 'S' COMMENT 'S => Slide, F => Fade, Z => Zoom',
  `transition_duration` INT NOT NULL DEFAULT 5,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_ck_carousel_ck_companies1_idx` (`company_id` ASC) VISIBLE,
  CONSTRAINT `fk_ck_carousel_ck_companies1`
    FOREIGN KEY (`company_id`)
    REFERENCES `carousel_king`.`ck_companies` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `carousel_king`.`ck_carousel_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `carousel_king`.`ck_carousel_item` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `carousel_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NULL,
  `description` VARCHAR(1000) NULL,
  `image_url` VARCHAR(1000) NULL,
  `video_url` VARCHAR(1000) NULL,
  `link_url` VARCHAR(1000) NULL,
  `order` INT NOT NULL DEFAULT 0,
  `status` ENUM('A', 'I') NOT NULL DEFAULT 'A' COMMENT 'A => Active, I => Inactive',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `fk_ck_carousel_item_ck_carousel1_idx` (`carousel_id` ASC) VISIBLE,
  CONSTRAINT `fk_ck_carousel_item_ck_carousel1`
    FOREIGN KEY (`carousel_id`)
    REFERENCES `carousel_king`.`ck_carousel` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
