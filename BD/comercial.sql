
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema Comercial
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema Comercial
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Comercial` DEFAULT CHARACTER SET utf8 ;
USE `Comercial` ;

-- -----------------------------------------------------
-- Table `Comercial`.`Clientes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Comercial`.`Clientes` ;

CREATE TABLE IF NOT EXISTS `Comercial`.`Clientes` (
  `clieId` INT NOT NULL AUTO_INCREMENT,
  `clieNome` VARCHAR(45) NOT NULL,
  `clieCpf` VARCHAR(11) NOT NULL,
  `clieRg` BIGINT NOT NULL,
  `clieUfRg` VARCHAR(2) NOT NULL,
  `clieRgDtExpedicao` DATE NOT NULL,
  `clieFone` VARCHAR(15) NOT NULL,
  `clieEmail` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`clieId`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `Comercial`.`Produtos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Comercial`.`Produtos` ;

CREATE TABLE IF NOT EXISTS `Comercial`.`Produtos` (
  `prodId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `prodNome` VARCHAR(45) NOT NULL,
  `prodValor` FLOAT NOT NULL,
  `prodQtde` INT NOT NULL,
  PRIMARY KEY (`prodId`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Comercial`.`Fornecedores`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Comercial`.`Fornecedores` ;

CREATE TABLE IF NOT EXISTS `Comercial`.`Fornecedores` (
  `fornCnpj` BIGINT NOT NULL,
  `fornNome` VARCHAR(45) NOT NULL,
  `fornFone1` VARCHAR(15) NOT NULL,
  `fornFone2` VARCHAR(45) NULL,
  `fornEnd` VARCHAR(100) NOT NULL,
  `fornCep` VARCHAR(10) NOT NULL,
  `fornCidade` VARCHAR(30) NOT NULL,
  `fornUf` VARCHAR(2) NOT NULL,
  PRIMARY KEY (`fornCnpj`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Comercial`.`FornecedoresDeProdutos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Comercial`.`FornecedoresDeProdutos` ;

CREATE TABLE IF NOT EXISTS `Comercial`.`FornecedoresDeProdutos` (
  `fproFornCnpj` BIGINT NOT NULL,
  `fproProdId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`fproFornCnpj`, `fproProdId`),
  INDEX `fk_Fornecedores_has_Produtos_Produtos1_idx` (`fproProdId` ASC),
  INDEX `fk_Fornecedores_has_Produtos_Fornecedores_idx` (`fproFornCnpj` ASC),
  CONSTRAINT `fk_Fornecedores_has_Produtos_Fornecedores`
    FOREIGN KEY (`fproFornCnpj`)
    REFERENCES `Comercial`.`Fornecedores` (`fornCnpj`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Fornecedores_has_Produtos_Produtos1`
    FOREIGN KEY (`fproProdId`)
    REFERENCES `Comercial`.`Produtos` (`prodId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Comercial`.`CestasDeCompras`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Comercial`.`CestasDeCompras` ;

CREATE TABLE IF NOT EXISTS `Comercial`.`CestasDeCompras` (
  `cestId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cestClieId` INT NOT NULL,
  PRIMARY KEY (`cestId`),
  INDEX `fk_CestasDeCompras_Clientes1_idx` (`cestClieId` ASC),
  CONSTRAINT `fk_CestasDeCompras_Clientes1`
    FOREIGN KEY (`cestClieId`)
    REFERENCES `Comercial`.`Clientes` (`clieId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Comercial`.`PreCompras`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Comercial`.`PreCompras` ;

CREATE TABLE IF NOT EXISTS `Comercial`.`PreCompras` (
  `precProdId` INT UNSIGNED NOT NULL,
  `precCestId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`precProdId`, `precCestId`),
  INDEX `fk_Produtos_has_CestasDeCompras_CestasDeCompras1_idx` (`precCestId` ASC),
  INDEX `fk_Produtos_has_CestasDeCompras_Produtos1_idx` (`precProdId` ASC),
  CONSTRAINT `fk_Produtos_has_CestasDeCompras_Produtos1`
    FOREIGN KEY (`precProdId`)
    REFERENCES `Comercial`.`Produtos` (`prodId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Produtos_has_CestasDeCompras_CestasDeCompras1`
    FOREIGN KEY (`precCestId`)
    REFERENCES `Comercial`.`CestasDeCompras` (`cestId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Comercial`.`Compras`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Comercial`.`Compras` ;

CREATE TABLE IF NOT EXISTS `Comercial`.`Compras` (
  `compId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `compClieId` INT NOT NULL,
  PRIMARY KEY (`compId`),
  INDEX `fk_Compras_Clientes1_idx` (`compClieId` ASC),
  CONSTRAINT `fk_Compras_Clientes1`
    FOREIGN KEY (`compClieId`)
    REFERENCES `Comercial`.`Clientes` (`clieId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Comercial`.`ItensDaCompra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Comercial`.`ItensDaCompra` ;

CREATE TABLE IF NOT EXISTS `Comercial`.`ItensDaCompra` (
  `itenCompId` INT UNSIGNED NOT NULL,
  `itenProdId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`itenCompId`, `itenProdId`),
  INDEX `fk_Compras_has_Produtos_Produtos1_idx` (`itenProdId` ASC),
  INDEX `fk_Compras_has_Produtos_Compras1_idx` (`itenCompId` ASC),
  CONSTRAINT `fk_Compras_has_Produtos_Compras1`
    FOREIGN KEY (`itenCompId`)
    REFERENCES `Comercial`.`Compras` (`compId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Compras_has_Produtos_Produtos1`
    FOREIGN KEY (`itenProdId`)
    REFERENCES `Comercial`.`Produtos` (`prodId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
