-- MySQL dump 10.13  Distrib 5.5.40, for CYGWIN (x86_64)
--
-- Host: 127.0.0.1    Database: flashcards
-- ------------------------------------------------------
-- Server version	5.6.21-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `decks`
--

DROP TABLE  `decks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `decks` (
  `deckid` int(8) NOT NULL AUTO_INCREMENT,
  `userid` int(8) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`deckid`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
alter table decks add column enable varchar(10) default '1'; 

alter table decks add column `created_d` timestamp DEFAULT CURRENT_TIMESTAMP;
--
-- Table structure for table `flashcards`
--

DROP TABLE  `flashcards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flashcards` (
  `cardid` int(16) NOT NULL AUTO_INCREMENT,
  `deckid` int(8) NOT NULL,
  `front` varchar(30) NOT NULL,
  `back` varchar(30) NOT NULL,
  PRIMARY KEY (`cardid`)
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

alter table flashcards add column dictionary varchar(2000) null; 
alter table flashcards modify back varchar(255);
alter table flashcards add column enable  varchar(10) default '1'; 
alter table flashcards add column cefr  varchar(5) null;
alter table flashcards add column audio  varchar(255) null; 
alter table flashcards add column audiob64  varchar(255) null; 
alter table flashcards add column eg1 varchar(2000) null; 
alter table flashcards add column eg2 varchar(2000) null; 
alter table flashcards add column eg3 varchar(2000) null; 
alter table flashcards add column numeg varchar(5) null; 
alter table flashcards add column eg4 varchar(2000) null; 
alter table flashcards add column eg5 varchar(2000) null; 


--
-- Table structure for table `test`
--

DROP TABLE `test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
-- select * from users

DROP TABLE  `users_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_data` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` char(64) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

alter table users_data add column comment varchar(200) null; 
alter table users_data add column role varchar(10) default 's'; 
alter table users_data add column enable  varchar(10) default '1'; 



/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-17  2:20:17

DROP TABLE  `log`;
CREATE TABLE `log` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `deckid` int(8) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `word` varchar(255) NOT NULL,
  `choice` varchar(255) NOT NULL,
  `created_d` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=utf8;

alter table log add column `sid` varchar(255);

DROP table  `quizlog`; 
CREATE TABLE `quizlog` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `userid` varchar(255) NOT NULL,
  `q` varchar(255) NOT NULL,
  `a` varchar(255) NOT NULL,
  `s` varchar(255) NOT NULL,
  `choice` varchar(255) NOT NULL,
  `created_d` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=utf8;
 

alter table quizlog add column `d` varchar(255);
alter table quizlog add column `sid` varchar(255);
alter table quizlog add column `type` varchar(255) default 'q';



CREATE TABLE `conv` (
  `cefr` varchar(30)  ,
  `ielts` varchar(30)  NULL,
  PRIMARY KEY (`cefr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into conv (cefr, ielts) values ( 'A1', 'below 2.5'); 
insert into conv (cefr, ielts) values ( 'A2', '3.0 ~ 3.5'); 
insert into conv (cefr, ielts) values ( 'B1', '4.0 ~ 5.0'); 
insert into conv (cefr, ielts) values ( 'B2', '5.5 ~ 6.5'); 
insert into conv (cefr, ielts) values ( 'C1', '7.0 ~ 8.0'); 
insert into conv (cefr, ielts) values ( 'C2', 'above 8.5'); 


CREATE TABLE `setup` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `type`    varchar(255) NOT NULL,
  `value`   varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)

insert into setup  (type, value) values ( 'numQuizQuestion' , '35');
insert into setup  (type, value) values ( 'numDictationQuestion_text' , '20'); 
insert into setup  (type, value) values ( 'numDictationQuestion_voice' , '10'); 
insert into setup  (type, value) values ( 'numDictationQuestion_fill' , '5'); 
insert into setup  (type, value) values ( 'numCambridgeQuestion' , '5'); 
insert into setup  (type, value) values ( 'numLearnByExample' , '5'); 
insert into setup  (type, value) values ( 'learningThreshold' , '0.5'); 


CREATE TABLE `setup_user` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `type`    varchar(255) NOT NULL,
  `value`   varchar(255) NOT NULL,
    `userid` varchar(255) NOT NULL
  PRIMARY KEY (`id`)
)
 

  