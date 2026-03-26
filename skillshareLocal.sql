create database skillshareLocal;
use skillshareLocal;

CREATE TABLE `user` (
  `id` integer PRIMARY KEY auto_increment,
  `username` varchar(255),
  `name` varchar(255),
  `surname` varchar(255),
  `email` varchar(255),
  `password` varchar(255)
);

CREATE TABLE `teacher` (
  `id` integer PRIMARY KEY auto_increment,
  `username` varchar(255),
  `name` varchar(255),
  `surname` varchar(255),
  `email` varchar(255),
  `password` varchar(255)
);

CREATE TABLE `session` (
  `id` integer PRIMARY KEY auto_increment,
  `title` varchar(255),
  `categoryId` integer,
  `teacherId` integer,
  `desc` varchar(255),
  `duration` varchar(255),
  `cost` float,
  `location` varchar(255),
  `img` varchar(255),
  `impactScore` float
);

CREATE TABLE `category` (
  `id` integer PRIMARY KEY auto_increment,
  `categoryName` varchar(255)
);

CREATE TABLE `learning` (
  `id` integer PRIMARY KEY auto_increment,
  `userId` integer,
  `sessionId` integer
);

CREATE TABLE `review` (
  `id` integer PRIMARY KEY auto_increment,
  `userId` integer,
  `sessionId` integer,
  `review` varchar(255)
);

ALTER TABLE `session` ADD FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`);

ALTER TABLE `session` ADD FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`id`);

ALTER TABLE `learning` ADD FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

ALTER TABLE `learning` ADD FOREIGN KEY (`sessionId`) REFERENCES `session` (`id`);

ALTER TABLE `review` ADD FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

ALTER TABLE `review` ADD FOREIGN KEY (`sessionId`) REFERENCES `session` (`id`);
