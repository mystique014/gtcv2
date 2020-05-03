#**************** BASE DE DONNEES clubtcr ****************
# Le : 18 04 2017 a 10h 15
# Serveur : clubtcr.teria.org
# Version PHP : 5.6.0
# Version mySQL : 5.5.54-MariaDB
# Version GRR : <a href='http://clubtcr.teria.org/'>GTC 2.0
# IP Client : 88.186.29.99
# Fichier SQL compatible PHPMyadmin
#
# ******* debut du fichier ********
#
# Structure de la table grr_abt
#
DROP TABLE IF EXISTS `grr_abt`;
CREATE TABLE `grr_abt` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `abt_name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `order_display` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
#
# Données de grr_abt
#
INSERT INTO grr_abt  values ('1', 'Simple', '1');
INSERT INTO grr_abt  values ('2', 'Ecole', '2');
INSERT INTO grr_abt  values ('3', 'Ecole 2h', '3');
INSERT INTO grr_abt  values ('4', 'Entrainement', '4');
INSERT INTO grr_abt  values ('5', 'Licence', '5');
#
# Structure de la table grr_annonces
#
DROP TABLE IF EXISTS `grr_annonces`;
CREATE TABLE `grr_annonces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(40) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `dispos` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_annonces
#
#
# Structure de la table grr_area
#
DROP TABLE IF EXISTS `grr_area`;
CREATE TABLE `grr_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_name` varchar(30) NOT NULL DEFAULT '',
  `access` char(1) NOT NULL DEFAULT '',
  `order_display` smallint(6) NOT NULL DEFAULT '0',
  `ip_adr` varchar(15) NOT NULL DEFAULT '',
  `morningstarts_area` smallint(6) NOT NULL DEFAULT '0',
  `minute_morningstarts_area` smallint(6) NOT NULL DEFAULT '0',
  `eveningends_area` smallint(6) NOT NULL DEFAULT '0',
  `resolution_area` smallint(6) NOT NULL DEFAULT '0',
  `eveningends_minutes_area` smallint(6) NOT NULL DEFAULT '0',
  `weekstarts_area` smallint(6) NOT NULL DEFAULT '0',
  `twentyfourhour_format_area` smallint(6) NOT NULL DEFAULT '0',
  `calendar_default_values` char(1) NOT NULL DEFAULT 'y',
  `enable_periods` char(1) NOT NULL DEFAULT 'n',
  `display_days` varchar(7) NOT NULL DEFAULT 'yyyyyyy',
  `group_id` smallint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
