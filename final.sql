DROP DATABASE IF EXISTS `final`;
CREATE DATABASE `final`;
USE `final`;

CREATE TABLE `avatar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cheminImage` varchar(255) NOT NULL,
  UNIQUE KEY `cheminImage_UNIQUE` (`cheminImage`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `utilisateur` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bio` varchar(255) NOT NULL,
  `localisation` varchar(100) NOT NULL,
  `url` varchar(255) NULL,
  `username` varchar(50) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `aRejointLe` date NOT NULL DEFAULT NOW(),
  `avatar_id` int(10) unsigned NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  FOREIGN KEY (`avatar_id`) REFERENCES `avatar` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `shweet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `texte` VARCHAR(255) NOT NULL,
  `dateCreation` datetime NOT NULL DEFAULT NOW(),
  `auteur_id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`auteur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`parent_id`) REFERENCES `shweet` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `avatar` VALUES(NULL, 'img/avatar1.jpg');
INSERT INTO `avatar` VALUES(NULL, 'img/avatar2.jpg');
INSERT INTO `avatar` VALUES(NULL, 'img/avatar3.png');
INSERT INTO `avatar` VALUES(NULL, 'img/avatar4.png');
INSERT INTO `avatar` VALUES(NULL, 'img/avatar5.jpg');
INSERT INTO `avatar` VALUES(NULL, 'img/avatar6.png');

-- Le mot de passe est encodé en sha256, il correspond à 'patate'
INSERT INTO `utilisateur` (id, bio, localisation, url, username, hash, avatar_id) VALUES(NULL, 'Je suis un troll. Je connais tout.', 'St-Lin', 'http://google.com', 'tremblayj', 'cf0461154c91c85275cbf98074832dcf2ad59f9a9f2ab11f8189e4cea6fdd323', 2);
INSERT INTO `utilisateur` (id, bio, localisation, url, username, hash, avatar_id) VALUES(NULL, 'Je suis un chasseur de troll.', 'Québec', 'http://chasseurdetroll.com', 'turcottej', 'cf0461154c91c85275cbf98074832dcf2ad59f9a9f2ab11f8189e4cea6fdd323', 5);
INSERT INTO `utilisateur` (id, bio, localisation, url, username, hash, avatar_id) VALUES(NULL, 'Je n''ai plus de cheveux. Je rêve d''en ravoir un jour afin d''avoir la chance de montrer au monde entier la beauté de la coupe Longueuil.', 'Shawingan', 'http://mullet.com', 'monchampf', 'cf0461154c91c85275cbf98074832dcf2ad59f9a9f2ab11f8189e4cea6fdd323', 6);

INSERT INTO `shweet` (id, texte, auteur_id, parent_id) VALUES(NULL, 'Mon premier shweet!', 1, NULL);
INSERT INTO `shweet` (id, texte, auteur_id, parent_id) VALUES(NULL, 'Je commente mon propre shweet!', 1, 1);
INSERT INTO `shweet` (id, texte, auteur_id, parent_id) VALUES(NULL, 'C''pas toi qui a inventé l''eau chaude...', 2, 1);
INSERT INTO `shweet` (id, texte, auteur_id, parent_id) VALUES(NULL, 'Pas encore un autre réseau social pour permettre aux deux d''piques d''écrire des niaiseries.', 2, NULL);
INSERT INTO `shweet` (id, texte, auteur_id, parent_id) VALUES(NULL, 'J''pense. Mais bon, tsé veut dire. Quand même... vraiment! Lâ lâ! Pffffff. Ouin Ouin. Tu comprends pas ce que je veux dire. Me semble que je suis clair! Relis ce que je viens d''écrire.', 3, NULL);
INSERT INTO `shweet` (id, texte, auteur_id, parent_id) VALUES(NULL, 'Hishhhh... t''as du manquer d''air à''naissance!', 2, 5);