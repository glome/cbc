CREATE TABLE `cashback`.`Visitors` (
  `visitorId` INT NOT NULL AUTO_INCREMENT,
  `userId` VARCHAR(64) NULL,
  PRIMARY KEY (`visitorId`),
  UNIQUE INDEX `uniqueUsers` (`userId` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE `cashback`.`Wishes` (
  `visitorId` INT NOT NULL,
  `productId` INT NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`visitorId`) REFERENCES `cashback`.`Visitors` (`visitorId`),
  UNIQUE INDEX `uniqueWish` (`visitorId` ASC, `productId` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