#
# Données de grr_area
#
INSERT INTO grr_area  values ('1', 'Tennis', 'a', '1', '', '8', '0', '21', '3600', '0', '1', '1', 'n', 'n', 'yyyyyyy', '1');
INSERT INTO grr_area  values ('2', 'Badminton', 'a', '2', '', '8', '30', '20', '3600', '30', '1', '1', 'n', 'n', 'yyyyyyy', '2');
INSERT INTO grr_area  values ('3', 'Squash', 'a', '3', '', '8', '0', '19', '1800', '0', '1', '1', 'n', 'n', 'yyyyyyy', '3');
INSERT INTO grr_area  values ('6', 'Padel Tennis', 'a', '4', '', '8', '0', '19', '3600', '0', '1', '1', 'n', 'n', 'yyyyyyy', '5');
INSERT INTO grr_area  values ('8', 'Billard', 'a', '0', '', '8', '30', '22', '3600', '0', '0', '1', 'n', 'n', 'yyyyyyy', '4');
#
# Structure de la table grr_area_periodes
#
DROP TABLE IF EXISTS `grr_area_periodes`;
CREATE TABLE `grr_area_periodes` (
  `id_area` int(11) NOT NULL DEFAULT '0',
  `num_periode` smallint(6) NOT NULL DEFAULT '0',
  `nom_periode` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_area_periodes
#
INSERT INTO grr_area_periodes  values ('4', '0', 'Matin');
INSERT INTO grr_area_periodes  values ('4', '1', 'Après-midi');
INSERT INTO grr_area_periodes  values ('0', '0', 'Matin');
INSERT INTO grr_area_periodes  values ('0', '1', 'Après-midi');
#
# Structure de la table grr_calendar
#
DROP TABLE IF EXISTS `grr_calendar`;
CREATE TABLE `grr_calendar` (
  `DAY` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_calendar
#
#
# Structure de la table grr_categorie_compta
#
DROP TABLE IF EXISTS `grr_categorie_compta`;
CREATE TABLE `grr_categorie_compta` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
#
# Données de grr_categorie_compta
#
INSERT INTO grr_categorie_compta  values ('1', 'Salaires');
INSERT INTO grr_categorie_compta  values ('2', 'Achats matériels');
INSERT INTO grr_categorie_compta  values ('3', 'Abonnements');
INSERT INTO grr_categorie_compta  values ('4', 'Invités');
INSERT INTO grr_categorie_compta  values ('5', 'Charges');
#
# Structure de la table grr_compta
#
DROP TABLE IF EXISTS `grr_compta`;
CREATE TABLE `grr_compta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(40) NOT NULL,
  `statut` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `categorie` int(3) NOT NULL,
  `montant` float NOT NULL,
  `mode` text NOT NULL,
  `default_year` int(4) NOT NULL,
  `rap` int(1) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
#
# Données de grr_compta
#
INSERT INTO grr_compta  values ('1', 'ADMIN', 'administrateur', '2015-03-10', 'Achat balles', '2', '523', 'Chq', '2015', '0', '2015-06-07 20:29:04');
INSERT INTO grr_compta  values ('2', 'ADMIN', 'administrateur', '2014-10-15', 'Salaire Moniteur', '3', '1578', 'Virt', '2015', '0', '2015-10-23 19:19:24');
INSERT INTO grr_compta  values ('3', 'JOUEUR1', 'utilisateur', '2015-04-22', 'Abonnement', '3', '125', 'chq', '2015', '0', '2015-06-07 20:30:03');
INSERT INTO grr_compta  values ('4', 'JOUEUR1', 'utilisateur', '2016-02-07', 'ticket', '4', '20', 'tickets', '2016', '0', '2016-02-08 13:36:40');
INSERT INTO grr_compta  values ('5', 'JOUEUR1', 'utilisateur', '2016-02-09', 'ticket', '3', '40', 'tickets', '2016', '0', '2016-02-25 18:17:20');
#
# Structure de la table grr_compte_tresorerie
#
DROP TABLE IF EXISTS `grr_compte_tresorerie`;
CREATE TABLE `grr_compte_tresorerie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) CHARACTER SET utf8 NOT NULL,
  `solde` float NOT NULL,
  `courant` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
#
# Données de grr_compte_tresorerie
#
INSERT INTO grr_compte_tresorerie  values ('1', 'Compte courant', '15263', '1');
INSERT INTO grr_compte_tresorerie  values ('2', 'Livret épargne', '2456', '0');
#
# Structure de la table grr_entry
#
DROP TABLE IF EXISTS `grr_entry`;
CREATE TABLE `grr_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `entry_type` int(11) NOT NULL DEFAULT '0',
  `repeat_id` int(11) NOT NULL DEFAULT '0',
  `room_id` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_by` varchar(25) NOT NULL DEFAULT '',
  `name` varchar(80) NOT NULL DEFAULT '',
  `type` char(1) NOT NULL DEFAULT 'A',
  `description` text,
  `statut_entry` char(1) NOT NULL DEFAULT '-',
  `option_reservation` int(11) NOT NULL DEFAULT '0',
  `overload_desc` text,
  PRIMARY KEY (`id`),
  KEY `idxStartTime` (`start_time`),
  KEY `idxEndTime` (`end_time`)
) ENGINE=InnoDB AUTO_INCREMENT=2100 DEFAULT CHARSET=latin1;
#
# Données de grr_entry
#
INSERT INTO grr_entry  values ('365', '1452092400', '1452106800', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('366', '1452697200', '1452711600', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('367', '1453302000', '1453316400', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('368', '1453906800', '1453921200', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('369', '1454511600', '1454526000', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('370', '1455116400', '1455130800', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('371', '1455721200', '1455735600', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('372', '1456326000', '1456340400', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('373', '1456930800', '1456945200', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('374', '1457535600', '1457550000', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('375', '1458140400', '1458154800', '1', '2', '1', '2015-01-28 21:09:29', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('376', '1458745200', '1458759600', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('377', '1459346400', '1459360800', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('378', '1459951200', '1459965600', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('379', '1460556000', '1460570400', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('380', '1461160800', '1461175200', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('381', '1461765600', '1461780000', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('382', '1462370400', '1462384800', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('383', '1462975200', '1462989600', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('384', '1463580000', '1463594400', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('385', '1464184800', '1464199200', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('386', '1464789600', '1464804000', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('387', '1465394400', '1465408800', '1', '2', '1', '2015-01-28 21:09:30', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('388', '1465999200', '1466013600', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('389', '1466604000', '1466618400', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('390', '1467208800', '1467223200', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('391', '1467813600', '1467828000', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('392', '1468418400', '1468432800', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('393', '1469023200', '1469037600', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('394', '1469628000', '1469642400', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('395', '1470232800', '1470247200', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('396', '1470837600', '1470852000', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('397', '1471442400', '1471456800', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('398', '1472047200', '1472061600', '1', '2', '1', '2015-01-28 21:09:31', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('399', '1472652000', '1472666400', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('400', '1473256800', '1473271200', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('401', '1473861600', '1473876000', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('402', '1474466400', '1474480800', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('403', '1475071200', '1475085600', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('404', '1475676000', '1475690400', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('405', '1476280800', '1476295200', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('406', '1476885600', '1476900000', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('407', '1477490400', '1477504800', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('408', '1478098800', '1478113200', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('409', '1478703600', '1478718000', '1', '2', '1', '2015-01-28 21:09:32', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('410', '1479308400', '1479322800', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('411', '1479913200', '1479927600', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('412', '1480518000', '1480532400', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('413', '1481122800', '1481137200', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('414', '1481727600', '1481742000', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('415', '1482332400', '1482346800', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('416', '1482937200', '1482951600', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('417', '1483542000', '1483556400', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('418', '1484146800', '1484161200', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('419', '1484751600', '1484766000', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('420', '1485356400', '1485370800', '1', '2', '1', '2015-01-28 21:09:33', 'ADMIN', 'Entrainement Adultes', 'D', '', '-', '-1', '');
INSERT INTO grr_entry  values ('607', '1451638800', '1451653200', '1', '3', '1', '2015-04-04 19:33:22', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('608', '1451898000', '1451912400', '1', '3', '1', '2015-04-04 19:33:22', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('609', '1452070800', '1452085200', '1', '3', '1', '2015-04-04 19:33:22', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('610', '1452243600', '1452258000', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('611', '1452502800', '1452517200', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('612', '1452675600', '1452690000', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('613', '1452848400', '1452862800', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('614', '1453107600', '1453122000', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('615', '1453280400', '1453294800', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('616', '1453453200', '1453467600', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('617', '1453712400', '1453726800', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('618', '1453885200', '1453899600', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('619', '1454058000', '1454072400', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('620', '1454317200', '1454331600', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('621', '1454490000', '1454504400', '1', '3', '1', '2015-04-04 19:33:23', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('622', '1454662800', '1454677200', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('623', '1454922000', '1454936400', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('624', '1455094800', '1455109200', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('625', '1455267600', '1455282000', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('626', '1455526800', '1455541200', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('627', '1455699600', '1455714000', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('628', '1455872400', '1455886800', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('629', '1456131600', '1456146000', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('630', '1456304400', '1456318800', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('631', '1456477200', '1456491600', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('632', '1456736400', '1456750800', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('633', '1456909200', '1456923600', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('634', '1457082000', '1457096400', '1', '3', '1', '2015-04-04 19:33:24', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('635', '1457341200', '1457355600', '1', '3', '1', '2015-04-04 19:33:25', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('636', '1457514000', '1457528400', '1', '3', '1', '2015-04-04 19:33:25', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('637', '1457686800', '1457701200', '1', '3', '1', '2015-04-04 19:33:25', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('638', '1457946000', '1457960400', '1', '3', '1', '2015-04-04 19:33:25', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('639', '1458118800', '1458133200', '1', '3', '1', '2015-04-04 19:33:25', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('640', '1458291600', '1458306000', '1', '3', '1', '2015-04-04 19:33:25', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('641', '1458550800', '1458565200', '1', '3', '1', '2015-04-04 19:33:25', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('642', '1458723600', '1458738000', '1', '3', '1', '2015-04-04 19:33:25', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('643', '1458896400', '1458910800', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('644', '1459152000', '1459166400', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('645', '1459324800', '1459339200', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('646', '1459497600', '1459512000', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('647', '1459756800', '1459771200', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('648', '1459929600', '1459944000', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('649', '1460102400', '1460116800', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('650', '1460361600', '1460376000', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('651', '1460534400', '1460548800', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('652', '1460707200', '1460721600', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('653', '1460966400', '1460980800', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('654', '1461139200', '1461153600', '1', '3', '1', '2015-04-04 19:33:26', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('655', '1461312000', '1461326400', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('656', '1461571200', '1461585600', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('657', '1461744000', '1461758400', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('658', '1461916800', '1461931200', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('659', '1462176000', '1462190400', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('660', '1462348800', '1462363200', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('661', '1462521600', '1462536000', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('662', '1462780800', '1462795200', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('663', '1462953600', '1462968000', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('664', '1463126400', '1463140800', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('665', '1463385600', '1463400000', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('666', '1463558400', '1463572800', '1', '3', '1', '2015-04-04 19:33:27', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('667', '1463731200', '1463745600', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('668', '1463990400', '1464004800', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('669', '1464163200', '1464177600', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('670', '1464336000', '1464350400', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('671', '1464595200', '1464609600', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('672', '1464768000', '1464782400', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('673', '1464940800', '1464955200', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('674', '1465200000', '1465214400', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('675', '1465372800', '1465387200', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('676', '1465545600', '1465560000', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('677', '1465804800', '1465819200', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('678', '1465977600', '1465992000', '1', '3', '1', '2015-04-04 19:33:28', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('679', '1466150400', '1466164800', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('680', '1466409600', '1466424000', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('681', '1466582400', '1466596800', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('682', '1466755200', '1466769600', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('683', '1467014400', '1467028800', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('684', '1467187200', '1467201600', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('685', '1467360000', '1467374400', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('686', '1467619200', '1467633600', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('687', '1467792000', '1467806400', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('688', '1467964800', '1467979200', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('689', '1468224000', '1468238400', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('690', '1468396800', '1468411200', '1', '3', '1', '2015-04-04 19:33:29', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('691', '1468569600', '1468584000', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('692', '1468828800', '1468843200', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('693', '1469001600', '1469016000', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('694', '1469174400', '1469188800', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('695', '1469433600', '1469448000', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('696', '1469606400', '1469620800', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('697', '1469779200', '1469793600', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('698', '1470038400', '1470052800', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('699', '1470211200', '1470225600', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('700', '1470384000', '1470398400', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('701', '1470643200', '1470657600', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('702', '1470816000', '1470830400', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('703', '1470988800', '1471003200', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('704', '1471248000', '1471262400', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('705', '1471420800', '1471435200', '1', '3', '1', '2015-04-04 19:33:30', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('706', '1471593600', '1471608000', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('707', '1471852800', '1471867200', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('708', '1472025600', '1472040000', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('709', '1472198400', '1472212800', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('710', '1472457600', '1472472000', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('711', '1472630400', '1472644800', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('712', '1472803200', '1472817600', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('713', '1473062400', '1473076800', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('714', '1473235200', '1473249600', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('715', '1473408000', '1473422400', '1', '3', '1', '2015-04-04 19:33:31', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('716', '1473667200', '1473681600', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('717', '1473840000', '1473854400', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('718', '1474012800', '1474027200', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('719', '1474272000', '1474286400', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('720', '1474444800', '1474459200', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('721', '1474617600', '1474632000', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('722', '1474876800', '1474891200', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('723', '1475049600', '1475064000', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('724', '1475222400', '1475236800', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('725', '1475481600', '1475496000', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('726', '1475654400', '1475668800', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('727', '1475827200', '1475841600', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('728', '1476086400', '1476100800', '1', '3', '1', '2015-04-04 19:33:32', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('729', '1476259200', '1476273600', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('730', '1476432000', '1476446400', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('731', '1476691200', '1476705600', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('732', '1476864000', '1476878400', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('733', '1477036800', '1477051200', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('734', '1477296000', '1477310400', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('735', '1477468800', '1477483200', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('736', '1477641600', '1477656000', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('737', '1477904400', '1477918800', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('738', '1478077200', '1478091600', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('739', '1478250000', '1478264400', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('740', '1478509200', '1478523600', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('741', '1478682000', '1478696400', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('742', '1478854800', '1478869200', '1', '3', '1', '2015-04-04 19:33:33', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('743', '1479114000', '1479128400', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('744', '1479286800', '1479301200', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('745', '1479459600', '1479474000', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('746', '1479718800', '1479733200', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('747', '1479891600', '1479906000', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('748', '1480064400', '1480078800', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('749', '1480323600', '1480338000', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('750', '1480496400', '1480510800', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('751', '1480669200', '1480683600', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('752', '1480928400', '1480942800', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('753', '1481101200', '1481115600', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('754', '1481274000', '1481288400', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('755', '1481533200', '1481547600', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('756', '1481706000', '1481720400', '1', '3', '1', '2015-04-04 19:33:34', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('757', '1481878800', '1481893200', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('758', '1482138000', '1482152400', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('759', '1482310800', '1482325200', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('760', '1482483600', '1482498000', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('761', '1482742800', '1482757200', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('762', '1482915600', '1482930000', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('763', '1483088400', '1483102800', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('764', '1483347600', '1483362000', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('765', '1483520400', '1483534800', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('766', '1483693200', '1483707600', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('767', '1483952400', '1483966800', '1', '3', '1', '2015-04-04 19:33:35', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('768', '1484125200', '1484139600', '1', '3', '1', '2015-04-04 19:33:36', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('769', '1484298000', '1484312400', '1', '3', '1', '2015-04-04 19:33:36', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('770', '1484557200', '1484571600', '1', '3', '1', '2015-04-04 19:33:36', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('771', '1484730000', '1484744400', '1', '3', '1', '2015-04-04 19:33:36', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('772', '1484902800', '1484917200', '1', '3', '1', '2015-04-04 19:33:36', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('773', '1485162000', '1485176400', '1', '3', '1', '2015-04-04 19:33:36', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('774', '1485334800', '1485349200', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('775', '1485507600', '1485522000', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('776', '1485766800', '1485781200', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('777', '1485939600', '1485954000', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('778', '1486112400', '1486126800', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('779', '1486371600', '1486386000', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('780', '1486544400', '1486558800', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('781', '1486717200', '1486731600', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('782', '1486976400', '1486990800', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('783', '1487149200', '1487163600', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('784', '1487322000', '1487336400', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('785', '1487581200', '1487595600', '1', '3', '1', '2015-04-04 19:33:37', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('786', '1487754000', '1487768400', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('787', '1487926800', '1487941200', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('788', '1488186000', '1488200400', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('789', '1488358800', '1488373200', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('790', '1488531600', '1488546000', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('791', '1488790800', '1488805200', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('792', '1488963600', '1488978000', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('793', '1489136400', '1489150800', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('794', '1489395600', '1489410000', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('795', '1489568400', '1489582800', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('796', '1489741200', '1489755600', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('797', '1490000400', '1490014800', '1', '3', '1', '2015-04-04 19:33:38', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('798', '1490173200', '1490187600', '1', '3', '1', '2015-04-04 19:33:39', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('799', '1490346000', '1490360400', '1', '3', '1', '2015-04-04 19:33:39', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('800', '1490601600', '1490616000', '1', '3', '1', '2015-04-04 19:33:39', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('801', '1490774400', '1490788800', '1', '3', '1', '2015-04-04 19:33:39', 'ADMIN', 'Ecole de tennis', 'B', '', '-', '-1', '');
INSERT INTO grr_entry  values ('866', '1451761200', '1451768400', '1', '4', '1', '2015-10-31 14:15:01', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('867', '1451847600', '1451854800', '1', '4', '1', '2015-10-31 14:15:01', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('868', '1452020400', '1452027600', '1', '4', '1', '2015-10-31 14:15:01', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('870', '1452366000', '1452373200', '1', '4', '1', '2015-10-31 14:15:02', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('871', '1452452400', '1452459600', '1', '4', '1', '2015-10-31 14:15:02', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('872', '1452625200', '1452632400', '1', '4', '1', '2015-10-31 14:15:02', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('874', '1452970800', '1452978000', '1', '4', '1', '2015-10-31 14:15:02', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('875', '1453057200', '1453064400', '1', '4', '1', '2015-10-31 14:15:02', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('876', '1453230000', '1453237200', '1', '4', '1', '2015-10-31 14:15:02', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('878', '1453575600', '1453582800', '1', '4', '1', '2015-10-31 14:15:02', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('879', '1453662000', '1453669200', '1', '4', '1', '2015-10-31 14:15:02', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('880', '1453834800', '1453842000', '1', '4', '1', '2015-10-31 14:15:02', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('882', '1454180400', '1454187600', '1', '4', '1', '2015-10-31 14:15:03', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('883', '1454266800', '1454274000', '1', '4', '1', '2015-10-31 14:15:03', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('884', '1454439600', '1454446800', '1', '4', '1', '2015-10-31 14:15:03', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('886', '1454785200', '1454792400', '1', '4', '1', '2015-10-31 14:15:03', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('887', '1454871600', '1454878800', '1', '4', '1', '2015-10-31 14:15:03', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('888', '1455044400', '1455051600', '1', '4', '1', '2015-10-31 14:15:03', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('890', '1455390000', '1455397200', '1', '4', '1', '2015-10-31 14:15:03', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('891', '1455476400', '1455483600', '1', '4', '1', '2015-10-31 14:15:03', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('892', '1455649200', '1455656400', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('894', '1455994800', '1456002000', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('895', '1456081200', '1456088400', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('896', '1456254000', '1456261200', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('898', '1456599600', '1456606800', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('899', '1456686000', '1456693200', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('900', '1456858800', '1456866000', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('902', '1457204400', '1457211600', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('903', '1457290800', '1457298000', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('904', '1457463600', '1457470800', '1', '4', '1', '2015-10-31 14:15:04', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('906', '1457809200', '1457816400', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('907', '1457895600', '1457902800', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('908', '1458068400', '1458075600', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('910', '1458414000', '1458421200', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('911', '1458500400', '1458507600', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('912', '1458673200', '1458680400', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('914', '1459018800', '1459026000', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('915', '1459101600', '1459108800', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('916', '1459274400', '1459281600', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('918', '1459620000', '1459627200', '1', '4', '1', '2015-10-31 14:15:05', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('919', '1459706400', '1459713600', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('920', '1459879200', '1459886400', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('922', '1460224800', '1460232000', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('923', '1460311200', '1460318400', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('924', '1460484000', '1460491200', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('926', '1460829600', '1460836800', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('927', '1460916000', '1460923200', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('928', '1461088800', '1461096000', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('930', '1461434400', '1461441600', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('931', '1461520800', '1461528000', '1', '4', '1', '2015-10-31 14:15:06', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('932', '1461693600', '1461700800', '1', '4', '1', '2015-10-31 14:15:07', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('934', '1462039200', '1462046400', '1', '4', '1', '2015-10-31 14:15:07', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('935', '1462125600', '1462132800', '1', '4', '1', '2015-10-31 14:15:07', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('936', '1462298400', '1462305600', '1', '4', '1', '2015-10-31 14:15:07', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('938', '1462644000', '1462651200', '1', '4', '1', '2015-10-31 14:15:07', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('939', '1462730400', '1462737600', '1', '4', '1', '2015-10-31 14:15:07', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('940', '1462903200', '1462910400', '1', '4', '1', '2015-10-31 14:15:07', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('942', '1463248800', '1463256000', '1', '4', '1', '2015-10-31 14:15:07', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('943', '1463335200', '1463342400', '1', '4', '1', '2015-10-31 14:15:07', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('944', '1463508000', '1463515200', '1', '4', '1', '2015-10-31 14:15:08', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('946', '1463853600', '1463860800', '1', '4', '1', '2015-10-31 14:15:08', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('947', '1463940000', '1463947200', '1', '4', '1', '2015-10-31 14:15:08', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('948', '1464112800', '1464120000', '1', '4', '1', '2015-10-31 14:15:08', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('950', '1464458400', '1464465600', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('951', '1464544800', '1464552000', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('952', '1464717600', '1464724800', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('954', '1465063200', '1465070400', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('955', '1465149600', '1465156800', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('956', '1465322400', '1465329600', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('958', '1465668000', '1465675200', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('959', '1465754400', '1465761600', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('960', '1465927200', '1465934400', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('962', '1466272800', '1466280000', '1', '4', '1', '2015-10-31 14:15:09', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('963', '1466359200', '1466366400', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('964', '1466532000', '1466539200', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('966', '1466877600', '1466884800', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('967', '1466964000', '1466971200', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('968', '1467136800', '1467144000', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('970', '1467482400', '1467489600', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('971', '1467568800', '1467576000', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('972', '1467741600', '1467748800', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('974', '1468087200', '1468094400', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('975', '1468173600', '1468180800', '1', '4', '1', '2015-10-31 14:15:10', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('976', '1468346400', '1468353600', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('978', '1468692000', '1468699200', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('979', '1468778400', '1468785600', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('980', '1468951200', '1468958400', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('982', '1469296800', '1469304000', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('983', '1469383200', '1469390400', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('984', '1469556000', '1469563200', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('986', '1469901600', '1469908800', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('987', '1469988000', '1469995200', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('988', '1470160800', '1470168000', '1', '4', '1', '2015-10-31 14:15:11', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('990', '1470506400', '1470513600', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('991', '1470592800', '1470600000', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('992', '1470765600', '1470772800', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('994', '1471111200', '1471118400', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('995', '1471197600', '1471204800', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('996', '1471370400', '1471377600', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('998', '1471716000', '1471723200', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('999', '1471802400', '1471809600', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1000', '1471975200', '1471982400', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1002', '1472320800', '1472328000', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1003', '1472407200', '1472414400', '1', '4', '1', '2015-10-31 14:15:12', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1004', '1472580000', '1472587200', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1006', '1472925600', '1472932800', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1007', '1473012000', '1473019200', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1008', '1473184800', '1473192000', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1010', '1473530400', '1473537600', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1011', '1473616800', '1473624000', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1012', '1473789600', '1473796800', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1014', '1474135200', '1474142400', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1015', '1474221600', '1474228800', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1016', '1474394400', '1474401600', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1018', '1474740000', '1474747200', '1', '4', '1', '2015-10-31 14:15:13', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1019', '1474826400', '1474833600', '1', '4', '1', '2015-10-31 14:15:14', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1020', '1474999200', '1475006400', '1', '4', '1', '2015-10-31 14:15:14', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1022', '1475344800', '1475352000', '1', '4', '1', '2015-10-31 14:15:14', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1023', '1475431200', '1475438400', '1', '4', '1', '2015-10-31 14:15:14', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1024', '1475604000', '1475611200', '1', '4', '1', '2015-10-31 14:15:14', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1026', '1475949600', '1475956800', '1', '4', '1', '2015-10-31 14:15:14', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1027', '1476036000', '1476043200', '1', '4', '1', '2015-10-31 14:15:14', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1028', '1476208800', '1476216000', '1', '4', '1', '2015-10-31 14:15:14', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1030', '1476554400', '1476561600', '1', '4', '1', '2015-10-31 14:15:14', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1031', '1476640800', '1476648000', '1', '4', '1', '2015-10-31 14:15:15', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1032', '1476813600', '1476820800', '1', '4', '1', '2015-10-31 14:15:15', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1034', '1477159200', '1477166400', '1', '4', '1', '2015-10-31 14:15:15', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1035', '1477245600', '1477252800', '1', '4', '1', '2015-10-31 14:15:15', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1036', '1477418400', '1477425600', '1', '4', '1', '2015-10-31 14:15:15', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1038', '1477764000', '1477771200', '1', '4', '1', '2015-10-31 14:15:15', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1076', '1451761200', '1451768400', '1', '5', '2', '2015-10-31 14:15:18', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1077', '1451847600', '1451854800', '1', '5', '2', '2015-10-31 14:15:18', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1078', '1452020400', '1452027600', '1', '5', '2', '2015-10-31 14:15:19', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1080', '1452366000', '1452373200', '1', '5', '2', '2015-10-31 14:15:19', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1081', '1452452400', '1452459600', '1', '5', '2', '2015-10-31 14:15:19', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1082', '1452625200', '1452632400', '1', '5', '2', '2015-10-31 14:15:19', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1084', '1452970800', '1452978000', '1', '5', '2', '2015-10-31 14:15:19', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1085', '1453057200', '1453064400', '1', '5', '2', '2015-10-31 14:15:19', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1086', '1453230000', '1453237200', '1', '5', '2', '2015-10-31 14:15:19', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1088', '1453575600', '1453582800', '1', '5', '2', '2015-10-31 14:15:20', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1089', '1453662000', '1453669200', '1', '5', '2', '2015-10-31 14:15:20', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1090', '1453834800', '1453842000', '1', '5', '2', '2015-10-31 14:15:20', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1092', '1454180400', '1454187600', '1', '5', '2', '2015-10-31 14:15:20', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1093', '1454266800', '1454274000', '1', '5', '2', '2015-10-31 14:15:20', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1094', '1454439600', '1454446800', '1', '5', '2', '2015-10-31 14:15:20', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1096', '1454785200', '1454792400', '1', '5', '2', '2015-10-31 14:15:20', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1097', '1454871600', '1454878800', '1', '5', '2', '2015-10-31 14:15:20', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1098', '1455044400', '1455051600', '1', '5', '2', '2015-10-31 14:15:20', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1100', '1455390000', '1455397200', '1', '5', '2', '2015-10-31 14:15:21', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1101', '1455476400', '1455483600', '1', '5', '2', '2015-10-31 14:15:21', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1102', '1455649200', '1455656400', '1', '5', '2', '2015-10-31 14:15:21', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1104', '1455994800', '1456002000', '1', '5', '2', '2015-10-31 14:15:21', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1105', '1456081200', '1456088400', '1', '5', '2', '2015-10-31 14:15:21', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1106', '1456254000', '1456261200', '1', '5', '2', '2015-10-31 14:15:21', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1108', '1456599600', '1456606800', '1', '5', '2', '2015-10-31 14:15:21', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1109', '1456686000', '1456693200', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1110', '1456858800', '1456866000', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1112', '1457204400', '1457211600', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1113', '1457290800', '1457298000', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1114', '1457463600', '1457470800', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1116', '1457809200', '1457816400', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1117', '1457895600', '1457902800', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1118', '1458068400', '1458075600', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1120', '1458414000', '1458421200', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1121', '1458500400', '1458507600', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1122', '1458673200', '1458680400', '1', '5', '2', '2015-10-31 14:15:22', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1124', '1459018800', '1459026000', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1125', '1459101600', '1459108800', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1126', '1459274400', '1459281600', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1128', '1459620000', '1459627200', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1129', '1459706400', '1459713600', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1130', '1459879200', '1459886400', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1132', '1460224800', '1460232000', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1133', '1460311200', '1460318400', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1134', '1460484000', '1460491200', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1136', '1460829600', '1460836800', '1', '5', '2', '2015-10-31 14:15:23', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1137', '1460916000', '1460923200', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1138', '1461088800', '1461096000', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1140', '1461434400', '1461441600', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1141', '1461520800', '1461528000', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1142', '1461693600', '1461700800', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1144', '1462039200', '1462046400', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1145', '1462125600', '1462132800', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1146', '1462298400', '1462305600', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1148', '1462644000', '1462651200', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1149', '1462730400', '1462737600', '1', '5', '2', '2015-10-31 14:15:24', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1150', '1462903200', '1462910400', '1', '5', '2', '2015-10-31 14:15:25', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1152', '1463248800', '1463256000', '1', '5', '2', '2015-10-31 14:15:25', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1153', '1463335200', '1463342400', '1', '5', '2', '2015-10-31 14:15:25', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1154', '1463508000', '1463515200', '1', '5', '2', '2015-10-31 14:15:25', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1156', '1463853600', '1463860800', '1', '5', '2', '2015-10-31 14:15:25', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1157', '1463940000', '1463947200', '1', '5', '2', '2015-10-31 14:15:25', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1158', '1464112800', '1464120000', '1', '5', '2', '2015-10-31 14:15:25', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1160', '1464458400', '1464465600', '1', '5', '2', '2015-10-31 14:15:25', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1161', '1464544800', '1464552000', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1162', '1464717600', '1464724800', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1164', '1465063200', '1465070400', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1165', '1465149600', '1465156800', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1166', '1465322400', '1465329600', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1168', '1465668000', '1465675200', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1169', '1465754400', '1465761600', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1170', '1465927200', '1465934400', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1172', '1466272800', '1466280000', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1173', '1466359200', '1466366400', '1', '5', '2', '2015-10-31 14:15:26', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1174', '1466532000', '1466539200', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1176', '1466877600', '1466884800', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1177', '1466964000', '1466971200', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1178', '1467136800', '1467144000', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1180', '1467482400', '1467489600', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1181', '1467568800', '1467576000', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1182', '1467741600', '1467748800', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1184', '1468087200', '1468094400', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1185', '1468173600', '1468180800', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1186', '1468346400', '1468353600', '1', '5', '2', '2015-10-31 14:15:27', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1188', '1468692000', '1468699200', '1', '5', '2', '2015-10-31 14:15:28', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1189', '1468778400', '1468785600', '1', '5', '2', '2015-10-31 14:15:28', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1190', '1468951200', '1468958400', '1', '5', '2', '2015-10-31 14:15:28', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1192', '1469296800', '1469304000', '1', '5', '2', '2015-10-31 14:15:28', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1193', '1469383200', '1469390400', '1', '5', '2', '2015-10-31 14:15:28', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1194', '1469556000', '1469563200', '1', '5', '2', '2015-10-31 14:15:28', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1196', '1469901600', '1469908800', '1', '5', '2', '2015-10-31 14:15:28', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1197', '1469988000', '1469995200', '1', '5', '2', '2015-10-31 14:15:28', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1198', '1470160800', '1470168000', '1', '5', '2', '2015-10-31 14:15:28', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1200', '1470506400', '1470513600', '1', '5', '2', '2015-10-31 14:15:29', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1201', '1470592800', '1470600000', '1', '5', '2', '2015-10-31 14:15:29', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1202', '1470765600', '1470772800', '1', '5', '2', '2015-10-31 14:15:29', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1204', '1471111200', '1471118400', '1', '5', '2', '2015-10-31 14:15:29', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1205', '1471197600', '1471204800', '1', '5', '2', '2015-10-31 14:15:29', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1206', '1471370400', '1471377600', '1', '5', '2', '2015-10-31 14:15:29', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1208', '1471716000', '1471723200', '1', '5', '2', '2015-10-31 14:15:29', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1209', '1471802400', '1471809600', '1', '5', '2', '2015-10-31 14:15:30', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1210', '1471975200', '1471982400', '1', '5', '2', '2015-10-31 14:15:30', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1212', '1472320800', '1472328000', '1', '5', '2', '2015-10-31 14:15:30', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1213', '1472407200', '1472414400', '1', '5', '2', '2015-10-31 14:15:30', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1214', '1472580000', '1472587200', '1', '5', '2', '2015-10-31 14:15:30', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1216', '1472925600', '1472932800', '1', '5', '2', '2015-10-31 14:15:30', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1217', '1473012000', '1473019200', '1', '5', '2', '2015-10-31 14:15:30', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1218', '1473184800', '1473192000', '1', '5', '2', '2015-10-31 14:15:30', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1220', '1473530400', '1473537600', '1', '5', '2', '2015-10-31 14:15:30', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1221', '1473616800', '1473624000', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1222', '1473789600', '1473796800', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1224', '1474135200', '1474142400', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1225', '1474221600', '1474228800', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1226', '1474394400', '1474401600', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1228', '1474740000', '1474747200', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1229', '1474826400', '1474833600', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1230', '1474999200', '1475006400', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1232', '1475344800', '1475352000', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1233', '1475431200', '1475438400', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1234', '1475604000', '1475611200', '1', '5', '2', '2015-10-31 14:15:31', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1236', '1475949600', '1475956800', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1237', '1476036000', '1476043200', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1238', '1476208800', '1476216000', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1240', '1476554400', '1476561600', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1241', '1476640800', '1476648000', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1242', '1476813600', '1476820800', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1244', '1477159200', '1477166400', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1245', '1477245600', '1477252800', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1246', '1477418400', '1477425600', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1248', '1477764000', '1477771200', '1', '5', '2', '2015-10-31 14:15:32', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1285', '1451588400', '1451595600', '1', '6', '3', '2015-10-31 14:15:36', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1286', '1451761200', '1451768400', '1', '6', '3', '2015-10-31 14:15:36', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1287', '1451847600', '1451854800', '1', '6', '3', '2015-10-31 14:15:36', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1288', '1452020400', '1452027600', '1', '6', '3', '2015-10-31 14:15:36', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1289', '1452193200', '1452200400', '1', '6', '3', '2015-10-31 14:15:36', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1290', '1452366000', '1452373200', '1', '6', '3', '2015-10-31 14:15:36', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1291', '1452452400', '1452459600', '1', '6', '3', '2015-10-31 14:15:36', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1292', '1452625200', '1452632400', '1', '6', '3', '2015-10-31 14:15:36', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1293', '1452798000', '1452805200', '1', '6', '3', '2015-10-31 14:15:36', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1294', '1452970800', '1452978000', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1295', '1453057200', '1453064400', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1296', '1453230000', '1453237200', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1297', '1453402800', '1453410000', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1298', '1453575600', '1453582800', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1299', '1453662000', '1453669200', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1300', '1453834800', '1453842000', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1301', '1454007600', '1454014800', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1302', '1454180400', '1454187600', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1303', '1454266800', '1454274000', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1304', '1454439600', '1454446800', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1305', '1454612400', '1454619600', '1', '6', '3', '2015-10-31 14:15:37', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1306', '1454785200', '1454792400', '1', '6', '3', '2015-10-31 14:15:38', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1307', '1454871600', '1454878800', '1', '6', '3', '2015-10-31 14:15:38', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1308', '1455044400', '1455051600', '1', '6', '3', '2015-10-31 14:15:38', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1309', '1455217200', '1455224400', '1', '6', '3', '2015-10-31 14:15:38', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1310', '1455390000', '1455397200', '1', '6', '3', '2015-10-31 14:15:38', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1311', '1455476400', '1455483600', '1', '6', '3', '2015-10-31 14:15:38', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1312', '1455649200', '1455656400', '1', '6', '3', '2015-10-31 14:15:38', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1313', '1455822000', '1455829200', '1', '6', '3', '2015-10-31 14:15:38', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1314', '1455994800', '1456002000', '1', '6', '3', '2015-10-31 14:15:38', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1315', '1456081200', '1456088400', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1316', '1456254000', '1456261200', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1317', '1456426800', '1456434000', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1318', '1456599600', '1456606800', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1319', '1456686000', '1456693200', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1320', '1456858800', '1456866000', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1321', '1457031600', '1457038800', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1322', '1457204400', '1457211600', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1323', '1457290800', '1457298000', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1324', '1457463600', '1457470800', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1325', '1457636400', '1457643600', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1326', '1457809200', '1457816400', '1', '6', '3', '2015-10-31 14:15:39', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1327', '1457895600', '1457902800', '1', '6', '3', '2015-10-31 14:15:40', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1328', '1458068400', '1458075600', '1', '6', '3', '2015-10-31 14:15:40', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1329', '1458241200', '1458248400', '1', '6', '3', '2015-10-31 14:15:40', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1330', '1458414000', '1458421200', '1', '6', '3', '2015-10-31 14:15:40', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1331', '1458500400', '1458507600', '1', '6', '3', '2015-10-31 14:15:40', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1332', '1458673200', '1458680400', '1', '6', '3', '2015-10-31 14:15:40', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1333', '1458846000', '1458853200', '1', '6', '3', '2015-10-31 14:15:40', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1334', '1459018800', '1459026000', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1335', '1459101600', '1459108800', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1336', '1459274400', '1459281600', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1337', '1459447200', '1459454400', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1338', '1459620000', '1459627200', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1339', '1459706400', '1459713600', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1340', '1459879200', '1459886400', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1341', '1460052000', '1460059200', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1342', '1460224800', '1460232000', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1343', '1460311200', '1460318400', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1344', '1460484000', '1460491200', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1345', '1460656800', '1460664000', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1346', '1460829600', '1460836800', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1347', '1460916000', '1460923200', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1348', '1461088800', '1461096000', '1', '6', '3', '2015-10-31 14:15:41', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1349', '1461261600', '1461268800', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1350', '1461434400', '1461441600', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1351', '1461520800', '1461528000', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1352', '1461693600', '1461700800', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1353', '1461866400', '1461873600', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1354', '1462039200', '1462046400', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1355', '1462125600', '1462132800', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1356', '1462298400', '1462305600', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1357', '1462471200', '1462478400', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1358', '1462644000', '1462651200', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1359', '1462730400', '1462737600', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1360', '1462903200', '1462910400', '1', '6', '3', '2015-10-31 14:15:42', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1361', '1463076000', '1463083200', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1362', '1463248800', '1463256000', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1363', '1463335200', '1463342400', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1364', '1463508000', '1463515200', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1365', '1463680800', '1463688000', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1366', '1463853600', '1463860800', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1367', '1463940000', '1463947200', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1368', '1464112800', '1464120000', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1369', '1464285600', '1464292800', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1370', '1464458400', '1464465600', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1371', '1464544800', '1464552000', '1', '6', '3', '2015-10-31 14:15:43', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1372', '1464717600', '1464724800', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1373', '1464890400', '1464897600', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1374', '1465063200', '1465070400', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1375', '1465149600', '1465156800', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1376', '1465322400', '1465329600', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1377', '1465495200', '1465502400', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1378', '1465668000', '1465675200', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1379', '1465754400', '1465761600', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1380', '1465927200', '1465934400', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1381', '1466100000', '1466107200', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1382', '1466272800', '1466280000', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1383', '1466359200', '1466366400', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1384', '1466532000', '1466539200', '1', '6', '3', '2015-10-31 14:15:44', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1385', '1466704800', '1466712000', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1386', '1466877600', '1466884800', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1387', '1466964000', '1466971200', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1388', '1467136800', '1467144000', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1389', '1467309600', '1467316800', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1390', '1467482400', '1467489600', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1391', '1467568800', '1467576000', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1392', '1467741600', '1467748800', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1393', '1467914400', '1467921600', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1394', '1468087200', '1468094400', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1395', '1468173600', '1468180800', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1396', '1468346400', '1468353600', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1397', '1468519200', '1468526400', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1398', '1468692000', '1468699200', '1', '6', '3', '2015-10-31 14:15:45', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1399', '1468778400', '1468785600', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1400', '1468951200', '1468958400', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1401', '1469124000', '1469131200', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1402', '1469296800', '1469304000', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1403', '1469383200', '1469390400', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1404', '1469556000', '1469563200', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1405', '1469728800', '1469736000', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1406', '1469901600', '1469908800', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1407', '1469988000', '1469995200', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1408', '1470160800', '1470168000', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1409', '1470333600', '1470340800', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1410', '1470506400', '1470513600', '1', '6', '3', '2015-10-31 14:15:46', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1411', '1470592800', '1470600000', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1412', '1470765600', '1470772800', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1413', '1470938400', '1470945600', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1414', '1471111200', '1471118400', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1415', '1471197600', '1471204800', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1416', '1471370400', '1471377600', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1417', '1471543200', '1471550400', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1418', '1471716000', '1471723200', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1419', '1471802400', '1471809600', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1420', '1471975200', '1471982400', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1421', '1472148000', '1472155200', '1', '6', '3', '2015-10-31 14:15:47', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1422', '1472320800', '1472328000', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1423', '1472407200', '1472414400', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1424', '1472580000', '1472587200', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1425', '1472752800', '1472760000', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1426', '1472925600', '1472932800', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1427', '1473012000', '1473019200', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1428', '1473184800', '1473192000', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1429', '1473357600', '1473364800', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1430', '1473530400', '1473537600', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1431', '1473616800', '1473624000', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1432', '1473789600', '1473796800', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1433', '1473962400', '1473969600', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1434', '1474135200', '1474142400', '1', '6', '3', '2015-10-31 14:15:48', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1435', '1474221600', '1474228800', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1436', '1474394400', '1474401600', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1437', '1474567200', '1474574400', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1438', '1474740000', '1474747200', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1439', '1474826400', '1474833600', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1440', '1474999200', '1475006400', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1441', '1475172000', '1475179200', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1442', '1475344800', '1475352000', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1443', '1475431200', '1475438400', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1444', '1475604000', '1475611200', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1445', '1475776800', '1475784000', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1446', '1475949600', '1475956800', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1447', '1476036000', '1476043200', '1', '6', '3', '2015-10-31 14:15:49', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1448', '1476208800', '1476216000', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1449', '1476381600', '1476388800', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1450', '1476554400', '1476561600', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1451', '1476640800', '1476648000', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1452', '1476813600', '1476820800', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1453', '1476986400', '1476993600', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1454', '1477159200', '1477166400', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1455', '1477245600', '1477252800', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1456', '1477418400', '1477425600', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1457', '1477591200', '1477598400', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1458', '1477764000', '1477771200', '1', '6', '3', '2015-10-31 14:15:50', 'ADMIN', 'Tournoi', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1469', '1453554000', '1453557600', '0', '0', '1', '2016-01-22 21:36:33', 'JOUEUR1', 'JOUEUR TENNIS1', 'A', 'JOUEUR TENNIS2', '-', '-1', '');
INSERT INTO grr_entry  values ('1471', '1455292800', '1455296400', '0', '0', '1', '2016-02-08 13:40:24', 'JOUEUR2', 'JOUEUR TENNIS2', 'A', 'JOUEUR TENNIS1', '-', '-1', '');
INSERT INTO grr_entry  values ('1472', '1455289200', '1455292800', '0', '0', '3', '2016-02-08 13:42:05', 'JOUEUR2', 'JOUEUR TENNIS2', 'A', 'invite ', '-', '-1', '');
INSERT INTO grr_entry  values ('1473', '1458568800', '1458572400', '0', '0', '2', '2016-03-21 15:41:33', 'ADMIN', 'te', 'A', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1474', '1461657600', '1461661200', '0', '0', '1', '2016-05-20 10:25:33', 'ADMIN', 'a', 'A', '2', '-', '-1', '');
INSERT INTO grr_entry  values ('1476', '1471608000', '1471611600', '0', '0', '1', '2016-08-18 18:27:11', 'JOUEUR2', 'JOUEUR TENNIS2', 'A', 'invite ', '-', '-1', '');
INSERT INTO grr_entry  values ('1477', '1473692400', '1473696000', '0', '0', '1', '2016-09-14 17:20:36', 'ADMIN', 'tournoi de jc', 'H', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1479', '1479132000', '1479142800', '0', '0', '1', '2016-11-17 13:26:56', 'ADMIN', 'Interclub', 'G', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1480', '1479132000', '1479142800', '0', '0', '2', '2016-11-17 13:26:56', 'ADMIN', 'Interclub', 'G', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1481', '1479132000', '1479142800', '0', '0', '3', '2016-11-17 13:26:56', 'ADMIN', 'Interclub', 'G', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1482', '1479132000', '1479142800', '0', '0', '16', '2016-11-17 13:26:56', 'ADMIN', 'Interclub', 'G', '', '-', '-1', '');
INSERT INTO grr_entry  values ('1483', '1479459600', '1479463200', '0', '0', '2', '2016-11-17 13:29:40', 'JOUEUR2', 'JOUEUR TENNIS2', 'A', 'JOUEUR TENNIS1', '-', '-1', '');
INSERT INTO grr_entry  values ('1484', '1483736400', '1483740000', '0', '0', '2', '2017-01-05 22:42:39', 'ADMIN', 'om', 'A', '2', '-', '-1', '');
INSERT INTO grr_entry  values ('1485', '1484557200', '1484564400', '0', '0', '12', '2017-01-22 11:00:36', 'ADMIN', 'Réservation sqash', 'K', 'bla', '-', '-1', '');
INSERT INTO grr_entry  values ('1486', '1484557200', '1484560800', '0', '0', '2', '2017-01-22 11:01:23', 'ADMIN', 'wxdf', 'B', 'sdf', '-', '-1', '');
INSERT INTO grr_entry  values ('1487', '1486314000', '1486317600', '0', '0', '1', '2017-02-05 15:55:14', 'JOUEUR2', 'JOUEUR TENNIS2', 'A', 'JOUEUR TENNIS1', '-', '-1', '');
INSERT INTO grr_entry  values ('1488', '1486472400', '1486476000', '0', '0', '2', '2017-02-05 15:55:32', 'JOUEUR2', 'JOUEUR TENNIS2', 'A', 'JOUEUR TENNIS1', '-', '-1', '');
INSERT INTO grr_entry  values ('1494', '1451545200', '1451595600', '0', '0', '1', '2017-03-02 09:49:30', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1495', '1452150000', '1452200400', '0', '0', '1', '2017-03-02 09:49:30', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1496', '1452754800', '1452805200', '0', '0', '1', '2017-03-02 09:49:30', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1497', '1453359600', '1453410000', '0', '0', '1', '2017-03-02 09:49:30', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1498', '1453964400', '1454014800', '0', '0', '1', '2017-03-02 09:49:30', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1499', '1454569200', '1454619600', '0', '0', '1', '2017-03-02 09:49:30', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1500', '1455174000', '1455224400', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1501', '1455778800', '1455829200', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1502', '1456383600', '1456434000', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1503', '1456988400', '1457038800', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1504', '1457593200', '1457643600', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1505', '1458198000', '1458248400', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1506', '1458802800', '1458853200', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1507', '1459404000', '1459454400', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1508', '1460008800', '1460059200', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1509', '1460613600', '1460664000', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1510', '1461218400', '1461268800', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1511', '1461823200', '1461873600', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1512', '1462428000', '1462478400', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1513', '1463032800', '1463083200', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1514', '1463637600', '1463688000', '0', '0', '1', '2017-03-02 09:49:31', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1515', '1464242400', '1464292800', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1516', '1464847200', '1464897600', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1517', '1465452000', '1465502400', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1518', '1466056800', '1466107200', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1519', '1466661600', '1466712000', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1520', '1467266400', '1467316800', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1521', '1467871200', '1467921600', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1522', '1468476000', '1468526400', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1523', '1469080800', '1469131200', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1524', '1469685600', '1469736000', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1525', '1470290400', '1470340800', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1526', '1470895200', '1470945600', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1527', '1471500000', '1471550400', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1528', '1472104800', '1472155200', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1529', '1472709600', '1472760000', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1530', '1473314400', '1473364800', '0', '0', '1', '2017-03-02 09:49:32', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1531', '1473919200', '1473969600', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1532', '1474524000', '1474574400', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1533', '1475128800', '1475179200', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1534', '1475733600', '1475784000', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1535', '1476338400', '1476388800', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1536', '1476943200', '1476993600', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1537', '1477548000', '1477598400', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1538', '1478156400', '1478206800', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1539', '1478761200', '1478811600', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1540', '1479366000', '1479416400', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1541', '1479970800', '1480021200', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1542', '1480575600', '1480626000', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1543', '1481180400', '1481230800', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1544', '1481785200', '1481835600', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1545', '1482390000', '1482440400', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1546', '1482994800', '1483045200', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1547', '1483599600', '1483650000', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1548', '1484204400', '1484254800', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1549', '1484809200', '1484859600', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1550', '1485414000', '1485464400', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1551', '1486018800', '1486069200', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1552', '1486623600', '1486674000', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1553', '1487228400', '1487278800', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1554', '1487833200', '1487883600', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1555', '1488438000', '1488488400', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1556', '1489042800', '1489093200', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1557', '1489647600', '1489698000', '0', '0', '1', '2017-03-02 09:49:33', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1558', '1490252400', '1490302800', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1559', '1490853600', '1490904000', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1560', '1491458400', '1491508800', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1561', '1492063200', '1492113600', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1562', '1492668000', '1492718400', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1563', '1493272800', '1493323200', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1564', '1493877600', '1493928000', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1565', '1494482400', '1494532800', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1566', '1495087200', '1495137600', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1567', '1495692000', '1495742400', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1568', '1496296800', '1496347200', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1569', '1496901600', '1496952000', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1570', '1497506400', '1497556800', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1571', '1498111200', '1498161600', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1572', '1498716000', '1498766400', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1573', '1499320800', '1499371200', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1574', '1499925600', '1499976000', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1575', '1500530400', '1500580800', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1576', '1501135200', '1501185600', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1577', '1501740000', '1501790400', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1578', '1502344800', '1502395200', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1579', '1502949600', '1503000000', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1580', '1503554400', '1503604800', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1581', '1504159200', '1504209600', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1582', '1504764000', '1504814400', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1583', '1505368800', '1505419200', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1584', '1505973600', '1506024000', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1585', '1506578400', '1506628800', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1586', '1507183200', '1507233600', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1587', '1507788000', '1507838400', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1588', '1508392800', '1508443200', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1589', '1508997600', '1509048000', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1590', '1509606000', '1509656400', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1591', '1510210800', '1510261200', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1592', '1510815600', '1510866000', '0', '0', '1', '2017-03-02 09:49:34', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1593', '1511420400', '1511470800', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1594', '1512025200', '1512075600', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1595', '1512630000', '1512680400', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1596', '1513234800', '1513285200', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1597', '1513839600', '1513890000', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1598', '1514444400', '1514494800', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1599', '1515049200', '1515099600', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1600', '1515654000', '1515704400', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1601', '1516258800', '1516309200', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1602', '1516863600', '1516914000', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1603', '1517468400', '1517518800', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1604', '1518073200', '1518123600', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1605', '1518678000', '1518728400', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1606', '1519282800', '1519333200', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1607', '1519887600', '1519938000', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1608', '1520492400', '1520542800', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1609', '1521097200', '1521147600', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1610', '1521702000', '1521752400', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1611', '1522303200', '1522353600', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1612', '1522908000', '1522958400', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1613', '1523512800', '1523563200', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1614', '1524117600', '1524168000', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1615', '1524722400', '1524772800', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1616', '1525327200', '1525377600', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1617', '1525932000', '1525982400', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1618', '1526536800', '1526587200', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1619', '1527141600', '1527192000', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1620', '1527746400', '1527796800', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1621', '1528351200', '1528401600', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1622', '1528956000', '1529006400', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1623', '1529560800', '1529611200', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1624', '1530165600', '1530216000', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1625', '1530770400', '1530820800', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1626', '1531375200', '1531425600', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1627', '1531980000', '1532030400', '0', '0', '1', '2017-03-02 09:49:35', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1628', '1532584800', '1532635200', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1629', '1533189600', '1533240000', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1630', '1533794400', '1533844800', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1631', '1534399200', '1534449600', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1632', '1535004000', '1535054400', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1633', '1535608800', '1535659200', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1634', '1536213600', '1536264000', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1635', '1536818400', '1536868800', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1636', '1537423200', '1537473600', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1637', '1538028000', '1538078400', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1638', '1538632800', '1538683200', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1639', '1539237600', '1539288000', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1640', '1539842400', '1539892800', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1641', '1540447200', '1540497600', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1642', '1541055600', '1541106000', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1643', '1541660400', '1541710800', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1644', '1542265200', '1542315600', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1645', '1542870000', '1542920400', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1646', '1543474800', '1543525200', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1647', '1544079600', '1544130000', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1648', '1544684400', '1544734800', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1649', '1545289200', '1545339600', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1650', '1545894000', '1545944400', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1651', '1546498800', '1546549200', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1652', '1547103600', '1547154000', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1653', '1547708400', '1547758800', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1654', '1548313200', '1548363600', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1655', '1548918000', '1548968400', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1656', '1549522800', '1549573200', '0', '0', '1', '2017-03-02 09:49:36', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1657', '1550127600', '1550178000', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1658', '1550732400', '1550782800', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1659', '1551337200', '1551387600', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1660', '1551942000', '1551992400', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1661', '1552546800', '1552597200', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1662', '1553151600', '1553202000', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1663', '1553756400', '1553806800', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1664', '1554357600', '1554408000', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1665', '1554962400', '1555012800', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1666', '1555567200', '1555617600', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1667', '1556172000', '1556222400', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1668', '1556776800', '1556827200', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1669', '1557381600', '1557432000', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1670', '1557986400', '1558036800', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1671', '1558591200', '1558641600', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1672', '1559196000', '1559246400', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1673', '1559800800', '1559851200', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1674', '1560405600', '1560456000', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1675', '1561010400', '1561060800', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1676', '1561615200', '1561665600', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1677', '1562220000', '1562270400', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1678', '1562824800', '1562875200', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1679', '1563429600', '1563480000', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1680', '1564034400', '1564084800', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1681', '1564639200', '1564689600', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1682', '1565244000', '1565294400', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1683', '1565848800', '1565899200', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1684', '1566453600', '1566504000', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1685', '1567058400', '1567108800', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1686', '1567663200', '1567713600', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1687', '1568268000', '1568318400', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1688', '1568872800', '1568923200', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1689', '1569477600', '1569528000', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1690', '1570082400', '1570132800', '0', '0', '1', '2017-03-02 09:49:37', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1691', '1570687200', '1570737600', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1692', '1571292000', '1571342400', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1693', '1571896800', '1571947200', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1694', '1572505200', '1572555600', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1695', '1573110000', '1573160400', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1696', '1573714800', '1573765200', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1697', '1574319600', '1574370000', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1698', '1574924400', '1574974800', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1699', '1575529200', '1575579600', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1700', '1576134000', '1576184400', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1701', '1576738800', '1576789200', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1702', '1577343600', '1577394000', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1703', '1577948400', '1577998800', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1704', '1578553200', '1578603600', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1705', '1579158000', '1579208400', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1706', '1579762800', '1579813200', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1707', '1580367600', '1580418000', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1708', '1580972400', '1581022800', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1709', '1581577200', '1581627600', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1710', '1582182000', '1582232400', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1711', '1582786800', '1582837200', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1712', '1583391600', '1583442000', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1713', '1583996400', '1584046800', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1714', '1584601200', '1584651600', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1715', '1585206000', '1585256400', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1716', '1585807200', '1585857600', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1717', '1586412000', '1586462400', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1718', '1587016800', '1587067200', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1719', '1587621600', '1587672000', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1720', '1588226400', '1588276800', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1721', '1588831200', '1588881600', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1722', '1589436000', '1589486400', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1723', '1590040800', '1590091200', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1724', '1590645600', '1590696000', '0', '0', '1', '2017-03-02 09:49:38', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1725', '1591250400', '1591300800', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1726', '1591855200', '1591905600', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1727', '1592460000', '1592510400', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1728', '1593064800', '1593115200', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1729', '1593669600', '1593720000', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1730', '1594274400', '1594324800', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1731', '1594879200', '1594929600', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1732', '1595484000', '1595534400', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1733', '1596088800', '1596139200', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1734', '1596693600', '1596744000', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1735', '1597298400', '1597348800', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1736', '1597903200', '1597953600', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1737', '1598508000', '1598558400', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1738', '1599112800', '1599163200', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1739', '1599717600', '1599768000', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1740', '1600322400', '1600372800', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1741', '1600927200', '1600977600', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1742', '1601532000', '1601582400', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1743', '1602136800', '1602187200', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1744', '1602741600', '1602792000', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1745', '1603346400', '1603396800', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1746', '1603954800', '1604005200', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1747', '1604559600', '1604610000', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1748', '1605164400', '1605214800', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1749', '1605769200', '1605819600', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1750', '1606374000', '1606424400', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1751', '1606978800', '1607029200', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1752', '1607583600', '1607634000', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1753', '1608188400', '1608238800', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1754', '1608793200', '1608843600', '0', '0', '1', '2017-03-02 09:49:39', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1760', '1451545200', '1451595600', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1761', '1452150000', '1452200400', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1762', '1452754800', '1452805200', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1763', '1453359600', '1453410000', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1764', '1453964400', '1454014800', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1765', '1454569200', '1454619600', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1766', '1455174000', '1455224400', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1767', '1455778800', '1455829200', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1768', '1456383600', '1456434000', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1769', '1456988400', '1457038800', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1770', '1457593200', '1457643600', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1771', '1458198000', '1458248400', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1772', '1458802800', '1458853200', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1773', '1459404000', '1459454400', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1774', '1460008800', '1460059200', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1775', '1460613600', '1460664000', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1776', '1461218400', '1461268800', '0', '0', '2', '2017-03-02 09:49:40', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1777', '1461823200', '1461873600', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1778', '1462428000', '1462478400', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1779', '1463032800', '1463083200', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1780', '1463637600', '1463688000', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1781', '1464242400', '1464292800', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1782', '1464847200', '1464897600', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1783', '1465452000', '1465502400', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1784', '1466056800', '1466107200', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1785', '1466661600', '1466712000', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1786', '1467266400', '1467316800', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1787', '1467871200', '1467921600', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1788', '1468476000', '1468526400', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1789', '1469080800', '1469131200', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1790', '1469685600', '1469736000', '0', '0', '2', '2017-03-02 09:49:41', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1791', '1470290400', '1470340800', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1792', '1470895200', '1470945600', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1793', '1471500000', '1471550400', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1794', '1472104800', '1472155200', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1795', '1472709600', '1472760000', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1796', '1473314400', '1473364800', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1797', '1473919200', '1473969600', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1798', '1474524000', '1474574400', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1799', '1475128800', '1475179200', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1800', '1475733600', '1475784000', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1801', '1476338400', '1476388800', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1802', '1476943200', '1476993600', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1803', '1477548000', '1477598400', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1804', '1478156400', '1478206800', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1805', '1478761200', '1478811600', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1806', '1479366000', '1479416400', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1807', '1479970800', '1480021200', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1808', '1480575600', '1480626000', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1809', '1481180400', '1481230800', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1810', '1481785200', '1481835600', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1811', '1482390000', '1482440400', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1812', '1482994800', '1483045200', '0', '0', '2', '2017-03-02 09:49:42', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1813', '1483599600', '1483650000', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1814', '1484204400', '1484254800', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1815', '1484809200', '1484859600', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1816', '1485414000', '1485464400', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1817', '1486018800', '1486069200', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1818', '1486623600', '1486674000', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1819', '1487228400', '1487278800', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1820', '1487833200', '1487883600', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1821', '1488438000', '1488488400', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1822', '1489042800', '1489093200', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1823', '1489647600', '1489698000', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1824', '1490252400', '1490302800', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1825', '1490853600', '1490904000', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1826', '1491458400', '1491508800', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1827', '1492063200', '1492113600', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1828', '1492668000', '1492718400', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1829', '1493272800', '1493323200', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1830', '1493877600', '1493928000', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1831', '1494482400', '1494532800', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1832', '1495087200', '1495137600', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1833', '1495692000', '1495742400', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1834', '1496296800', '1496347200', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1835', '1496901600', '1496952000', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1836', '1497506400', '1497556800', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1837', '1498111200', '1498161600', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1838', '1498716000', '1498766400', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1839', '1499320800', '1499371200', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1840', '1499925600', '1499976000', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1841', '1500530400', '1500580800', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1842', '1501135200', '1501185600', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1843', '1501740000', '1501790400', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1844', '1502344800', '1502395200', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1845', '1502949600', '1503000000', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1846', '1503554400', '1503604800', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1847', '1504159200', '1504209600', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1848', '1504764000', '1504814400', '0', '0', '2', '2017-03-02 09:49:43', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1849', '1505368800', '1505419200', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1850', '1505973600', '1506024000', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1851', '1506578400', '1506628800', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1852', '1507183200', '1507233600', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1853', '1507788000', '1507838400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1854', '1508392800', '1508443200', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1855', '1508997600', '1509048000', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1856', '1509606000', '1509656400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1857', '1510210800', '1510261200', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1858', '1510815600', '1510866000', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1859', '1511420400', '1511470800', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1860', '1512025200', '1512075600', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1861', '1512630000', '1512680400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1862', '1513234800', '1513285200', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1863', '1513839600', '1513890000', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1864', '1514444400', '1514494800', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1865', '1515049200', '1515099600', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1866', '1515654000', '1515704400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1867', '1516258800', '1516309200', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1868', '1516863600', '1516914000', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1869', '1517468400', '1517518800', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1870', '1518073200', '1518123600', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1871', '1518678000', '1518728400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1872', '1519282800', '1519333200', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1873', '1519887600', '1519938000', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1874', '1520492400', '1520542800', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1875', '1521097200', '1521147600', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1876', '1521702000', '1521752400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1877', '1522303200', '1522353600', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1878', '1522908000', '1522958400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1879', '1523512800', '1523563200', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1880', '1524117600', '1524168000', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1881', '1524722400', '1524772800', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1882', '1525327200', '1525377600', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1883', '1525932000', '1525982400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1884', '1526536800', '1526587200', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1885', '1527141600', '1527192000', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1886', '1527746400', '1527796800', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1887', '1528351200', '1528401600', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1888', '1528956000', '1529006400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1889', '1529560800', '1529611200', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1890', '1530165600', '1530216000', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1891', '1530770400', '1530820800', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1892', '1531375200', '1531425600', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1893', '1531980000', '1532030400', '0', '0', '2', '2017-03-02 09:49:44', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1894', '1532584800', '1532635200', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1895', '1533189600', '1533240000', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1896', '1533794400', '1533844800', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1897', '1534399200', '1534449600', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1898', '1535004000', '1535054400', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1899', '1535608800', '1535659200', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1900', '1536213600', '1536264000', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1901', '1536818400', '1536868800', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1902', '1537423200', '1537473600', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1903', '1538028000', '1538078400', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1904', '1538632800', '1538683200', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1905', '1539237600', '1539288000', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1906', '1539842400', '1539892800', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1907', '1540447200', '1540497600', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1908', '1541055600', '1541106000', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1909', '1541660400', '1541710800', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1910', '1542265200', '1542315600', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1911', '1542870000', '1542920400', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1912', '1543474800', '1543525200', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1913', '1544079600', '1544130000', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1914', '1544684400', '1544734800', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1915', '1545289200', '1545339600', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1916', '1545894000', '1545944400', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1917', '1546498800', '1546549200', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1918', '1547103600', '1547154000', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1919', '1547708400', '1547758800', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1920', '1548313200', '1548363600', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1921', '1548918000', '1548968400', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1922', '1549522800', '1549573200', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1923', '1550127600', '1550178000', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1924', '1550732400', '1550782800', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1925', '1551337200', '1551387600', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1926', '1551942000', '1551992400', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1927', '1552546800', '1552597200', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1928', '1553151600', '1553202000', '0', '0', '2', '2017-03-02 09:49:45', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1929', '1553756400', '1553806800', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1930', '1554357600', '1554408000', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1931', '1554962400', '1555012800', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1932', '1555567200', '1555617600', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1933', '1556172000', '1556222400', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1934', '1556776800', '1556827200', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1935', '1557381600', '1557432000', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1936', '1557986400', '1558036800', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1937', '1558591200', '1558641600', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1938', '1559196000', '1559246400', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1939', '1559800800', '1559851200', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1940', '1560405600', '1560456000', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1941', '1561010400', '1561060800', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1942', '1561615200', '1561665600', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1943', '1562220000', '1562270400', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1944', '1562824800', '1562875200', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1945', '1563429600', '1563480000', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1946', '1564034400', '1564084800', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1947', '1564639200', '1564689600', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1948', '1565244000', '1565294400', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1949', '1565848800', '1565899200', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1950', '1566453600', '1566504000', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1951', '1567058400', '1567108800', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1952', '1567663200', '1567713600', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1953', '1568268000', '1568318400', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1954', '1568872800', '1568923200', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1955', '1569477600', '1569528000', '0', '0', '2', '2017-03-02 09:49:46', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1956', '1570082400', '1570132800', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1957', '1570687200', '1570737600', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1958', '1571292000', '1571342400', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1959', '1571896800', '1571947200', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1960', '1572505200', '1572555600', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1961', '1573110000', '1573160400', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1962', '1573714800', '1573765200', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1963', '1574319600', '1574370000', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1964', '1574924400', '1574974800', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1965', '1575529200', '1575579600', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1966', '1576134000', '1576184400', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1967', '1576738800', '1576789200', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1968', '1577343600', '1577394000', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1969', '1577948400', '1577998800', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1970', '1578553200', '1578603600', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1971', '1579158000', '1579208400', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1972', '1579762800', '1579813200', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1973', '1580367600', '1580418000', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1974', '1580972400', '1581022800', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1975', '1581577200', '1581627600', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1976', '1582182000', '1582232400', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1977', '1582786800', '1582837200', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1978', '1583391600', '1583442000', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1979', '1583996400', '1584046800', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1980', '1584601200', '1584651600', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1981', '1585206000', '1585256400', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1982', '1585807200', '1585857600', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1983', '1586412000', '1586462400', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1984', '1587016800', '1587067200', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1985', '1587621600', '1587672000', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1986', '1588226400', '1588276800', '0', '0', '2', '2017-03-02 09:49:47', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1987', '1588831200', '1588881600', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1988', '1589436000', '1589486400', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1989', '1590040800', '1590091200', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1990', '1590645600', '1590696000', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1991', '1591250400', '1591300800', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1992', '1591855200', '1591905600', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1993', '1592460000', '1592510400', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1994', '1593064800', '1593115200', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1995', '1593669600', '1593720000', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1996', '1594274400', '1594324800', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1997', '1594879200', '1594929600', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1998', '1595484000', '1595534400', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('1999', '1596088800', '1596139200', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2000', '1596693600', '1596744000', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2001', '1597298400', '1597348800', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2002', '1597903200', '1597953600', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2003', '1598508000', '1598558400', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2004', '1599112800', '1599163200', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2005', '1599717600', '1599768000', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2006', '1600322400', '1600372800', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2007', '1600927200', '1600977600', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2008', '1601532000', '1601582400', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2009', '1602136800', '1602187200', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2010', '1602741600', '1602792000', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2011', '1603346400', '1603396800', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2012', '1603954800', '1604005200', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2013', '1604559600', '1604610000', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2014', '1605164400', '1605214800', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2015', '1605769200', '1605819600', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2016', '1606374000', '1606424400', '0', '0', '2', '2017-03-02 09:49:48', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2017', '1606978800', '1607029200', '0', '0', '2', '2017-03-02 09:49:49', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2018', '1607583600', '1607634000', '0', '0', '2', '2017-03-02 09:49:49', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2019', '1608188400', '1608238800', '0', '0', '2', '2017-03-02 09:49:49', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2020', '1608793200', '1608843600', '0', '0', '2', '2017-03-02 09:49:49', 'ADMIN', 'RENCONTRE EQUIPE 2', 'G', '', '-', '0', '');
INSERT INTO grr_entry  values ('2031', '1494061200', '1494064800', '1', '7', '1', '2017-03-02 11:29:22', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2032', '1494666000', '1494669600', '1', '7', '1', '2017-03-02 11:29:22', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2033', '1495270800', '1495274400', '1', '7', '1', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2034', '1495875600', '1495879200', '1', '7', '1', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2035', '1496480400', '1496484000', '1', '7', '1', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2036', '1497085200', '1497088800', '1', '7', '1', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2037', '1497690000', '1497693600', '1', '7', '1', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2038', '1498294800', '1498298400', '1', '7', '1', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2039', '1498899600', '1498903200', '1', '7', '1', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2049', '1494061200', '1494064800', '1', '8', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2050', '1494666000', '1494669600', '1', '8', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2051', '1495270800', '1495274400', '1', '8', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2052', '1495875600', '1495879200', '1', '8', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2053', '1496480400', '1496484000', '1', '8', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2054', '1497085200', '1497088800', '1', '8', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2055', '1497690000', '1497693600', '1', '8', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2056', '1498294800', '1498298400', '1', '8', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2057', '1498899600', '1498903200', '1', '8', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '-', '-1', '<1>TWFyaWU=</1>');
INSERT INTO grr_entry  values ('2076', '1488619800', '1488623400', '1', '11', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2077', '1489224600', '1489228200', '1', '11', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2078', '1489829400', '1489833000', '1', '11', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2079', '1490434200', '1490437800', '1', '11', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2080', '1491035400', '1491039000', '1', '11', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2081', '1491640200', '1491643800', '1', '11', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2082', '1492245000', '1492248600', '1', '11', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2083', '1492849800', '1492853400', '1', '11', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2084', '1493454600', '1493458200', '1', '11', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2085', '1488619800', '1488623400', '1', '12', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2086', '1489224600', '1489228200', '1', '12', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2087', '1489829400', '1489833000', '1', '12', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2088', '1490434200', '1490437800', '1', '12', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2089', '1491035400', '1491039000', '1', '12', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2090', '1491640200', '1491643800', '1', '12', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2091', '1492245000', '1492248600', '1', '12', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2092', '1492849800', '1492853400', '1', '12', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2093', '1493454600', '1493458200', '1', '12', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '-', '-1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_entry  values ('2096', '1488456000', '1488459600', '0', '0', '3', '2017-03-02 12:11:31', 'JOUEUR2', 'JOUEUR TENNIS2', 'A', 'JOUEUR TENNIS1', '-', '-1', '<1></1>');
INSERT INTO grr_entry  values ('2097', '1488524400', '1488528000', '0', '0', '1', '2017-03-03 02:02:38', 'JOUEUR1', 'JOUEUR TENNIS1', 'A', 'invite ', '-', '-1', '<1></1>');
INSERT INTO grr_entry  values ('2098', '1488906000', '1488909600', '0', '0', '2', '2017-03-07 17:14:49', 'JOUEUR2', 'JOUEUR TENNIS2', 'A', 'invite ', '-', '-1', '<1></1>');
INSERT INTO grr_entry  values ('2099', '1491055200', '1491080400', '0', '0', '2', '2017-04-01 14:13:35', 'JOUEUR1', 'JOUEUR TENNIS1', 'A', 'invite ', '-', '-1', '<1></1>');
#
# Structure de la table grr_group
#
DROP TABLE IF EXISTS `grr_group`;
CREATE TABLE `grr_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(30) NOT NULL DEFAULT '',
  `order_display` smallint(6) NOT NULL DEFAULT '0',
  `couleur` smallint(6) NOT NULL DEFAULT '0',
  `group_letter` char(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
#
# Données de grr_group
#
INSERT INTO grr_group  values ('1', 'Tennis', '1', '4', 'A');
INSERT INTO grr_group  values ('2', 'Badminton', '2', '8', 'B');
INSERT INTO grr_group  values ('3', 'Squash', '3', '14', 'C');
INSERT INTO grr_group  values ('4', 'Billard', '4', '1', 'D');
INSERT INTO grr_group  values ('5', 'Padel', '5', '15', 'E');
#
# Structure de la table grr_group_calendar
#
DROP TABLE IF EXISTS `grr_group_calendar`;
CREATE TABLE `grr_group_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `entry_type` int(11) NOT NULL DEFAULT '0',
  `repeat_id` int(11) NOT NULL DEFAULT '0',
  `room_id` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_by` varchar(25) NOT NULL DEFAULT '',
  `name` varchar(80) NOT NULL DEFAULT '',
  `group_id` int(10) NOT NULL,
  `description` text,
  `statut_entry` char(1) NOT NULL DEFAULT '-',
  `option_reservation` int(11) NOT NULL DEFAULT '0',
  `overload_desc` text,
  PRIMARY KEY (`id`),
  KEY `idxStartTime` (`start_time`),
  KEY `idxEndTime` (`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_group_calendar
#
#
# Structure de la table grr_group_repeat
#
DROP TABLE IF EXISTS `grr_group_repeat`;
CREATE TABLE `grr_group_repeat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `rep_type` int(11) NOT NULL DEFAULT '0',
  `end_date` int(11) NOT NULL DEFAULT '0',
  `rep_opt` varchar(32) NOT NULL DEFAULT '',
  `room_id` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_by` varchar(25) NOT NULL DEFAULT '',
  `name` varchar(80) NOT NULL DEFAULT '',
  `group_id` char(1) NOT NULL DEFAULT 'E',
  `description` text,
  `rep_num_weeks` tinyint(4) DEFAULT '0',
  `overload_desc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_group_repeat
#
#
# Structure de la table grr_j_mailuser_room
#
DROP TABLE IF EXISTS `grr_j_mailuser_room`;
CREATE TABLE `grr_j_mailuser_room` (
  `login` varchar(40) NOT NULL DEFAULT '',
  `id_room` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`,`id_room`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_j_mailuser_room
#
INSERT INTO grr_j_mailuser_room  values ('JOUEUR1', '1');
#
# Structure de la table grr_j_type_area
#
DROP TABLE IF EXISTS `grr_j_type_area`;
CREATE TABLE `grr_j_type_area` (
  `id_type` int(11) NOT NULL DEFAULT '0',
  `id_area` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_j_type_area
#
INSERT INTO grr_j_type_area  values ('2', '2');
INSERT INTO grr_j_type_area  values ('5', '2');
INSERT INTO grr_j_type_area  values ('6', '2');
INSERT INTO grr_j_type_area  values ('7', '2');
INSERT INTO grr_j_type_area  values ('8', '2');
INSERT INTO grr_j_type_area  values ('10', '1');
INSERT INTO grr_j_type_area  values ('2', '3');
INSERT INTO grr_j_type_area  values ('3', '3');
INSERT INTO grr_j_type_area  values ('4', '3');
INSERT INTO grr_j_type_area  values ('5', '3');
INSERT INTO grr_j_type_area  values ('6', '3');
INSERT INTO grr_j_type_area  values ('7', '3');
INSERT INTO grr_j_type_area  values ('8', '3');
INSERT INTO grr_j_type_area  values ('10', '3');
INSERT INTO grr_j_type_area  values ('11', '1');
INSERT INTO grr_j_type_area  values ('11', '2');
INSERT INTO grr_j_type_area  values ('9', '1');
#
# Structure de la table grr_j_useradmin_area
#
DROP TABLE IF EXISTS `grr_j_useradmin_area`;
CREATE TABLE `grr_j_useradmin_area` (
  `login` varchar(40) NOT NULL DEFAULT '',
  `id_area` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`,`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_j_useradmin_area
#
#
# Structure de la table grr_j_user_area
#
DROP TABLE IF EXISTS `grr_j_user_area`;
CREATE TABLE `grr_j_user_area` (
  `login` varchar(40) NOT NULL DEFAULT '',
  `id_area` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`,`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_j_user_area
#
#
# Structure de la table grr_j_user_room
#
DROP TABLE IF EXISTS `grr_j_user_room`;
CREATE TABLE `grr_j_user_room` (
  `login` varchar(40) NOT NULL DEFAULT '',
  `id_room` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`,`id_room`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_j_user_room
#
#
# Structure de la table grr_log
#
DROP TABLE IF EXISTS `grr_log`;
CREATE TABLE `grr_log` (
  `LOGIN` varchar(40) NOT NULL DEFAULT '',
  `START` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `SESSION_ID` varchar(64) NOT NULL DEFAULT '',
  `REMOTE_ADDR` varchar(16) NOT NULL DEFAULT '',
  `USER_AGENT` varchar(100) NOT NULL DEFAULT '',
  `REFERER` varchar(64) NOT NULL DEFAULT '',
  `AUTOCLOSE` enum('0','1') NOT NULL DEFAULT '0',
  `END` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`SESSION_ID`,`START`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Structure de la table grr_overload
#
DROP TABLE IF EXISTS `grr_overload`;
CREATE TABLE `grr_overload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_area` int(11) NOT NULL DEFAULT '0',
  `fieldname` varchar(25) NOT NULL DEFAULT '',
  `fieldtype` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
#
# Données de grr_overload
#
INSERT INTO grr_overload  values ('1', '1', 'Enseignant', 'text');
#
# Structure de la table grr_repeat
#
DROP TABLE IF EXISTS `grr_repeat`;
CREATE TABLE `grr_repeat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` int(11) NOT NULL DEFAULT '0',
  `end_time` int(11) NOT NULL DEFAULT '0',
  `rep_type` int(11) NOT NULL DEFAULT '0',
  `end_date` int(11) NOT NULL DEFAULT '0',
  `rep_opt` varchar(32) NOT NULL DEFAULT '',
  `room_id` int(11) NOT NULL DEFAULT '1',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_by` varchar(25) NOT NULL DEFAULT '',
  `name` varchar(80) NOT NULL DEFAULT '',
  `type` char(1) NOT NULL DEFAULT 'E',
  `description` text,
  `rep_num_weeks` tinyint(4) DEFAULT '0',
  `overload_desc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
#
# Données de grr_repeat
#
INSERT INTO grr_repeat  values ('2', '1422457200', '1422471600', '2', '1485615600', '0001000', '1', '2015-01-28 21:09:25', 'ADMIN', 'Entrainement Adultes', 'D', '', '1', '');
INSERT INTO grr_repeat  values ('3', '1427702400', '1427716800', '2', '1490860800', '0101010', '1', '2015-04-04 19:33:13', 'ADMIN', 'Ecole de tennis', 'B', '', '1', '');
INSERT INTO grr_repeat  values ('4', '1446145200', '1446152400', '2', '1477764000', '1010101', '1', '2015-10-31 14:14:58', 'ADMIN', 'Tournoi', 'H', '', '1', '');
INSERT INTO grr_repeat  values ('5', '1446145200', '1446152400', '2', '1477764000', '1010101', '2', '2015-10-31 14:15:15', 'ADMIN', 'Tournoi', 'H', '', '1', '');
INSERT INTO grr_repeat  values ('6', '1446145200', '1446152400', '2', '1477764000', '1010101', '3', '2015-10-31 14:15:33', 'ADMIN', 'Tournoi', 'H', '', '1', '');
INSERT INTO grr_repeat  values ('7', '1488621600', '1488625200', '2', '1499158800', '0000001', '1', '2017-03-02 11:29:22', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '1', '<1>TWFyaWU=</1>');
INSERT INTO grr_repeat  values ('8', '1488621600', '1488625200', '2', '1499158800', '0000001', '2', '2017-03-02 11:29:23', 'ADMIN', 'EDT NIVEAU ORANGE', 'S', '', '1', '<1>TWFyaWU=</1>');
INSERT INTO grr_repeat  values ('11', '1488619800', '1488623400', '2', '1493886600', '0000001', '1', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '1', '<1>U3TDqXBoYW5l</1>');
INSERT INTO grr_repeat  values ('12', '1488619800', '1488623400', '2', '1493886600', '0000001', '2', '2017-03-02 11:35:11', 'ADMIN', 'EDT NIVEAU ROUGE', 'B', '', '1', '<1>U3TDqXBoYW5l</1>');
#
# Structure de la table grr_room
#
DROP TABLE IF EXISTS `grr_room`;
CREATE TABLE `grr_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) NOT NULL DEFAULT '0',
  `room_name` varchar(60) NOT NULL DEFAULT '',
  `description` varchar(60) NOT NULL DEFAULT '',
  `capacity` int(11) NOT NULL DEFAULT '0',
  `max_booking` smallint(6) NOT NULL DEFAULT '-1',
  `max_booking_week` smallint(6) NOT NULL DEFAULT '-1',
  `statut_room` char(1) NOT NULL DEFAULT '1',
  `show_fic_room` char(1) NOT NULL DEFAULT 'n',
  `picture_room` varchar(50) NOT NULL DEFAULT '',
  `comment_room` text NOT NULL,
  `delais_max_resa_room` smallint(6) NOT NULL DEFAULT '-1',
  `delais_min_resa_room` smallint(6) NOT NULL DEFAULT '0',
  `allow_action_in_past` char(1) NOT NULL DEFAULT 'n',
  `dont_allow_modify` char(1) NOT NULL DEFAULT 'n',
  `order_display` smallint(6) NOT NULL DEFAULT '0',
  `delais_option_reservation` smallint(6) NOT NULL DEFAULT '0',
  `type_affichage_reser` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
#
# Données de grr_room
#
INSERT INTO grr_room  values ('1', '1', 'Court N°1', '', '2', '1', '-1', '1', 'y', 'img_1.jpg', '', '15', '0', 'n', 'n', '1', '0', '1');
INSERT INTO grr_room  values ('2', '1', 'Court N°2', '', '0', '1', '4', '1', 'y', 'img_2.jpg', '', '15', '0', 'n', 'n', '2', '0', '1');
INSERT INTO grr_room  values ('3', '1', 'Court N°3', 'Exterieur', '0', '1', '6', '1', 'y', 'img_3.jpg', '', '15', '0', 'n', 'n', '3', '0', '0');
INSERT INTO grr_room  values ('8', '2', 'Terrain Bad 1', '', '1', '-1', '-1', '1', 'y', '', '', '-1', '0', 'n', 'n', '0', '0', '0');
INSERT INTO grr_room  values ('9', '2', 'Terrain Bad 2', '', '2', '-1', '-1', '1', 'y', '', '', '-1', '0', 'n', 'n', '0', '0', '0');
INSERT INTO grr_room  values ('10', '6', 'Terrain Padel 1', '', '0', '1', '-1', '1', 'n', '', '', '-1', '0', 'n', 'n', '0', '0', '0');
INSERT INTO grr_room  values ('11', '6', 'Terrain Padel 2', '', '0', '1', '-1', '1', 'n', '', '', '-1', '0', 'n', 'n', '0', '0', '0');
INSERT INTO grr_room  values ('12', '3', 'Squash 1', '', '2', '-1', '-1', '1', 'n', '', '', '-1', '0', 'n', 'n', '1', '0', '0');
INSERT INTO grr_room  values ('13', '3', 'Squash 2', '', '2', '-1', '-1', '1', 'n', '', '', '-1', '0', 'n', 'n', '2', '0', '0');
INSERT INTO grr_room  values ('14', '8', 'Table N°1', '', '0', '-1', '-1', '1', 'n', '', '', '-1', '0', 'n', 'n', '0', '0', '0');
INSERT INTO grr_room  values ('15', '8', 'Table N°2', '', '0', '-1', '-1', '1', 'n', '', '', '-1', '0', 'n', 'n', '0', '0', '0');
INSERT INTO grr_room  values ('16', '1', 'Court N°4', '', '0', '1', '-1', '1', 'n', '', '', '-1', '0', 'n', 'n', '4', '0', '0');
#
# Structure de la table grr_setting
#
DROP TABLE IF EXISTS `grr_setting`;
CREATE TABLE `grr_setting` (
  `NAME` varchar(32) NOT NULL DEFAULT '',
  `VALUE` text NOT NULL,
  PRIMARY KEY (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_setting
#
INSERT INTO grr_setting  values ('area_list_format', 'select');
INSERT INTO grr_setting  values ('automatic_mail', 'no');
INSERT INTO grr_setting  values ('begin_bookings', '1451516400');
INSERT INTO grr_setting  values ('bookingdouble', '1');
INSERT INTO grr_setting  values ('company', 'Club de Démonstration');
INSERT INTO grr_setting  values ('compteurinvite', '10');
INSERT INTO grr_setting  values ('date_verify_reservation', '1492466400');
INSERT INTO grr_setting  values ('default_area', '1');
INSERT INTO grr_setting  values ('default_css', 'volcan');
INSERT INTO grr_setting  values ('default_language', 'fr');
INSERT INTO grr_setting  values ('default_room', '-2');
INSERT INTO grr_setting  values ('default_year', '2017');
INSERT INTO grr_setting  values ('disable_login', 'no');
INSERT INTO grr_setting  values ('end_bookings', '1609282800');
INSERT INTO grr_setting  values ('grr_url', 'http://clubtcr.teria.org/demo/');
INSERT INTO grr_setting  values ('infos', 'Bienvenue sur le site de démonstration de G.T.C<br> Attention de nombreux paramètres peuvent faire varier les possibilités de réservation<br> Pensez à regarder les réglages pour chaque ressource');
INSERT INTO grr_setting  values ('maxallressources', '1');
INSERT INTO grr_setting  values ('message_home_page', '<b>Code d’accès Utilisateur et Administrateur ci-dessous.</b><br>\r\nPour administrer le site, rdv dans la rubrique <b>administration</b> du bandeau supérieur une fois connecté en tant qu’admin :');
INSERT INTO grr_setting  values ('regle_de', '<p><b>Les fonctionnalit&eacute;s du syst&egrave;me :</b><br><menu><a href=\"#fon\"><u>Voir les fonctionnalit&eacute;s</u></a><br></menu><p><b>R&eacute;glement int&eacute;rieur</b><br><menu><a href=\"#reg\"><u>Lire le r&eacute;glement</u></a><br></menu><p><b>Se connecter / G&eacute;rer son compte</b><br><menu><a href=\"#reserv\"><u>Comment se connecter pour R&eacute;server ?</u></a><br><a href=\"#mot\"><u>Comment changer mon mot de passe ?</u></a><br></menu><b>Cr&eacute;er / Supprimer les R&eacute;servations</b><menu><a href=\"#create\"><u>Comment cr&eacute;er une r&eacute;servation?</u></a><br><a href=\"#email\"><u>Comment pr&eacute;venir l adversaire que j ai choisi?</u></a><br><a href=\"#delete\"><u>Comment supprimer une r&eacute;servation?</u></a><br> <a href=\"#multiple_users\"><u>Que se passe t il si plusieurs personnes enregistrent la m&ecirc;me ressource sur le m&ecirc;me cr&eacute;neau horaire ?</u></a><br></menu><b>Divers</b><br><menu><a href=\"#create_ext\"><u>Comment cr&eacute;er une r&eacute;servation avec une personne ext&eacute;rieure au club?</u></a><br><hr><br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <hr><p><a name=\"reserv\"><b>Comment se connecter pour R&eacute;server ?</b></a><menu>Pour r&eacute;server un court, vous devez cliquer sur <b>Se connecter</b>,<br> entrez votre <b>identifiant :</b> premiere lettre du pr&eacute;nom suivi du nom puis votre <b>mot de passe :</b> date de naissance au format AAAAMMJJ (ann&eacute;e mois jour sans espace) ce mot de passe peut &ecirc;tre chang&eacute; dans la rubrique g&eacute;rer mon compte.</menu><a href=\"#top\">Haut de page</a><hr><hr><p><a name=\"mot\"><b>Comment changer mon mot de passe ?</b></a><menu>Pour changer votre mot de passe, vous devez &ecirc;tre connect&eacute; et vous devez cliquer sur <b>G&eacute;rer mon compte</b>,<br> puis v&eacute;rifiez votre e-mail ou modifiez le. Puis cliquez sur <b>--Cliquez ici pour modifier votre mot de passe--</b> suivez les explications et validez en cliquant dans le bas de la fen&ecirc;tre sur <b>Envoyer</b></menu><a href=\"#top\">Haut de page</a><hr><p><a name=create><b>Comment cr&eacute;er une r&eacute;servation?</b></a><menu>Apr&egrave;s avoir choisi le <b>court de tennis :</b><br> N&deg; 1 (salle)<br>N&deg; 2 (ext&eacute;rieur)<br> N&deg; 3 (ext&eacute;rieur)<br> cliquez sur la balle de tennis &agrave; l heure d&eacute;sir&eacute;e, l &eacute;cran de r&eacute;servation s affiche.<br>Choisissez votre <b>Adversaire</b> dans la liste d&eacute;roulante des membres du T.C.<br> V&eacute;rifiez l int&eacute;gralit&eacute; de votre r&eacute;servation<br>Puis cliquez sur <b>Enregistrer</b><br></menu><a href=\"#top\">Haut de page</a><hr><p><a name=email><b>Comment pr&eacute;venir l adversaire que j ai choisi?</b></a><menu>Lors de votre r&eacute;servation un <b>email automatique</b> est envoy&eacute; &agrave; votre adversaire s il nous la fourni. </menu><a href=\"#top\">Haut de page</a><hr><p><a name=\"delete\"><b>Comment supprimer une r&eacute;servation?</b></a><menu>Pour pouvoir supprimer une r&eacute;servation, vous devez &ecirc;tre la personne qui a cr&eacute;&eacute; la r&eacute;servation<br>Vous devez vous identifier et s&eacute;lectionner la r&eacute;servation puis cliquez sur supprimer.</menu><p><a name=\"multiple_users\"><b>Que se passe t il si plusieurs personnesenregistrent la m&ecirc;me ressource sur le m&ecirc;me cr&eacute;neau horaire ?</b></a><menu> R&eacute;ponse rapide : la premi&egrave;re personne qui clique le bouton <b>Enregistrer</b> gagne.<br> </menu><p><a name=\"create_ext\"><b>Comment cr&eacute;er une r&eacute;servation avec une personne ext&eacute;rieure au club?</b></a><menu>Pour pouvoir r&eacute;server un court avec une personne de l ext&eacute;rieur, vous devez r&eacute;gler des \"ticket invit&eacute;\"<br>Lors de votre r&eacute;servation, choisissez l abonn&eacute; \"invit&eacute;\" dans la liste d&eacute;roulante et votre compteur \"ticket invit&eacute;\" sera d&eacute;cr&eacute;ment&eacute;.</menu><a href=\"#top\">Haut de page</a><hr><br><p><a name=\"reg\"><b>Le r&eacute;glement int&eacute;rieur du club</b></a><menu> Le but du pr&eacute;sent r&egrave;glement est de faciliter l utilisation des courts et faire appliquer certaines r&egrave;gles sportives de biens&eacute;ance. Si l auto discipline n est pas suffisante, les membres du bureau sont habilit&eacute;s &agrave; le faire respecter.<br> <b>Article 1 : Les abonn&eacute;s</b><br>TOUT JOUEUR DOIT ETRE ABONNE du TC........Sont consid&eacute;r&eacute;s comme abonn&eacute;s du club les personnes ayant r&eacute;gl&eacute; leur cotisation annuelle. L ann&eacute;e tennistique commence le 1er Octobre et se termine le 31 Septembre de l ann&eacute;e suivante.L abonn&eacute; ne doit pas pr&ecirc;ter la carte d entr&eacute;e perfor&eacute;e, ou r&eacute;server un cr&eacute;neau horaire pour une personne ext&eacute;rieure au club (famille, amis...), sous peine de voir son compte de r&eacute;servation d&eacute;sactiv&eacute; et sa carte retir&eacute;e. En cas de blessure, le club ne pourra &ecirc;tre tenu pour responsable si une personne ext&eacute;rieure joue sur ses installations sans y &ecirc;tre autoris&eacute;e.Tous les abonn&eacute;s ont les m&ecirc;mes droits et les m&ecirc;mes devoirs sur tous les courts. Ils sont responsables de l &eacute;tat dans lequel ils laissent le terrain apr&egrave;s y avoir jou&eacute; (ramassage des bouteille)<br><b>Article 2 : L &eacute;cole de tennis</b><br>Avant de d&eacute;poser leurs enfants au club, les parents doivent s assurer qu il y a bien un moniteur/responsable pour les accueillir. Les parents sont pri&eacute;s de reprendre leurs enfants &agrave; la fin des entrainements.<br><b>Article 3 : Les r&eacute;servations de court</b><br>Les r&eacute;servations se font sur le site : http://.......  ou au club avec le PC mis &agrave; votre disposition.Il est interdit de r&eacute;server deux heures cons&eacute;cutives avec la m&ecirc;me personne !La r&eacute;servation d un cr&eacute;neau sur un court, rend impossible une deuxi&egrave;me r&eacute;servation sur ce m&ecirc;me court. Lorsque l horaire du cr&eacute;neau r&eacute;serv&eacute; est d&eacute;pass&eacute;, vous pouvez &agrave; nouveau r&eacute;server pour une autre heure, non cons&eacute;cutive &agrave; la pr&eacute;c&eacute;dente ou un autre jour. Tout abonn&eacute; doit r&eacute;server un court avec un autre abonn&eacute; du club pour que sa r&eacute;servation soit valid&eacute;e.Un court peut &ecirc;tre utilis&eacute; pendant une heure, soit en simple soit en double, &agrave; condition que les joueurs occupant le court soient tous abonn&eacute;s du TC.Exceptionnellement un court peut &ecirc;tre r&eacute;serv&eacute; par un abonn&eacute; avec un, deux ou trois invit&eacute;s sous r&eacute;serve d avoir pr&eacute;venu le bureau par mail club@freefree.fr ou, lors des permanences, le mercredi 18-19h, samedi 10-12h pr&eacute;c&eacute;dant sa r&eacute;servation.Le tarif est de 3euros par invit&eacute;. ( ex : 1 abonn&eacute; + 3 invit&eacute;s = 9 euros l heure).<br><b>Article 4 : Les vols</b><br>Le club d&eacute;cline toute responsabilit&eacute; en cas de perte ou de vol sur les courts, dans le club-house et les espaces environnants.<br><b>Article 5 : Les priorit&eacute;s d acc&egrave;s</b><br>Des priorit&eacute;s sont instaur&eacute;es sur certains courts pour  l &eacute;cole de tennis, les matchs en comp&eacute;tition et les entrainements &eacute;quipes.<br><b>Article 6 : R&egrave;gles diverses</b><br>Quel que soit le temps la tenue de tennis est obligatoire. Sur les courts, les chaussures de tennis ne doivent pas avoir de semelles noires. Sur le court couvert il est demand&eacute; aux adh&eacute;rents de veiller &agrave; la fermeture des baies coulissantes et &agrave; l extinction des lumi&egrave;res. Les bicyclettes et motocyclettes doivent &ecirc;tre rang&eacute;es dans les emplacements pr&eacute;vus &agrave; cet effetBoites, bouteilles, papiers etc. doivent &ecirc;tre d&eacute;pos&eacute;s dans les poubelles pr&eacute;vues &agrave; cet effet ou rapport&eacute;es &agrave; domicile.Les diverses installations doivent &ecirc;tre respect&eacute;es.<br><b>Article 7 : Les sanctions</b><br>Tout manquement au pr&eacute;sent r&egrave;glement pourra donner lieu &agrave; des sanctions d&eacute;cid&eacute;es par les membres du bureau.Tout manquement aux r&egrave;gles de r&eacute;servation entraine sa nullit&eacute;.Toute personne &eacute;trang&egrave;re au T.C. surprise &agrave; jouer sur les installations devra s acquitter du r&egrave;glement de 10 euros pour toute heure jou&eacute;e. </menu><a href=\"#top\">Haut de page</a><hr><br><p><a name=\"fon\"><b>Les fonctionnalit&eacute;s du syst&egrave;me :</b></a><menu> Ce syst&egrave;me de r&eacute;servation permet :<br> - aux adh&eacute;rents de r&eacute;server leur cr&eacute;neau de tennis.<br> - aux responsables de g&eacute;rer compl&eacute;tement un club de tennis y compris la comptabilit&eacute;.<br> Voyons en d&eacute;tail les possibilit&eacute;s : <br><br><b> 1 - Pour l adh&eacute;rent :</b><br><br><b>R&eacute;server :</b><br>Il poss&egrave;de un identifiant et mot de passe fournis par les responsables du club.	<br>L adh&eacute;rent peut r&eacute;server une heure de tennis sur le court de son choix avec un membre du club (qui recevra un email de confirmation) ou un invit&eacute;.<br>S il choisit de r&eacute;server avec un invit&eacute;, il aura pris soin auparavant de r&eacute;gler des \"ticket invit&eacute;\".Le principe est simple les responsables saisissent le nombre d heures invit&eacute;s r&eacute;gl&eacute; par l abonn&eacute;.D&egrave;s que l abonn&eacute; r&eacute;serve avec un invit&eacute;, son compteur est d&eacute;cr&eacute;ment&eacute; automatiquement.<br><br><b> G&eacute;rer son compte :</b> <br>L adh&eacute;rent peut changer son mot de passe et renseigner son adresse mail, il peut changer l aspect visuel du site.<br><br><b> D&eacute;poser une annonce : </b><br>L adh&eacute;rent qui le souhaite peut consulter les annonces et d&eacute;poser sa propre annonce pour indiquer ses disponibilit&eacute;s.<br><br><b>2 - Pour les responsables :</b><br><br><b> G&eacute;rer les adh&eacute;rents :</b> <br>( identifiant, mot de passe, photo, nom, pr&eacute;nom, date de naissance, email, t&eacute;l, adrese, type d abonnement, compteur invit&eacute;...)Visualiser les statistiques de r&eacute;servation, comparer les abonnements sur plusieurs ann&eacute;es.Envoyer des emails avec pi&egrave;ces jointes par un simple clic aux adh&eacute;rents.<br><br><b> G&eacute;rer la comptabilit&eacute; du club:</b> <br>Gestion par ann&eacute;e sportive des abonn&eacute;s etdes frais de fonctionnement du club (salaires, charges etc...)<br>Edition de bilan comptable<br>Gestion de la tresorerie<br><br><b> G&eacute;rer l aspect du site :</b><br>Configurer l aspect du site (couleur, langue, menu)Configurer les courts.D&eacute;poser des annonces en page d accueil.<br><br><b> Effectuer/Retirer des r&eacute;servations :</b> <br>Les responsables peuvent effectuer des r&eacute;servations sur plusieurs heures pour des activit&eacute;s diff&eacute;rentes et param&eacute;trables(Ecole de tennis, Entra&icirc;nement, ...) avec des p&eacute;riodicit&eacute;s (Chaque jour, chaque semaine etc ...)Les responsables peuvent retirer des r&eacute;servations d adh&eacute;rents si n&eacute;cessaire.</menu><a href=\"#top\">Haut de page</a><hr><br><br><br><br><br><br>');
INSERT INTO grr_setting  values ('regle_en', '<p><b>Les fonctionnalit&eacute;s du syst&egrave;me :</b><br><menu><a href=\"#fon\"><u>Voir les fonctionnalit&eacute;s</u></a><br></menu><p><b>R&eacute;glement int&eacute;rieur</b><br><menu><a href=\"#reg\"><u>Lire le r&eacute;glement</u></a><br></menu><p><b>Se connecter / G&eacute;rer son compte</b><br><menu><a href=\"#reserv\"><u>Comment se connecter pour R&eacute;server ?</u></a><br><a href=\"#mot\"><u>Comment changer mon mot de passe ?</u></a><br></menu><b>Cr&eacute;er / Supprimer les R&eacute;servations</b><menu><a href=\"#create\"><u>Comment cr&eacute;er une r&eacute;servation?</u></a><br><a href=\"#email\"><u>Comment pr&eacute;venir l adversaire que j ai choisi?</u></a><br><a href=\"#delete\"><u>Comment supprimer une r&eacute;servation?</u></a><br> <a href=\"#multiple_users\"><u>Que se passe t il si plusieurs personnes enregistrent la m&ecirc;me ressource sur le m&ecirc;me cr&eacute;neau horaire ?</u></a><br></menu><b>Divers</b><br><menu><a href=\"#create_ext\"><u>Comment cr&eacute;er une r&eacute;servation avec une personne ext&eacute;rieure au club?</u></a><br><hr><br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <hr><p><a name=\"reserv\"><b>Comment se connecter pour R&eacute;server ?</b></a><menu>Pour r&eacute;server un court, vous devez cliquer sur <b>Se connecter</b>,<br> entrez votre <b>identifiant :</b> premiere lettre du pr&eacute;nom suivi du nom puis votre <b>mot de passe :</b> date de naissance au format AAAAMMJJ (ann&eacute;e mois jour sans espace) ce mot de passe peut &ecirc;tre chang&eacute; dans la rubrique g&eacute;rer mon compte.</menu><a href=\"#top\">Haut de page</a><hr><hr><p><a name=\"mot\"><b>Comment changer mon mot de passe ?</b></a><menu>Pour changer votre mot de passe, vous devez &ecirc;tre connect&eacute; et vous devez cliquer sur <b>G&eacute;rer mon compte</b>,<br> puis v&eacute;rifiez votre e-mail ou modifiez le. Puis cliquez sur <b>--Cliquez ici pour modifier votre mot de passe--</b> suivez les explications et validez en cliquant dans le bas de la fen&ecirc;tre sur <b>Envoyer</b></menu><a href=\"#top\">Haut de page</a><hr><p><a name=create><b>Comment cr&eacute;er une r&eacute;servation?</b></a><menu>Apr&egrave;s avoir choisi le <b>court de tennis :</b><br> N&deg; 1 (salle)<br>N&deg; 2 (ext&eacute;rieur)<br> N&deg; 3 (ext&eacute;rieur)<br> cliquez sur la balle de tennis &agrave; l heure d&eacute;sir&eacute;e, l &eacute;cran de r&eacute;servation s affiche.<br>Choisissez votre <b>Adversaire</b> dans la liste d&eacute;roulante des membres du T.C.<br> V&eacute;rifiez l int&eacute;gralit&eacute; de votre r&eacute;servation<br>Puis cliquez sur <b>Enregistrer</b><br></menu><a href=\"#top\">Haut de page</a><hr><p><a name=email><b>Comment pr&eacute;venir l adversaire que j ai choisi?</b></a><menu>Lors de votre r&eacute;servation un <b>email automatique</b> est envoy&eacute; &agrave; votre adversaire s il nous la fourni. </menu><a href=\"#top\">Haut de page</a><hr><p><a name=\"delete\"><b>Comment supprimer une r&eacute;servation?</b></a><menu>Pour pouvoir supprimer une r&eacute;servation, vous devez &ecirc;tre la personne qui a cr&eacute;&eacute; la r&eacute;servation<br>Vous devez vous identifier et s&eacute;lectionner la r&eacute;servation puis cliquez sur supprimer.</menu><p><a name=\"multiple_users\"><b>Que se passe t il si plusieurs personnesenregistrent la m&ecirc;me ressource sur le m&ecirc;me cr&eacute;neau horaire ?</b></a><menu> R&eacute;ponse rapide : la premi&egrave;re personne qui clique le bouton <b>Enregistrer</b> gagne.<br> </menu><p><a name=\"create_ext\"><b>Comment cr&eacute;er une r&eacute;servation avec une personne ext&eacute;rieure au club?</b></a><menu>Pour pouvoir r&eacute;server un court avec une personne de l ext&eacute;rieur, vous devez r&eacute;gler des \"ticket invit&eacute;\"<br>Lors de votre r&eacute;servation, choisissez l abonn&eacute; \"invit&eacute;\" dans la liste d&eacute;roulante et votre compteur \"ticket invit&eacute;\" sera d&eacute;cr&eacute;ment&eacute;.</menu><a href=\"#top\">Haut de page</a><hr><br><p><a name=\"reg\"><b>Le r&eacute;glement int&eacute;rieur du club</b></a><menu> Le but du pr&eacute;sent r&egrave;glement est de faciliter l utilisation des courts et faire appliquer certaines r&egrave;gles sportives de biens&eacute;ance. Si l auto discipline n est pas suffisante, les membres du bureau sont habilit&eacute;s &agrave; le faire respecter.<br> <b>Article 1 : Les abonn&eacute;s</b><br>TOUT JOUEUR DOIT ETRE ABONNE du TC........Sont consid&eacute;r&eacute;s comme abonn&eacute;s du club les personnes ayant r&eacute;gl&eacute; leur cotisation annuelle. L ann&eacute;e tennistique commence le 1er Octobre et se termine le 31 Septembre de l ann&eacute;e suivante.L abonn&eacute; ne doit pas pr&ecirc;ter la carte d entr&eacute;e perfor&eacute;e, ou r&eacute;server un cr&eacute;neau horaire pour une personne ext&eacute;rieure au club (famille, amis...), sous peine de voir son compte de r&eacute;servation d&eacute;sactiv&eacute; et sa carte retir&eacute;e. En cas de blessure, le club ne pourra &ecirc;tre tenu pour responsable si une personne ext&eacute;rieure joue sur ses installations sans y &ecirc;tre autoris&eacute;e.Tous les abonn&eacute;s ont les m&ecirc;mes droits et les m&ecirc;mes devoirs sur tous les courts. Ils sont responsables de l &eacute;tat dans lequel ils laissent le terrain apr&egrave;s y avoir jou&eacute; (ramassage des bouteille)<br><b>Article 2 : L &eacute;cole de tennis</b><br>Avant de d&eacute;poser leurs enfants au club, les parents doivent s assurer qu il y a bien un moniteur/responsable pour les accueillir. Les parents sont pri&eacute;s de reprendre leurs enfants &agrave; la fin des entrainements.<br><b>Article 3 : Les r&eacute;servations de court</b><br>Les r&eacute;servations se font sur le site : http://.......  ou au club avec le PC mis &agrave; votre disposition.Il est interdit de r&eacute;server deux heures cons&eacute;cutives avec la m&ecirc;me personne !La r&eacute;servation d un cr&eacute;neau sur un court, rend impossible une deuxi&egrave;me r&eacute;servation sur ce m&ecirc;me court. Lorsque l horaire du cr&eacute;neau r&eacute;serv&eacute; est d&eacute;pass&eacute;, vous pouvez &agrave; nouveau r&eacute;server pour une autre heure, non cons&eacute;cutive &agrave; la pr&eacute;c&eacute;dente ou un autre jour. Tout abonn&eacute; doit r&eacute;server un court avec un autre abonn&eacute; du club pour que sa r&eacute;servation soit valid&eacute;e.Un court peut &ecirc;tre utilis&eacute; pendant une heure, soit en simple soit en double, &agrave; condition que les joueurs occupant le court soient tous abonn&eacute;s du TC.Exceptionnellement un court peut &ecirc;tre r&eacute;serv&eacute; par un abonn&eacute; avec un, deux ou trois invit&eacute;s sous r&eacute;serve d avoir pr&eacute;venu le bureau par mail club@freefree.fr ou, lors des permanences, le mercredi 18-19h, samedi 10-12h pr&eacute;c&eacute;dant sa r&eacute;servation.Le tarif est de 3euros par invit&eacute;. ( ex : 1 abonn&eacute; + 3 invit&eacute;s = 9 euros l heure).<br><b>Article 4 : Les vols</b><br>Le club d&eacute;cline toute responsabilit&eacute; en cas de perte ou de vol sur les courts, dans le club-house et les espaces environnants.<br><b>Article 5 : Les priorit&eacute;s d acc&egrave;s</b><br>Des priorit&eacute;s sont instaur&eacute;es sur certains courts pour  l &eacute;cole de tennis, les matchs en comp&eacute;tition et les entrainements &eacute;quipes.<br><b>Article 6 : R&egrave;gles diverses</b><br>Quel que soit le temps la tenue de tennis est obligatoire. Sur les courts, les chaussures de tennis ne doivent pas avoir de semelles noires. Sur le court couvert il est demand&eacute; aux adh&eacute;rents de veiller &agrave; la fermeture des baies coulissantes et &agrave; l extinction des lumi&egrave;res. Les bicyclettes et motocyclettes doivent &ecirc;tre rang&eacute;es dans les emplacements pr&eacute;vus &agrave; cet effetBoites, bouteilles, papiers etc. doivent &ecirc;tre d&eacute;pos&eacute;s dans les poubelles pr&eacute;vues &agrave; cet effet ou rapport&eacute;es &agrave; domicile.Les diverses installations doivent &ecirc;tre respect&eacute;es.<br><b>Article 7 : Les sanctions</b><br>Tout manquement au pr&eacute;sent r&egrave;glement pourra donner lieu &agrave; des sanctions d&eacute;cid&eacute;es par les membres du bureau.Tout manquement aux r&egrave;gles de r&eacute;servation entraine sa nullit&eacute;.Toute personne &eacute;trang&egrave;re au T.C. surprise &agrave; jouer sur les installations devra s acquitter du r&egrave;glement de 10 euros pour toute heure jou&eacute;e. </menu><a href=\"#top\">Haut de page</a><hr><br><p><a name=\"fon\"><b>Les fonctionnalit&eacute;s du syst&egrave;me :</b></a><menu> Ce syst&egrave;me de r&eacute;servation permet :<br> - aux adh&eacute;rents de r&eacute;server leur cr&eacute;neau de tennis.<br> - aux responsables de g&eacute;rer compl&eacute;tement un club de tennis y compris la comptabilit&eacute;.<br> Voyons en d&eacute;tail les possibilit&eacute;s : <br><br><b> 1 - Pour l adh&eacute;rent :</b><br><br><b>R&eacute;server :</b><br>Il poss&egrave;de un identifiant et mot de passe fournis par les responsables du club.	<br>L adh&eacute;rent peut r&eacute;server une heure de tennis sur le court de son choix avec un membre du club (qui recevra un email de confirmation) ou un invit&eacute;.<br>S il choisit de r&eacute;server avec un invit&eacute;, il aura pris soin auparavant de r&eacute;gler des \"ticket invit&eacute;\".Le principe est simple les responsables saisissent le nombre d heures invit&eacute;s r&eacute;gl&eacute; par l abonn&eacute;.D&egrave;s que l abonn&eacute; r&eacute;serve avec un invit&eacute;, son compteur est d&eacute;cr&eacute;ment&eacute; automatiquement.<br><br><b> G&eacute;rer son compte :</b> <br>L adh&eacute;rent peut changer son mot de passe et renseigner son adresse mail, il peut changer l aspect visuel du site.<br><br><b> D&eacute;poser une annonce : </b><br>L adh&eacute;rent qui le souhaite peut consulter les annonces et d&eacute;poser sa propre annonce pour indiquer ses disponibilit&eacute;s.<br><br><b>2 - Pour les responsables :</b><br><br><b> G&eacute;rer les adh&eacute;rents :</b> <br>( identifiant, mot de passe, photo, nom, pr&eacute;nom, date de naissance, email, t&eacute;l, adrese, type d abonnement, compteur invit&eacute;...)Visualiser les statistiques de r&eacute;servation, comparer les abonnements sur plusieurs ann&eacute;es.Envoyer des emails avec pi&egrave;ces jointes par un simple clic aux adh&eacute;rents.<br><br><b> G&eacute;rer la comptabilit&eacute; du club:</b> <br>Gestion par ann&eacute;e sportive des abonn&eacute;s etdes frais de fonctionnement du club (salaires, charges etc...)<br>Edition de bilan comptable<br>Gestion de la tresorerie<br><br><b> G&eacute;rer l aspect du site :</b><br>Configurer l aspect du site (couleur, langue, menu)Configurer les courts.D&eacute;poser des annonces en page d accueil.<br><br><b> Effectuer/Retirer des r&eacute;servations :</b> <br>Les responsables peuvent effectuer des r&eacute;servations sur plusieurs heures pour des activit&eacute;s diff&eacute;rentes et param&eacute;trables(Ecole de tennis, Entra&icirc;nement, ...) avec des p&eacute;riodicit&eacute;s (Chaque jour, chaque semaine etc ...)Les responsables peuvent retirer des r&eacute;servations d adh&eacute;rents si n&eacute;cessaire.</menu><a href=\"#top\">Haut de page</a><hr><br><br><br><br><br><br>');
INSERT INTO grr_setting  values ('regle_fr', '<p><b>Les fonctionnalités du système :</b><br><menu><a href=\"#fon\"><u>Voir les fonctionnalités</u></a><br></menu><p><b>Réglement intérieur</b><br><menu><a href=\"#reg\"><u>Lire le réglement</u></a><br></menu><p><b>Se connecter / Gérer son compte</b><br><menu><a href=\"#reserv\"><u>Comment se connecter pour Réserver ?</u></a><br><a href=\"#mot\"><u>Comment changer mon mot de passe ?</u></a><br></menu><b>Créer / Supprimer les Réservations</b><menu><a href=\"#create\"><u>Comment créer une réservation?</u></a><br><a href=\"#email\"><u>Comment prévenir l adversaire que j ai choisi?</u></a><br><a href=\"#delete\"><u>Comment supprimer une réservation?</u></a><br> <a href=\"#multiple_users\"><u>Que se passe t il si plusieurs personnes enregistrent la même ressource sur le même créneau horaire ?</u></a><br></menu><b>Divers</b><br><menu><a href=\"#create_ext\"><u>Comment créer une réservation avec une personne extérieure au club?</u></a><br><hr><br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <hr><p><a name=\"reserv\"><b>Comment se connecter pour Réserver ?</b></a><menu>Pour réserver un court, vous devez cliquer sur <b>Se connecter</b>,<br> entrez votre <b>identifiant :</b> premiere lettre du prénom suivi du nom puis votre <b>mot de passe :</b> date de naissance au format AAAAMMJJ (année mois jour sans espace) ce mot de passe peut être changé dans la rubrique gérer mon compte.</menu><a href=\"#top\">Haut de page</a><hr><hr><p><a name=\"mot\"><b>Comment changer mon mot de passe ?</b></a><menu>Pour changer votre mot de passe, vous devez être connecté et vous devez cliquer sur <b>Gérer mon compte</b>,<br> puis vérifiez votre e-mail ou modifiez le. Puis cliquez sur <b>--Cliquez ici pour modifier votre mot de passe--</b> suivez les explications et validez en cliquant dans le bas de la fenêtre sur <b>Envoyer</b></menu><a href=\"#top\">Haut de page</a><hr><p><a name=create><b>Comment créer une réservation?</b></a><menu>Après avoir choisi le <b>court de tennis :</b><br> N° 1 (salle)<br>N° 2 (extérieur)<br> N° 3 (extérieur)<br> cliquez sur la balle de tennis à l heure désirée, l écran de réservation s affiche.<br>Choisissez votre <b>Adversaire</b> dans la liste déroulante des membres du T.C.<br> Vérifiez l intégralité de votre réservation<br>Puis cliquez sur <b>Enregistrer</b><br></menu><a href=\"#top\">Haut de page</a><hr><p><a name=email><b>Comment prévenir l adversaire que j ai choisi?</b></a><menu>Lors de votre réservation un <b>email automatique</b> est envoyé à votre adversaire s il nous la fourni. </menu><a href=\"#top\">Haut de page</a><hr><p><a name=\"delete\"><b>Comment supprimer une réservation?</b></a><menu>Pour pouvoir supprimer une réservation, vous devez être la personne qui a créé la réservation<br>Vous devez vous identifier et sélectionner la réservation puis cliquez sur supprimer.</menu><p><a name=\"multiple_users\"><b>Que se passe t il si plusieurs personnesenregistrent la même ressource sur le même créneau horaire ?</b></a><menu> Réponse rapide : la première personne qui clique le bouton <b>Enregistrer</b> gagne.<br> </menu><p><a name=\"create_ext\"><b>Comment créer une réservation avec une personne extérieure au club?</b></a><menu>Pour pouvoir réserver un court avec une personne de l extérieur, vous devez régler des \"ticket invité\"<br>Lors de votre réservation, choisissez l abonné \"invité\" dans la liste déroulante et votre compteur \"ticket invité\" sera décrémenté.</menu><a href=\"#top\">Haut de page</a><hr><br><p><a name=\"reg\"><b>Le réglement intérieur du club</b></a><menu> Le but du présent règlement est de faciliter l utilisation des courts et faire appliquer certaines règles sportives de bienséance. Si l auto discipline n est pas suffisante, les membres du bureau sont habilités à le faire respecter.<br> <b>Article 1 : Les abonnés</b><br>TOUT JOUEUR DOIT ETRE ABONNE du TC........Sont considérés comme abonnés du club les personnes ayant réglé leur cotisation annuelle. L année tennistique commence le 1er Octobre et se termine le 31 Septembre de l année suivante.L abonné ne doit pas prêter la carte d entrée perforée, ou réserver un créneau horaire pour une personne extérieure au club (famille, amis...), sous peine de voir son compte de réservation désactivé et sa carte retirée. En cas de blessure, le club ne pourra être tenu pour responsable si une personne extérieure joue sur ses installations sans y être autorisée.Tous les abonnés ont les mêmes droits et les mêmes devoirs sur tous les courts. Ils sont responsables de l état dans lequel ils laissent le terrain après y avoir joué (ramassage des bouteille)<br><b>Article 2 : L école de tennis</b><br>Avant de déposer leurs enfants au club, les parents doivent s assurer qu il y a bien un moniteur/responsable pour les accueillir. Les parents sont priés de reprendre leurs enfants à la fin des entrainements.<br><b>Article 3 : Les réservations de court</b><br>Les réservations se font sur le site : http://.......  ou au club avec le PC mis à votre disposition.Il est interdit de réserver deux heures consécutives avec la même personne !La réservation d un créneau sur un court, rend impossible une deuxième réservation sur ce même court. Lorsque l horaire du créneau réservé est dépassé, vous pouvez à nouveau réserver pour une autre heure, non consécutive à la précédente ou un autre jour. Tout abonné doit réserver un court avec un autre abonné du club pour que sa réservation soit validée.Un court peut être utilisé pendant une heure, soit en simple soit en double, à condition que les joueurs occupant le court soient tous abonnés du TC.Exceptionnellement un court peut être réservé par un abonné avec un, deux ou trois invités sous réserve d avoir prévenu le bureau par mail club@freefree.fr ou, lors des permanences, le mercredi 18-19h, samedi 10-12h précédant sa réservation.Le tarif est de 3euros par invité. ( ex : 1 abonné + 3 invités = 9 euros l heure).<br><b>Article 4 : Les vols</b><br>Le club décline toute responsabilité en cas de perte ou de vol sur les courts, dans le club-house et les espaces environnants.<br><b>Article 5 : Les priorités d accès</b><br>Des priorités sont instaurées sur certains courts pour  l école de tennis, les matchs en compétition et les entrainements équipes.<br><b>Article 6 : Règles diverses</b><br>Quel que soit le temps la tenue de tennis est obligatoire. Sur les courts, les chaussures de tennis ne doivent pas avoir de semelles noires. Sur le court couvert il est demandé aux adhérents de veiller à la fermeture des baies coulissantes et à l extinction des lumières. Les bicyclettes et motocyclettes doivent être rangées dans les emplacements prévus à cet effetBoites, bouteilles, papiers etc. doivent être déposés dans les poubelles prévues à cet effet ou rapportées à domicile.Les diverses installations doivent être respectées.<br><b>Article 7 : Les sanctions</b><br>Tout manquement au présent règlement pourra donner lieu à des sanctions décidées par les membres du bureau.Tout manquement aux règles de réservation entraine sa nullité.Toute personne étrangère au T.C. surprise à jouer sur les installations devra s acquitter du règlement de 10 euros pour toute heure jouée. </menu><a href=\"#top\">Haut de page</a><hr><br><p><a name=\"fon\"><b>Les fonctionnalités du système :</b></a><menu> Ce système de réservation permet :<br> - aux adhérents de réserver leur créneau de tennis.<br> - aux responsables de gérer complétement un club de tennis y compris la comptabilité.<br> Voyons en détail les possibilités : <br><br><b> 1 - Pour l adhérent :</b><br><br><b>Réserver :</b><br>Il possède un identifiant et mot de passe fournis par les responsables du club.	<br>L adhérent peut réserver une heure de tennis sur le court de son choix avec un membre du club (qui recevra un email de confirmation) ou un invité.<br>S il choisit de réserver avec un invité, il aura pris soin auparavant de régler des \"ticket invité\".Le principe est simple les responsables saisissent le nombre d heures invités réglé par l abonné.Dès que l abonné réserve avec un invité, son compteur est décrémenté automatiquement.<br><br><b> Gérer son compte :</b> <br>L adhérent peut changer son mot de passe et renseigner son adresse mail, il peut changer l aspect visuel du site.<br><br><b> Déposer une annonce : </b><br>L adhérent qui le souhaite peut consulter les annonces et déposer sa propre annonce pour indiquer ses disponibilités.<br><br><b>2 - Pour les responsables :</b><br><br><b> Gérer les adhérents :</b> <br>( identifiant, mot de passe, photo, nom, prénom, date de naissance, email, tél, adrese, type d abonnement, compteur invité...)Visualiser les statistiques de réservation, comparer les abonnements sur plusieurs années.Envoyer des emails avec pièces jointes par un simple clic aux adhérents.<br><br><b> Gérer la comptabilité du club:</b> <br>Gestion par année sportive des abonnés etdes frais de fonctionnement du club (salaires, charges etc...)<br>Edition de bilan comptable<br>Gestion de la tresorerie<br><br><b> Gérer l aspect du site :</b><br>Configurer l aspect du site (couleur, langue, menu)Configurer les courts.Déposer des annonces en page d accueil.<br><br><b> Effectuer/Retirer des réservations :</b> <br>Les responsables peuvent effectuer des réservations sur plusieurs heures pour des activités différentes et paramétrables(Ecole de tennis, Entraînement, ...) avec des périodicités (Chaque jour, chaque semaine etc ...)Les responsables peuvent retirer des réservations d adhérents si nécessaire.</menu><a href=\"#top\">Haut de page</a><hr><br><br><br><br><br><br>');
INSERT INTO grr_setting  values ('regle_it', '<p><strong>Le caratteristiche del sistema:</strong></p><menu><a href=\"#fon\">Visualizza le caratteristiche</a></menu><p><strong>regolamento interno</strong></p><menu><a href=\"#reg\">Leggi il regolamento</a></menu><p><strong>Login / Gestisci il tuo account</strong></p><menu><a href=\"#reserv\">Come connettersi a prenotare?</a><br /><a href=\"#mot\">Come cambiare la mia password?</a></menu><p><strong>Crea / Cancella prenotazioni</strong></p><menu><a href=\"#create\">Come prenotare?</a><br /><a href=\"#email\">Come prevenire l avversario che ho scelto?</a><br /><a href=\"#delete\">Come faccio a cancellare una prenotazione?</a><br /> <a href=\"#multiple_users\">Cosa succede se più persone pianificare la stessa risorsa nello stesso intervallo di tempo?</a></menu><p><strong>Vario</strong></p><menu><a href=\"#create_ext\">Come fare una prenotazione con qualcuno al di fuori del club?</a><br /><hr /><br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /> <br /><hr /><p><a name=\"reserv\"></a><strong>Come connettersi a prenotare? ?</strong></p><menu>Per prenotare un campo, è necessario fare clic su Connetti,<br />Inserisci il tuo nome utente: la prima lettera del nome seguito dal nome e la password: la data di nascita in formato AAAAMMGG (anno mese giorno senza spazi) password può essere modificata nella sezione gestire il mio conto.</menu><p><a href=\"#top\">Inizio pagina</a></p><hr /><p><strong>Come cambiare la mia password? <br /></strong></p><menu>Per modificare la password, è necessario eseguire il login e si deve cliccare su Gestisci Il mio account<br />quindi controllare la posta elettronica o modificare. --click Clicca qui per cambiare la password passe-- seguire le spiegazioni e confermare facendo clic nella parte inferiore della finestra di invio</menu><p><a href=\"#top\">Inizio pagina</a></p><hr /><p><a name=\"create\"></a><strong>Come prenotare?</strong></p><menu>Dopo aver scelto il campo da tennis:<br />N ° 1 (camera)<br />N° 2 (esterno)<br />N° 3 (fuori)<br />Fare clic sulla palla da tennis per il tempo desiderato, viene visualizzata la schermata del libro s.<br />Scegli il tuo avversario dall elenco a discesa dei membri del T. C.<br />Controlla la tua intera prenotazione<br />Quindi fare clic su Salva</menu><p><a href=\"#top\">Inizio pagina</a></p><hr /><p><a name=\"email\"></a><strong>Come prevenire l avversario che ho scelto?</strong></p><menu>Per le prenotazioni di una e-mail automatica viene inviato al tuo avversario se egli ci ha fornito.</menu><p><a href=\"#top\">Inizio pagina</a></p><hr /><p><a name=\"delete\"></a><strong>Come faccio a cancellare una prenotazione?</strong></p><menu>Per cancellare una prenotazione, è necessario essere la persona che ha creato la prenotazione<br />Effettua il login e selezionare la prenotazione e scegliere Elimina.</menu><p><a name=\"multiple_users\"></a><strong>Cosa succede se più persone pianificare la stessa risorsa nello stesso intervallo di tempo?</strong></p><menu>Risposta rapida: la prima persona che scatta vince pulsante Salva.</menu><p><a name=\"create_ext\"></a><strong>Come fare una prenotazione con qualcuno al di fuori del club?</strong></p><menu>Per prenotare un campo con una persona da fuori, è necessario impostare il \"biglietto guest\"<br />Al momento della prenotazione, scegliere l abbonato \"ospite\" nel misuratore combo e il vostro \"biglietto guest\" sarà diminuito.</menu><p><a href=\"#top\">Inizio pagina</a></p><hr /><p> </p><p><a name=\"reg\"></a><strong>Leggi il regolamento</strong></p><menu>Lo scopo del presente regolamento è quello di facilitare l uso di breve e di attuare alcune etichetta sportiva. Se auto disciplina non è sufficiente, gli ufficiali hanno il potere di farla rispettare.<br /><strong>Articolo 1:</strong> abbonati<br />Tutti i giocatori devono ESSERE ABBONATO CT ........ sono considerati gli abbonati del club persone che hanno pagato la loro quota annuale. L anno di tennis inizia il 1 ° ottobre e si conclude il 31 settembre dell anno suivante.L abbonato non deve essere dell entrata carta perforata, o prenotare un intervallo di tempo per qualcuno al di fuori del club (famiglia, amici ...) in mancanza della quale il suo conto di prenotazione disabili e carta rimossa. In caso di infortunio, il club non può essere ritenuta responsabile se una persona esterna gioca sui suoi impianti, senza essere abbonati permission.All hanno gli stessi diritti e gli stessi obblighi a tutti i tribunali. Essi sono responsabili per la condizione in cui lasciano il campo dopo aver giocato (di scarto)<br /><strong>Articolo 2:</strong> La scuola di tennis<br />Prima di depositare i loro figli al club, i genitori devono garantire che s c è un buon istruttore / manager di riceverli. I genitori sono invitati a prendere i loro figli alla fine degli allenamenti.<br /><strong>Articolo 3:</strong> prenotazioni a breve<br />Le prenotazioni possono essere effettuate presso: http: // ....... o club con il PC a vostra disposition.Il devono prenotare due ore consecutive con la stessa persona La prenotazione di una nicchia in uno! insomma, rende impossibile una seconda prenotazione della stessa corte. Quando l intervallo di tempo riservato viene superato, è possibile prenotare ancora per un altra ora, non consecutivi alla precedente o un altro giorno. Ciascun abbonato deve prenotare un campo con un altro abbonato del club per il suo libro è breve validée.Un può essere utilizzato per un ora, sia singolo o doppio, a patto che gli attori coinvolti sono brevi tutti gli abbonati TC.Exceptionnellement un giudice può essere riservato da un abbonato con uno, due o tre ospiti oggetto di aver informato l ufficio via e-mail o club@freefree.fr durante la permanenza il Mercoledì 18-19h Sabato 10-12h prima della sua booking.The prezzo è di 3 euro per ogni ospite. (Ad esempio abbonato 1 + 3 = 9 euro invitato l ora).<br /><strong>Articolo 4:</strong> voli<br />Il club declina ogni responsabilità per perdite o furto sui campi del circolo e le zone circostanti.<br /><strong>Articolo 5:</strong> Le priorità di accesso<br />Le priorità sono stabilite in alcuni campi per la scuola di tennis, giochi competitivi e squadre di formazione.<br /><strong>Articolo 6:</strong> Altre regole<br />Qualunque sia il tempo di tennis abbigliamento è obbligatoria. Sui campi, le scarpe da tennis non dovrebbero avere suole nere. Sul campo coperto viene chiesto collezionisti per garantire la chiusura delle porte scorrevoli e le luci spente. Biciclette e motocicli devono essere conservati in slot dedicati effetBoites, bottiglie, carta, ecc devono essere depositate negli appositi contenitori previsti a tale scopo o segnalati domicile.Les diverse installazioni devono essere rispettati.<br /><strong>Articolo 7:</strong> Sanzioni<br />Qualsiasi violazione di tale regolamento può comportare le sanzioni decise dai membri del bureau.Tout regole di prenotazione violazione conduce il suo estraneo nullité.Toute per la sorpresa TC di giocare sugli impianti dovrebbe pagare s regolamento di 10 euro per ogni ora giocato.</menu><p><a href=\"#top\">Inizio pagina</a></p><hr /><p> </p><p><a name=\"fon\"></a><strong>Visualizza le caratteristiche :</strong></p><menu>Questo sistema di prenotazione permette di:<br />- Per i membri di prenotare la loro nicchia di tennis.<br />- I manager di gestire completamente un club di tennis tra cui contabilità.<br />Vediamo nel dettaglio le possibilità:<br /><br /><strong>1 - Per l utente:</strong><br /><br /><span style=\"text-decoration: underline;\">Prenotare :</span><br />Ha un nome utente e una password forniti dai funzionari del club.<br />Il socio può prenotare un ora di tennis sul campo di sua scelta con un membro del club (che riceverà una mail di conferma) o guest.<br />Se si sceglie di prenotare con un ospite, avrà preso cura di prima di risolvere \"biglietto chiamato\" .Il principio è leader semplici cogliere il numero di ore stabilite dal abonné.Dès ospiti che riserva abbonato con un ospite, il suo contatore viene decrementato automaticamente.<br /><br /><span style=\"text-decoration: underline;\">Gestisci il tuo account:</span><br />L utente può modificare la propria password e riempire la sua posta, si può cambiare l aspetto visivo del sito.<br /><br /><span style=\"text-decoration: underline;\">Pubblica un inserzione:</span><br />Il socio che vuole può visualizzare elenchi e presentare il proprio annuncio a indicare la sua disponibilità.<br /><br />2 <strong>- Per i dirigenti:</strong><br /><br /><span style=\"text-decoration: underline;\">Gestisci membri:</span><br />(Nome utente, password, foto, nome, data di nascita, e-mail, telefono, adrese, tipo di abbonamento, invitato metro ...) statistiche Visualizza prenotazione, confrontare abbonamenti su più e-mail con années.Envoyer allegati con un solo clic per i membri.<br /><br /><span style=\"text-decoration: underline;\">Gestire i conti del club:</span><br />Gestione per anno di sport abbonati eun costi di gestione del club (stipendi, spese, ecc ...)<br />bilancio Edition<br />Gestione di denaro contante<br /><br /><span style=\"text-decoration: underline;\">Gestire l aspetto del sito:</span><br />Configurare l aspetto del sito (colore, la lingua, menu) annunci Configurare courts.Déposer in Home page.<br /><br /><span style=\"text-decoration: underline;\">Eseguire / Rimuovi prenotazioni:</span><br />I manager possono effettuare prenotazioni con diverse ore e configurabile per le diverse attività (scuola di tennis, formazione, ...) con intervalli (giornaliero, settimanale, ecc ...) i gestori possono rimuovere i membri di prenotazione, se necessario .</menu><p><a href=\"#top\">Inizio pagina</a></p><hr /><p><br /><br /><br /><br /><br /><br /></p></menu>');
INSERT INTO grr_setting  values ('sessionMaxLength', '30');
INSERT INTO grr_setting  values ('technical_support_email', 'stephane.duchemin3@libertysurf.fr');
INSERT INTO grr_setting  values ('title_home_page', 'G.T.C 2.0');
INSERT INTO grr_setting  values ('url_disconnect', 'http://clubtcr.teria.org/demo/');
INSERT INTO grr_setting  values ('version', 'GTC 2.0');
INSERT INTO grr_setting  values ('versionRC', '');
INSERT INTO grr_setting  values ('webmaster_email', 'stephane.duchemin3@libertysurf.fr');
INSERT INTO grr_setting  values ('webmaster_name', 'Le bureau du Tennis Club');
#
# Structure de la table grr_type_area
#
DROP TABLE IF EXISTS `grr_type_area`;
CREATE TABLE `grr_type_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(30) NOT NULL DEFAULT '',
  `order_display` smallint(6) NOT NULL DEFAULT '0',
  `couleur` smallint(6) NOT NULL DEFAULT '0',
  `type_letter` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
#
# Données de grr_type_area
#
INSERT INTO grr_type_area  values ('1', 'Individuel', '1', '11', 'A');
INSERT INTO grr_type_area  values ('2', 'Ecole de tennis', '2', '4', 'B');
INSERT INTO grr_type_area  values ('3', 'Stage', '3', '3', 'C');
INSERT INTO grr_type_area  values ('4', 'Entrainement', '4', '8', 'D');
INSERT INTO grr_type_area  values ('5', 'Tournoi Interne', '5', '5', 'E');
INSERT INTO grr_type_area  values ('6', 'Championnat ind', '6', '23', 'F');
INSERT INTO grr_type_area  values ('7', 'Championnat equi', '7', '6', 'G');
INSERT INTO grr_type_area  values ('8', 'Tournoi Open', '8', '7', 'H');
INSERT INTO grr_type_area  values ('9', 'Menage', '9', '21', 'I');
INSERT INTO grr_type_area  values ('10', 'Ecole Badminton', '10', '7', 'J');
INSERT INTO grr_type_area  values ('11', 'Ecole Squash', '11', '26', 'K');
INSERT INTO grr_type_area  values ('12', 'EDT', '23', '12', 'S');
#
# Structure de la table grr_utilisateurs
#
DROP TABLE IF EXISTS `grr_utilisateurs`;
CREATE TABLE `grr_utilisateurs` (
  `login` varchar(40) NOT NULL DEFAULT '',
  `nom` varchar(30) NOT NULL DEFAULT '',
  `prenom` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `statut` varchar(30) NOT NULL DEFAULT '',
  `etat` varchar(20) NOT NULL DEFAULT '',
  `default_area` smallint(6) NOT NULL DEFAULT '0',
  `default_room` smallint(6) NOT NULL DEFAULT '0',
  `default_style` varchar(50) NOT NULL DEFAULT '',
  `default_list_type` varchar(50) NOT NULL DEFAULT '',
  `default_language` char(3) NOT NULL DEFAULT '',
  `source` varchar(10) NOT NULL DEFAULT 'local',
  `datenais` date NOT NULL,
  `tel` varchar(15) NOT NULL DEFAULT '',
  `telport` varchar(15) NOT NULL DEFAULT '',
  `abt` varchar(15) NOT NULL DEFAULT '---',
  `licence` varchar(6) NOT NULL DEFAULT '---',
  `classement` varchar(6) NOT NULL DEFAULT '---',
  `adresse` varchar(35) NOT NULL DEFAULT '',
  `code` varchar(6) NOT NULL DEFAULT '',
  `ville` varchar(25) NOT NULL DEFAULT '',
  `invite` int(10) NOT NULL DEFAULT '0',
  `champio` varchar(10) NOT NULL DEFAULT 'inactif',
  `group_id` int(10) NOT NULL DEFAULT '1',
  `badge` varchar(20) DEFAULT NULL,
  `solo` varchar(10) NOT NULL DEFAULT 'inactif',
  `inviteactif` varchar(10) NOT NULL DEFAULT 'inactif',
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
#
# Données de grr_utilisateurs
#
INSERT INTO grr_utilisateurs  values ('ADMIN', 'Admin', ' Club', '21232f297a57a5a743894a0e4a801fc3', '', 'administrateur', 'actif', '1', '-2', 'default', 'select', 'fr', 'local', '2009-09-09', '', '', '1', '', '', '', '', '', '0', 'inactif', '1', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('ADMINISTRATEUR', 'Tennis', 'Club', 'ab4f63f9ac65152575886860dde480a1', '', 'administrateur', 'actif', '1', '-1', 'forestier', 'list', 'fr', 'local', '0000-00-00', '', '', '', '---', '', '', '', '', '0', 'inactif', '1', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('championnat', 'championnat', 'individuel', 'b0cd7e999b9a0dfe958a8c5c94fd1267', '', 'visiteur', 'actif', '0', '0', '', '', '', 'local', '0000-00-00', '', '', '', '---', '', '', '', '', '0', 'inactif', '1', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('invite', 'invite', '', 'b0cd7e999b9a0dfe958a8c5c94fd1267', '', 'visiteur', 'actif', '0', '0', '', '', '', 'local', '0000-00-00', '', '', '', '---', '', '', '', '', '0', 'inactif', '1', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('JOUEUR1', 'JOUEUR', 'TENNIS1', '7334c140a65caf31d972689f2c951c5f', 'hsfkjhsqj@free.fr', 'utilisateur', 'actif', '1', '1', 'forestier', 'select', 'fr', 'local', '2009-09-09', '', '', '1', '', '', '', '', '', '8', 'inactif', '1', '53R546325W', 'inactif', 'actif');
INSERT INTO grr_utilisateurs  values ('JOUEUR2', 'JOUEUR', 'TENNIS2', '7334c140a65caf31d972689f2c951c5f', '', 'utilisateur', 'actif', '1', '1', 'forestier', 'select', 'fr', 'local', '2009-09-09', '', '', '1', '', '', '', '', '', '7', 'inactif', '1', '1254H21321H', 'inactif', 'actif');
INSERT INTO grr_utilisateurs  values ('JOUEUR3', 'JOUEUR', 'BADMINTON1', '7334c140a65caf31d972689f2c951c5f', '', 'utilisateur', 'actif', '1', '1', 'forestier', 'list', 'fr', 'local', '2009-09-09', '', '', '1', '', '', '', '', '', '0', 'inactif', '2', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('JOUEUR4', 'JOUEUR', 'BADMINTON2', '7334c140a65caf31d972689f2c951c5f', '', 'utilisateur', 'actif', '1', '1', 'forestier', 'list', 'fr', 'local', '2009-09-09', '', '', '1', '', '', '', '', '', '0', 'inactif', '2', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('JOUEUR5', 'JOUEUR', 'SQUASH1', '7334c140a65caf31d972689f2c951c5f', '', 'utilisateur', 'actif', '0', '0', '', '', '', 'local', '2009-09-09', '', '', '1', '', '', '', '', '', '0', 'inactif', '3', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('JOUEUR6', 'JOUEUR', 'SQUASH2', '7334c140a65caf31d972689f2c951c5f', '', 'utilisateur', 'actif', '1', '1', 'forestier', 'list', 'fr', 'local', '2009-09-09', '', '', '1', '', '', '', '', '', '0', 'inactif', '3', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('solo', 'solo', '', '', '', 'visiteur', 'actif', '0', '0', '', '', '', 'local', '0000-00-00', '', '', '---', '---', '---', '', '', '', '0', 'inactif', '1', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('supervision', 'supervision', '', '1b3231655cebb7a1f783eddf27d254ca', '', 'administrateur', 'actif', '0', '0', '', '', '', 'local', '0000-00-00', '', '', '', '---', '', '', '', '', '0', 'inactif', '1', '', 'inactif', 'inactif');
INSERT INTO grr_utilisateurs  values ('TEST.SECR', 'test', 'secrétariat', 'cff4423c1e5a7bc9cd16268113aac2cb', '', 'administrateur', 'actif', '1', '1', 'forestier', 'list', 'fr', 'local', '0000-00-00', '', '', '1', '', '', '', '', '', '0', 'inactif', '1', '', 'inactif', 'inactif');
#********* fin du fichier ***********