CREATE TABLE `b_xscan_results`
(
    `id` int NOT NULL AUTO_INCREMENT,
    `type` varchar(5) NOT NULL,
    `src` varchar(255) NOT NULL,
    `message` varchar(255) NOT NULL,
    `score` double NOT NULL,
    `mtime` DATETIME NULL,
    `ctime` DATETIME NULL,
    `tags` TEXT NUll,
    PRIMARY KEY(`id`)
)