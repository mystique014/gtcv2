DROP TABLE IF EXISTS grr_abt;
CREATE TABLE IF NOT EXISTS grr_abt (id int(10) NOT NULL auto_increment, abt_name varchar(20) character set utf8 NOT NULL, order_display smallint(6) NOT NULL default '0', PRIMARY KEY  (`id`)) AUTO_INCREMENT=7 ;
INSERT INTO grr_abt (id, abt_name, order_display) VALUES (1, 'Simple', 1),(2, 'Ecole', 2),(3, 'Ecole 2h', 3),(4, 'Entrainement', 4),(5, 'Licence', 5);
DROP TABLE IF EXISTS grr_annonces;
CREATE TABLE grr_annonces ( id int(11) NOT NULL auto_increment, login varchar(40) NOT NULL, nom varchar(30) NOT NULL,  prenom varchar(30) NOT NULL, dispos text NOT NULL, PRIMARY KEY  (id));
DROP TABLE IF EXISTS grr_area;
CREATE TABLE grr_area (id int(11) NOT NULL auto_increment,area_name varchar(30) NOT NULL default '',access char(1) NOT NULL default '', order_display smallint(6) NOT NULL default '0', ip_adr varchar(15) NOT NULL default '', morningstarts_area smallint(6) NOT NULL default '0', minute_morningstarts_area smallint(6) NOT NULL default '0',eveningends_area smallint(6) NOT NULL default '0', resolution_area smallint(6) NOT NULL default '0', eveningends_minutes_area smallint(6) NOT NULL default '0', weekstarts_area smallint(6) NOT NULL default '0', twentyfourhour_format_area smallint(6) NOT NULL default '0', calendar_default_values char(1) NOT NULL default 'y', enable_periods char(1) NOT NULL default 'n',display_days varchar(7) NOT NULL default 'yyyyyyy', group_id smallint(2) NOT NULL default '0', PRIMARY KEY  (id))AUTO_INCREMENT=1 ;
INSERT INTO grr_area (id, area_name, access, order_display, ip_adr, morningstarts_area, minute_morningstarts_area,eveningends_area, resolution_area, eveningends_minutes_area, weekstarts_area, twentyfourhour_format_area, calendar_default_values, enable_periods, display_days, group_id) VALUES (1, 'Tennis', 'a', 0, '', 8, 0, 21, 3600, 0, 1, 1, 'n', 'n', 'yyyyyyy', '0');
DROP TABLE IF EXISTS grr_area_periodes;
CREATE TABLE grr_area_periodes (id_area int(11) NOT NULL default '0', num_periode smallint(6) NOT NULL default '0', nom_periode varchar(100) NOT NULL default '');
DROP TABLE IF EXISTS grr_calendar;
CREATE TABLE grr_calendar ( `DAY` int(11) NOT NULL default '0');
DROP TABLE IF EXISTS grr_categorie_compta;
CREATE TABLE grr_categorie_compta (id int(3) NOT NULL AUTO_INCREMENT,name varchar(30) NOT NULL, PRIMARY KEY (id));
DROP TABLE IF EXISTS grr_compta;
CREATE TABLE grr_compta (id int(11) NOT NULL AUTO_INCREMENT,login varchar(40) NOT NULL,statut varchar(15) NOT NULL, date date NOT NULL,description text NOT NULL,categorie int(3) NOT NULL,montant float NOT NULL,mode text NOT NULL,default_year int(4) NOT NULL,rap int(1) NOT NULL DEFAULT '0',timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (id));
DROP TABLE IF EXISTS grr_compte_tresorerie;
CREATE TABLE IF NOT EXISTS grr_compte_tresorerie (id int(11) NOT NULL AUTO_INCREMENT,name varchar(25) CHARACTER SET utf8 NOT NULL,solde float NOT NULL,courant int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`));
DROP TABLE IF EXISTS grr_entry;
CREATE TABLE grr_entry ( id int(11) NOT NULL auto_increment, start_time int(11) NOT NULL default '0', end_time int(11) NOT NULL default '0', entry_type int(11) NOT NULL default '0', repeat_id int(11) NOT NULL default '0', room_id int(11) NOT NULL default '1', `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP, create_by varchar(25) NOT NULL default '', `name` varchar(80) NOT NULL default '', `type` char(1) NOT NULL default 'A', description text, statut_entry char(1) NOT NULL default '-', option_reservation int(11) NOT NULL default '0', overload_desc text, PRIMARY KEY  (id),  KEY idxStartTime (start_time),  KEY idxEndTime (end_time));
DROP TABLE IF EXISTS grr_group;
CREATE TABLE IF NOT EXISTS grr_group (id int(10) NOT NULL auto_increment, group_name varchar(30) NOT NULL default '', order_display smallint(6) NOT NULL default '0', couleur smallint(6) NOT NULL default '0', group_letter char(2) NOT NULL, PRIMARY KEY  (id)) AUTO_INCREMENT=5 ;
INSERT INTO grr_group (id, group_name, order_display, couleur, group_letter) VALUES(1, 'Tennis', 1, 1, 'A'),(2, 'Foot', 2, 2, 'B'),(3, 'Badminton', 3, 3, 'C'),(4, 'Tennis de table', 4, 4, 'D');
DROP TABLE IF EXISTS grr_group_calendar;
CREATE TABLE IF NOT EXISTS grr_group_calendar ( id int(11) NOT NULL auto_increment, start_time int(11) NOT NULL default '0', end_time int(11) NOT NULL default '0', entry_type int(11) NOT NULL default '0', repeat_id int(11) NOT NULL default '0', room_id int(11) NOT NULL default '1', `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP, create_by varchar(25) NOT NULL default '', `name` varchar(80) NOT NULL default '', group_id int(10) NOT NULL, description text, statut_entry char(1) NOT NULL default '-', option_reservation int(11) NOT NULL default '0', overload_desc text, PRIMARY KEY  (id), KEY idxStartTime (start_time), KEY idxEndTime (end_time)) AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS grr_group_repeat;
CREATE TABLE IF NOT EXISTS grr_group_repeat ( id int(11) NOT NULL auto_increment, start_time int(11) NOT NULL default '0', end_time int(11) NOT NULL default '0', rep_type int(11) NOT NULL default '0', end_date int(11) NOT NULL default '0', rep_opt varchar(32) NOT NULL default '', room_id int(11) NOT NULL default '1', `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP, create_by varchar(25) NOT NULL default '', `name` varchar(80) NOT NULL default '', group_id char(1) NOT NULL default 'E', description text, rep_num_weeks tinyint(4) default '0', overload_desc text, PRIMARY KEY  (id)) AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS grr_j_mailuser_room;
CREATE TABLE grr_j_mailuser_room (login varchar(40) NOT NULL default '', id_room int(11) NOT NULL default '0', PRIMARY KEY  (login,id_room));
DROP TABLE IF EXISTS grr_j_type_area;
CREATE TABLE grr_j_type_area ( id_type int(11) NOT NULL default '0', id_area int(11) NOT NULL default '0');
DROP TABLE IF EXISTS grr_j_useradmin_area;
CREATE TABLE grr_j_useradmin_area ( login varchar(40) NOT NULL default '', id_area int(11) NOT NULL default '0', PRIMARY KEY  (login,id_area));
DROP TABLE IF EXISTS grr_j_user_area;
CREATE TABLE grr_j_user_area ( login varchar(40) NOT NULL default '', id_area int(11) NOT NULL default '0', PRIMARY KEY  (login,id_area));
DROP TABLE IF EXISTS grr_j_user_room;
CREATE TABLE grr_j_user_room ( login varchar(40) NOT NULL default '', id_room int(11) NOT NULL default '0', PRIMARY KEY  (login,id_room));
DROP TABLE IF EXISTS grr_log;
CREATE TABLE grr_log ( LOGIN varchar(40) NOT NULL default '', `START` datetime NOT NULL default '0000-00-00 00:00:00', SESSION_ID varchar(64) NOT NULL default '', REMOTE_ADDR varchar(16) NOT NULL default '', USER_AGENT varchar(100) NOT NULL default '',  REFERER varchar(64) NOT NULL default '', AUTOCLOSE enum('0','1') NOT NULL default '0', `END` datetime NOT NULL default '0000-00-00 00:00:00', PRIMARY KEY  (SESSION_ID,`START`));
DROP TABLE IF EXISTS grr_overload;
CREATE TABLE grr_overload ( id int(11) NOT NULL auto_increment, id_area int(11) NOT NULL default '0', fieldname varchar(25) NOT NULL default '', fieldtype varchar(25) NOT NULL default '', PRIMARY KEY  (id)) AUTO_INCREMENT=1 ;
DROP TABLE IF EXISTS grr_repeat;
CREATE TABLE grr_repeat ( id int(11) NOT NULL auto_increment, start_time int(11) NOT NULL default '0', end_time int(11) NOT NULL default '0', rep_type int(11) NOT NULL default '0',  end_date int(11) NOT NULL default '0', rep_opt varchar(32) NOT NULL default '',  room_id int(11) NOT NULL default '1', `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP, create_by varchar(25) NOT NULL default '', `name` varchar(80) NOT NULL default '', `type` char(1) NOT NULL default 'E', description text, rep_num_weeks tinyint(4) default '0', overload_desc text, PRIMARY KEY  (id));
DROP TABLE IF EXISTS grr_room;
CREATE TABLE grr_room ( id int(11) NOT NULL auto_increment, area_id int(11) NOT NULL default '0', room_name varchar(60) NOT NULL default '', description varchar(60) NOT NULL default '', capacity int(11) NOT NULL default '0', max_booking smallint(6) NOT NULL default '-1',max_booking_week smallint(6) NOT NULL default '-1', statut_room char(1) NOT NULL default '1',  show_fic_room char(1) NOT NULL default 'n', picture_room varchar(50) NOT NULL default '', comment_room text NOT NULL, delais_max_resa_room smallint(6) NOT NULL default '-1', delais_min_resa_room smallint(6) NOT NULL default '0', allow_action_in_past char(1) NOT NULL default 'n', dont_allow_modify char(1) NOT NULL default 'n', order_display smallint(6) NOT NULL default '0', delais_option_reservation smallint(6) NOT NULL default '0', type_affichage_reser smallint(6) NOT NULL default '0', PRIMARY KEY  (id)) AUTO_INCREMENT=4;
INSERT INTO grr_room (id, area_id, room_name, description, capacity, max_booking, max_booking_week, statut_room, show_fic_room, picture_room, comment_room, delais_max_resa_room, delais_min_resa_room, allow_action_in_past, dont_allow_modify, order_display, delais_option_reservation, type_affichage_reser) VALUES (1, 1, 'Court N1', 'Salle couverte', 1, 1, -1, '1', 'y', '', '', -1, 0, 'n', 'n', 0, 0, 0),(2, 1, 'Court N2', 'Ext�rieur', 1, 1, -1, '1', 'y', '', '', -1, 0, 'n', 'n', 0, 0, 0),(3, 1, 'Court N3', 'Exterieur', 1, 1, -1, '1', 'y', '', '', -1, 0, 'n', 'n', 0, 0, 0);
DROP TABLE IF EXISTS grr_setting;
CREATE TABLE grr_setting (NAME varchar(32) NOT NULL default '', VALUE text NOT NULL default '', PRIMARY KEY  (NAME));
DROP TABLE IF EXISTS grr_type_area;
CREATE TABLE grr_type_area ( id int(11) NOT NULL auto_increment, type_name varchar(30) NOT NULL default '', order_display smallint(6) NOT NULL default '0', couleur smallint(6) NOT NULL default '0',  type_letter char(2) NOT NULL default '', PRIMARY KEY  (id)) AUTO_INCREMENT=10 ;
DROP TABLE IF EXISTS grr_utilisateurs;
CREATE TABLE grr_utilisateurs ( login varchar(40) NOT NULL default '', nom varchar(30) NOT NULL default '', prenom varchar(30) NOT NULL default '', password varchar(32) NOT NULL default '', email varchar(100) NOT NULL default '', statut varchar(30) NOT NULL default '', etat varchar(20) NOT NULL default '', default_area smallint(6) NOT NULL default '0',default_room smallint(6) NOT NULL default '0', default_style varchar(50) NOT NULL default '', default_list_type varchar(50) NOT NULL default '', default_language char(3) NOT NULL default '', source varchar(10) NOT NULL default 'local', datenais date NOT NULL, tel varchar(15) NOT NULL default '', telport varchar(15) NOT NULL default '', abt varchar(15) NOT NULL default '', licence varchar(6) NOT NULL default '', classement varchar(6) NOT NULL default '',  adresse varchar(35) NOT NULL default '', code varchar(6) NOT NULL default '', ville varchar(25) NOT NULL default '', invite int(10) NOT NULL default '0', champio varchar(10) NOT NULL default 'inactif', group_id int(10) NOT NULL default '1', badge varchar(20) NOT NULL default '', solo varchar(10) NOT NULL default 'inactif', inviteactif varchar(10) NOT NULL default 'inactif', PRIMARY KEY  (login));
INSERT INTO grr_utilisateurs VALUES ('ADMINISTRATEUR', 'Tennis', 'Club', 'ab4f63f9ac65152575886860dde480a1', '', 'administrateur', 'actif', 1, 1, 'forestier', 'list', 'fr', 'local', '0000-00-00', '', '',  '', '---','', '', '', '', 0,'inactif',  0,'','inactif' ,'inactif');
INSERT INTO grr_utilisateurs VALUES ('invite', 'invite', '', 'b0cd7e999b9a0dfe958a8c5c94fd1267', '', 'visiteur', 'actif', 0, 0, '', '', '', 'local', '0000-00-00',  '', '', '', '---','', '', '', '', 0,'inactif',  0,'','inactif' ,'inactif');
INSERT INTO grr_utilisateurs VALUES ('supervision', 'supervision', '', '1b3231655cebb7a1f783eddf27d254ca', '', 'administrateur', 'actif', 0, 0, '', '', '', 'local', '0000-00-00',  '', '', '', '---','', '', '', '', 0, 'inactif', 0,'','inactif' ,'inactif');
INSERT INTO grr_utilisateurs VALUES ('championnat', 'championnat', 'individuel', 'b0cd7e999b9a0dfe958a8c5c94fd1267', '', 'visiteur', 'actif', 0, 0, '', '', '', 'local', '0000-00-00', '', '', '', '---', '', '', '', '', 0, 'inactif', 0,'','inactif' ,'inactif');
INSERT INTO grr_utilisateurs VALUES ('solo', 'solo', '', 'b0cd7e999b9a0dfe958a8c5c94fd1267', '', 'visiteur', 'actif', 0, 0, '', '', '', 'local', '0000-00-00', '', '', '', '---', '', '', '', '', 0, 'inactif', 0,'','inactif' ,'inactif');
INSERT INTO grr_setting VALUES ('sessionMaxLength', '15');
INSERT INTO grr_setting VALUES ('automatic_mail', 'yes');
INSERT INTO grr_setting VALUES ('company', 'Tennis Club ...');
INSERT INTO grr_setting VALUES ('webmaster_name', 'Le bureau du Tennis Club');
INSERT INTO grr_setting VALUES ('webmaster_email', 'admin@mon.site.fr');
INSERT INTO grr_setting VALUES ('technical_support_email', 'support.technique@mon.site.fr');
INSERT INTO grr_setting VALUES ('grr_url', 'http://mon.site.fr/gtc');
INSERT INTO grr_setting VALUES ('disable_login', 'no');
INSERT INTO grr_setting VALUES ('begin_bookings', '1199059200');
INSERT INTO grr_setting VALUES ('end_bookings', '1451433600');
INSERT INTO grr_setting VALUES ('title_home_page', 'Gestion Tennis Club');
INSERT INTO grr_setting VALUES ('message_home_page', 'En raison du caract&egrave;re personnel du contenu, ce site est soumis &agrave; des restrictions utilisateurs. Pour acc&eacute;der aux outils de r&eacute;servation, identifiez-vous :');
INSERT INTO grr_setting VALUES ('version', 'GTC 2.0');
INSERT INTO grr_setting VALUES ('versionRC', '');
INSERT INTO grr_setting VALUES ('default_language', 'fr');
INSERT INTO grr_setting VALUES ('url_disconnect', '');
INSERT INTO grr_setting VALUES ('date_verify_reservation', '1351296000');
INSERT INTO grr_setting VALUES ('infos', 'Pensez &agrave; r&eacute;gler tous les param&egrave;tres dans le menu administration');
INSERT INTO grr_setting VALUES ('default_css', 'default');
INSERT INTO grr_setting VALUES ('default_area', '-1');
INSERT INTO grr_setting VALUES ('default_room', '-1');
INSERT INTO grr_setting VALUES ('compteurinvite', '20');
INSERT INTO grr_setting VALUES ('maxallressources', '-1');
INSERT INTO grr_setting VALUES ('bookingdouble', '-1');
INSERT INTO grr_setting VALUES ('default_year', '2015');
INSERT INTO grr_type_area VALUES (1, 'Individuel', 1, 11, 'A');
INSERT INTO grr_type_area VALUES (2, 'Ecole de tennis', 2, 4, 'B');
INSERT INTO grr_type_area VALUES (3, 'Stage', 3, 3, 'C');
INSERT INTO grr_type_area VALUES (4, 'Entrainement', 4, 4, 'D');
INSERT INTO grr_type_area VALUES (5, 'Tournoi Interne', 5, 5, 'E');
INSERT INTO grr_type_area VALUES (6, 'Championnat ind', 6, 23, 'F');
INSERT INTO grr_type_area VALUES (7, 'Championnat equi', 7, 6, 'G');
INSERT INTO grr_type_area VALUES (8, 'Tournoi Open', 8, 10, 'H');
INSERT INTO grr_type_area VALUES (9, 'Menage', 9, 21, 'I');
