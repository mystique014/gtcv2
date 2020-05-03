<?php
# GRR : fichier de langue espagnole
# Last modification : 10/07/2007


# Charset (jeu de caract�res) utilis� dans l'en-t�te des pages HTML
$charset_html = "iso-8859-1";

$vocab = array();

//
//
$vocab["deux_points"] = "&nbsp;: ";

# Used in admin_config_sso.php // A traduire
$vocab["admin_config_sso.php"]     = "Configuration SSO";
$vocab["Ne_pas_activer_Service_sso"] = "Ne pas activer la prise en compte d'un SSO.";
$vocab["config_cas_title"]           = "Consideraci�n de un medio CAS (SSO)";
$vocab["CAS_SSO_explain"]            = "Si usted tiene a su disposici�n un contexto <b>CAS</b> (Central Authentification Service), usted puede activar la consideraci�n de este servicio por <B>GRR</B> a continuaci�n. Dir�jase a la documentaci�n de GRR para saber m�s.";
$vocab["Statut_par_defaut_utilisateurs_importes"] = "Estatus por defecto de los usuarios importados";
$vocab["choix_statut_CAS_SSO"]       = "Elija el estatuto que se asignar� a las personas cuando se conectan a GRR por primera vez utilizando el servicio CAS. Usted podr� m�s tarde modificar este valor por cada usuario";
$vocab["Url_de_deconnexion"]         = "Url de Desconexi�n ";
$vocab["Url_de_deconnexion_explain"] = "Cuando un usuario se desconecta, el navegador va de nuevo hacia la p�gina cuyo URL figura a continuaci�n.";
$vocab["Url_de_deconnexion_explain2"] = "Si le champ est vide, selon la valeur du param�tre \$authentification_obli (fichier config.inc.php),l'utilisateur est dirig� soit vers la page d'accueil soit vers la page de d�connexion."; // A traduire
$vocab["admin_config_lemon.php"]     = "Configuraci�n Lemonldap (SSO)";
$vocab["config_lemon_title"]         = "Consideraci�n de un medio Lemonldap (SSO)";
$vocab["lemon_SSO_explain"]          = "Si usted tiene a su disposici�n un contexto <b>Lemonldap</b>, Usted puede activar la consideraci�n de este servicio por <B>GRR</B> a continuaci�n. Dir�jase a la documentaci�n de GRR para mayor informaci�n.";
$vocab["choix_statut_lemon_SSO"]     = "Elija el estatuto que se asignar� a las personas cuando se conectan a GRR por primera vez utilizando al servicio Lemonldap. Usted podr� modificar m�s  adelante este valor para cada usuario";
$vocab["config_lcs_title"] = "Prise en compte d'un environnement SSO d'un serveur LCS";
$vocab["lcs_SSO_explain"] = "Si vous installez GRR sur un serveur <b>LCS</b>, vous pouvez activer ci-dessous la prise en compte du syst�me d'authentification par <B>GRR</B>. Reportez-vous � la documentation de GRR pour en savoir plus.";
$vocab["choix_statut_lcs_SSO"] = "Activez la prise en compte du service <b>SSO</b> de LCS et choisissez ci-dessous le statut qui sera attribu� aux personnes lorsqu'elles se connectent � GRR pour la premi�re fois en utilisant le service CAS. Vous pourrez par la suite modifier cette valeur pour chaque utilisateur.";
$vocab["active_lcs"] = "Activez la prise en compte du service SSO de LCS";
$vocab["statut_eleve"] = "Statut des �l�ves";
$vocab["statut_non_eleve"] = "Statut des autres utilisateurs (non �l�ve)";

# Used in admin_config_ldap.php
$vocab["admin_config_ldap.php"]      = "Configuraci�n LDAP";

# Used in login.php
$vocab["msg_login1"]                  = "<b>GRR</b> es una aplicaci�n PHP/MySql bajo licencia GPL, adaptada de <a href='http://mrbs.sourceforge.net'> MRBS</a>.<br>Para cualquier informaci�n relativa a <b>GRR</b>, ir a este sitio : ";
$vocab["msg_login3"]                  = "El sitio se halla momentaneamente inaccesible. �Le pedimos disculpas por este inconveniente!";
$vocab["autor_contact"]               = "Contactar al autor";
$vocab["pwd"]                         = "Contrase�a";
$vocab["identification"]              = "Identificaci�n";
$vocab["wrong_pwd"]                   = "Identificador o contrase�a incorrecta";
$vocab["grr_version"]                 = "Versi�n ";
$vocab["importation_impossible"]      = "La identificaci�n es correcta pero la importaci�n del perfil es imposible. Por favor se�ale este problema al administrador GRR";
$vocab["echec_connexion_GRR"]         = "Falla de conexi�n a GRR.";
$vocab["causes_possibles"]            = "Posibles causas :";
$vocab["echec_authentification_ldap"] = "Error en la autentificaci�n Idap ";
$vocab["authentification_CAS"]        = "Autentificarse en el Servicio Central de Autentificaci�n (CAS SSO)";
$vocab["authentification_lcs"] = "S'authentifier directement � votre espace perso LCS"; // A traduire

# Used in logout.php
$vocab["msg_logout1"] = "Usted cerr� su sesi�n GRR.";
$vocab["msg_logout2"] = "Su sesi�n GRR expir�, o fue desconectado,<br/>o no estaba conectado.";
$vocab["msg_logout3"] = "Abrir una sesi�n";

# Used in functions.inc
$vocab["welcome"]               = "Recepci�n";
$vocab["welcome_to"]            = "Bienvenida ";
$vocab["mrbs"]                  = "GRR (Gesti�n y Reserva de Recursos) ";
$vocab["report"]                = "Informe";
$vocab["admin"]                 = "Administraci�n";
$vocab["help"]                  = "Ayuda";
$vocab["not_php3"]              = "<H1> ATENCI�N : Esta aplicaci�n puede funcionar mal con PHP3</H1>";
$vocab["connect"]               = "Conectarse";
$vocab["disconnect"]            = "Desconectarse";
$vocab["manage_my_account"]     = "Administrar mi cuenta";
$vocab["technical_contact"]     = "Contactar el soporte t�cnico";
$vocab["administrator_contact"] = "Contactar el administrador";
$vocab["subject_mail1"]         = "GRR : opini�n ";
$vocab["subject_mail_creation"] = " - Nueva reservaci�n";
$vocab["subject_mail_modify"]   = " - Modificaci�n de una reservaci�n";
$vocab["subject_mail_delete"]   = " - Supresi�n de una reservaci�n";
$vocab["title_mail"]            = "Mensaje autom�tico emitido por el GRR : ";
$vocab["the_user"]              = "El usuario ";
$vocab["creation_booking"]      = " reserv� ";
$vocab["modify_booking"]        = " modific� la reservaci�n de ";
$vocab["delete_booking"]        = " suprimi� la reservaci�n de ";
$vocab["the_room"]              = "el recurso : ";
$vocab["start_of_the_booking"]  = "Inicio de la  reservaci�n : ";
$vocab["msg_no_email"]          = "Si usted no desea recibir estos mensajes autom�ticos, escriba al gestor de Grr : ";
$vocab["created_by"]            = " realizado por ";
$vocab["created_by_you"]        = " que realiz�.";
$vocab["title_mail_creation"]   = "Se registr� una reservac�on en GRR :\n";
$vocab["title_mail_modify"]     = "Se modific� una reservac�on en GRR :\n";
$vocab["title_mail_delete"]     = "Se suprimi� una reservac�on en GRR :\n";
$vocab["user_name"]             = "Apellido y nombre del usuario :";
$vocab["email"]                 = "Email del usuario :";
$vocab["tentative_reservation_ressource_indisponible"] = "�PROBLEMA : Usted intent� reservar un medio temporalmente indisponible!";
$vocab["suppression_automatique"] = "Le d�lai de confirmation de r�servation a �t� d�pass�.\nSuppression automatique de la r�servation de "; // A traduire

# Used in day.php
$vocab["bookingsfor"]       = "Reservaci�n para<br> ";
$vocab["bookingsforpost"]   = "";
$vocab["areas"]             = "�mbitos&nbsp;:&nbsp;";
$vocab["daybefore"]         = "Ir al d�a anterior";
$vocab["dayafter"]          = "Ir al d�a siguiente";
$vocab["gototoday"]         = "Hoy";
$vocab["goto"]              = " Indicar";
$vocab["number_max"]        = " persona max.";
$vocab["number_max2"]       = " personas max.";
$vocab["one_connected"]     = " persona conectada";
$vocab["several_connected"] = " personas conectadas";
$vocab["week"]              = "Semana";
$vocab["month"]             = "Mes";
$vocab["ressource_temporairement_indisponible"] = "Temporalmente indisponible";
$vocab["fiche_ressource"]   = "Ficha de presentaci�n del recurso";
$vocab["Configurer la ressource"] = "Configurar el recurso";
$vocab["cliquez_pour_effectuer_une_reservation"] = "Cliquez pour effectuer une r�servation"; // A traduire
$vocab["reservation_impossible"] = "R�servation impossible"; // A traduire

# Used in trailer.inc
$vocab["viewday"]   = "Ver el d�a";
$vocab["viewweek"]  = "Ver la semana";
$vocab["viewmonth"] = "Ver el mes";
$vocab["ppreview"]  = "Formato imprimible";

# Used in edit_entry.php
$vocab["addentry"]                          = "Adicionar una reservaci�n";
$vocab["editentry"]                         = "Modificar una  reservaci�n";
$vocab["editseries"]                        = "Modificar una periodicidad";
$vocab["namebooker"]                        = "Breve descripci�n :";
$vocab["fulldescription"]                   = "Descripci�n completa: (N�mero de personas, etc.)";
$vocab["date"]                              = "D�but de la r�servation"; // A traduire
$vocab["fin_reservation"]                   = "Fin de la r�servation"; // A traduire
$vocab["start_date"]                        = "Fecha de inicio :";
$vocab["end_date"]                          = "Fecha de fin :";
$vocab["time"]                              = "Hora&nbsp;:";
$vocab["duration"]                          = "Duraci�n :";
$vocab["seconds"]                           = "segundo(s)";
$vocab["minutes"]                           = "minuto(s)";
$vocab["hours"]                             = "hora(s)";
$vocab["days"]                              = "d�a(s)";
$vocab["weeks"]                             = "semana(s)";
$vocab["years"]                             = "a�o(s)";
$vocab["all_day"]                           = "Todo el d�a";
$vocab["type"]                              = "Tipo :";
$vocab["save"]                              = "Registrar";
$vocab["rep_type"]                          = "Tipo de periodicidad : ";
$vocab["rep_type_0"]                        = "Ninguna";
$vocab["rep_type_1"]                        = "Cada d�a";
$vocab["rep_type_2"]                        = "Cada semana";
$vocab["rep_type_3"]                        = "Cada mes, la misma fecha";
$vocab["rep_type_4"]                        = "Cada a�o, la misma fecha";
$vocab["rep_type_5"]                        = "Cada mes, el mismo d�a en la semana";
$vocab["rep_type_6"]                        = "todas las n semanas";
$vocab["rep_end_date"]                      = "Fecha de fin de la periodicidad :";
$vocab["rep_rep_day"]                       = "D�a :";
$vocab["rep_for_weekly"]                    = "(para una periodicidad semanal)";
$vocab["rep_freq"]                          = "Frecuencia :";
$vocab["rep_num_weeks"]                     = "Intervalo semanal";
$vocab["rep_for_nweekly"]                   = "(para n-semanas)";
$vocab["ctrl_click"]                        = "CTRL + clic en el rat�n para seleccionar m�s de un recurso";
$vocab["entryid"]                           = "Reservac�on n� ";
$vocab["repeat_id"]                         = "periodicidad n� ";
$vocab["you_have_not_entered"]              = "No introdujo los datos ";
$vocab["valid_time_of_day"]                 = "una hora v�lida.";
$vocab["brief_description"]                 = "la descripci�n breve.";
$vocab["useful_n-weekly_value"]             = "un intervalo semanal v�lido.";
$vocab["no_compatibility_with_repeat_type"] = "El tipo de periodicidad que usted eligi� es incompatible con la elecci�n : d�a (para n-semanas).";
$vocab['choose_a_day'] = "Choisissez au moins un jour dans la semaine."; // A traduire
$vocab["no_compatibility_n-weekly_value"]   = "Usted no puede definir un intervalo semanal si elige este tipo de periodicidad.";
$vocab["message_records"]                   = "�Se registraron las modificaciones!";
$vocab["click_here_for_series_open"]        = ">>>Clicar aqu� para abrir las opciones de la periodicidad<<<";
$vocab["click_here_for_series_close"]       = ">>>Pulsar aqu� para cerrar las opciones de la periodicidad<<<";
$vocab["choose_a_type"]                     = "Usted tiene que elegir un tipo de reservaci�n.";
$vocab["every week"]                        = "cada semana";
$vocab["week 1/2 "]                         = "una semana sobre dos";
$vocab["week 1/3"]                          = "una semana sobre tres";
$vocab["week 1/4"]                          = "una semana sobre cuatro";
$vocab["week 1/5"]                          = "una semana sobre cinco";
$vocab["choose"]                            = "(Elija)";
$vocab["signaler_reservation_en_cours"]     = "Avisar que la reservaci�n est� en curso de utilizaci�n (reservado a los gestores del recurso)";
$vocab["reservation_en_cours"]              = "Reservaci�n en curso de utilizaci�n.";
$vocab["une_seule_reservation_en_cours"]    = "Observaci�n : para un recurso dado, se puede indicar una �nica reservaci�n \"en curso de utilizaci�n\".";
$vocab["period"] = "Cr�neau :"; // A traduire
$vocab["periods"] = "cr�neau(x)"; // A traduire

# Used in view_entry.php
$vocab["description"]      = "Descripci�n :";
$vocab["room"]             = "Recurso :";
$vocab["createdby"]        = "Creado por :";
$vocab["lastupdate"]       = "�ltima actualizaci�n :";
$vocab["deleteentry"]      = "Suprimir una reservaci�n";
$vocab["deleteseries"]     = "Suprimir una periodicidad";
$vocab["confirmdel"]       = "�Est� seguro/a\\nde querer suprimir\\nesta reserva?\\n\\n";
$vocab["returnprev"]       = "Regresar a la p�gina anterior";
$vocab["invalid_entry_id"] = "n� de reservaci�n no v�lida";
$vocab["reservation_a_confirmer_au_plus_tard_le"] = "R�servation � confirmer au plus tard le :"; // A traduire
$vocab["avertissement_reservation_a_confirmer"] = "(les r�servations non confirm�es sont automatiquement supprim�es)"; // A traduire
$vocab["Reservation confirmee"] = "R�servation confirm�e"; // A traduire
$vocab["confirmer reservation"] = "Cocher la case pour confirmer la r�servation"; // A traduire

# Used in edit_entry_handler.php
$vocab["error"]                      = "Error";
$vocab["sched_conflict"]             = "Conflicto entre reservaciones ";
$vocab["conflict"]                   = "La nueva reservaci�n entra en conflicto con la(s) reserva(s) siguiente(s) :";
$vocab["too_may_entrys"]             = "Las opciones elegidas crear�n demasiadas reservaciones.<BR>�Elija opciones diferentes!";
$vocab["returncal"]                  = "Retorno al calendario";
$vocab["failed_to_acquire"]          = "Error, imposible de obtener el acceso exclusivo a la base de datos";
$vocab["booking_in_past"]            = "Reservaci�n anterior a la fecha actual";
$vocab["booking_in_past_explain"]    = "La fecha de inicio de la nueva reservaci�n pas�. Quiere elegir una fecha de comienzo de reservaci�n posterior a la fecha actual :";
$vocab["booking_in_past_explain_with_periodicity"] = "Una o m�s reservaciones plantean un problema ya que se sit�an anteriormente.<br>Quiere elegir fechas de fin de reservaci�n posteriores a la fecha actual :";
$vocab["error_delais_max_resa_room"] = "No se les autoriz� a reservar este recurso tamb�en a largo tiempo anticipado.";
$vocab["error_delais_min_resa_room"] = "No se les autoriz� a reservar este recurso o a efectuar esta modificaci�n : El t�rmino m�nimo de reservaci�n de este recurso pas�.";
$vocab["del_entry_in_conflict"]      = "Suprimir la(s) reservacion(es) anteriores con el fin de validar la nueva reservaci�n.";
$vocab["error_date_confirm_reservation"] = "Vous devez choisir une date de confirmation inf�rieure � la date de d�but de r�servation."; // A traduire

# Authentication stuff
$vocab["accessdenied"]               = "Acceso rechazado";
$vocab["norights"]                   = "Usted no tiene los derechos suficientes para efectuar esta operaci�n.";
$vocab["nobookings"]                 = "�No hay reservaci�n posible para esta fecha!";
$vocab["msg_max_booking"]            = "El nombre m�ximo de reservaciones de este recurso por usuario se fij� a : ";
$vocab["accessdeniedtoomanybooking"] = "Acceso rechazado : �se rechaza su solicitud de reservaci�n ya que superar�a el m�ximo de reservaciones autorizado!";

# Used in search.php
$vocab["invalid_search"]  = "B�squeda no v�lida.";
$vocab["search_results"]  = "Resultados de la b�squeda para :";
$vocab["nothing_found"]   = "Se encontr� ninguna reservaci�n.";
$vocab["records"]         = "Registros ";
$vocab["through"]         = " a ";
$vocab["of"]              = " sobre ";
$vocab["previous"]        = "Anterior";
$vocab["next"]            = "Siguiente";
$vocab["entry"]           = "Reservaci�n";
$vocab["view"]            = "Ver";
$vocab["advanced_search"] = "B�squeda avanzada";
$vocab["search_button"]   = "B�squeda";
$vocab["search_for"]      = "Buscar";
$vocab["from"]            = "a partir de";
$vocab["dans les champs suivants : "] = "En los campos siguientes&nbsp;: ";

# Used in report.php
$vocab["report_on"]          = "Informe de las reservaciones :";
$vocab["report_start"]       = "Fecha de inicio del informe :";
$vocab["report_end"]         = "Fecha de fin del informe :";
$vocab["match_area"]         = "�mbito :";
$vocab["match_room"]         = "Recurso :";
$vocab["match_entry"]        = "Breve descripci�n :";
$vocab["match_descr"]        = "Descripci�n completa&nbsp;";
$vocab["include"]            = "Incluir :";
$vocab["report_only"]        = "el informe solamente";
$vocab["summary_only"]       = "el resumen solamente";
$vocab["report_and_summary"] = "el informe y el resumen";
$vocab["summarize_by"]       = "Resumido por :";
$vocab["sum_by_descrip"]     = "Breve descripci�n";
$vocab["sum_by_creator"]     = "Creador";
$vocab["entry_found"]        = "reservaci�n encontrada";
$vocab["entries_found"]      = "reservaciones encontradas";
$vocab["summary_header"]     = "Descuenta de las horas reservadas";
$vocab["total"]              = "Total";
$vocab["submitquery"]        = "Indicar el informe";
$vocab["match_login"]        = "Indentificador";
$vocab["csv"]                = "Cargar a distancia el fichero CSV";
$vocab["indexcsv"]           = "Fichero CSV del informe de las reservaciones";
$vocab["dlresumecsv"]        = ">>>Cargar el CSV del resumen<<<";
$vocab["dlrapportcsv"]       = ">>>Cargar el CSV del informe<<<";
$vocab["trier_par"]     = "Trier par"; // A traduire

# Used in week.php
$vocab["weekbefore"]        = "Ver la semana anterior";
$vocab["weekafter"]         = "Ver la semana siguiente";
$vocab["allday"]            = "D�a";

# Used in week_all.php
$vocab["all_rooms"]         = "Todos los recursos";

# Used in month.php
$vocab["monthbefore"]       = "Ver el mes anterior";
$vocab["monthafter"]        = "Ver el mes siguiente";

# Used in month_all.php
$vocab["all_areas"]         = "Todas las reservaciones";
$vocab["change_view"]       = "Cambiar el modo de visualizaci�n";

# Used in {day week month}.php
$vocab["no_rooms_for_area"] = "Ning�n recurso se define para este �mbito.";

# Used in admin_room.php
$vocab["edit"]     = "Modificar";
$vocab["delete"]   = "Suprimir";
$vocab["rooms"]    = "Recursos";
$vocab["in"]       = "de :";
$vocab["noareas"]  = "No hay �mbito";
$vocab["addarea"]  = "A�adir un �mbito";
$vocab["name"]     = "Appellido";
$vocab["noarea"]   = "seleccionar en primer lugar un �mbito.";
$vocab["addroom"]  = "Adicionar un recurso";
$vocab["capacity"] = "Nombre m�xims de personas m�ximas autorizadas en la sala (0 si no se trata de una sala)";
$vocab["norooms"]  = "Ning�n recurso se cre� para este �mbito.";
$vocab['access']   = "Acceso limitado";
$vocab["edittype"]    = "Types de r�servations"; // A traduire

# Used in admin_type_modify.php
$vocab["admin_type_modify_create.php"] = "Ajout d'un nouveau type de r�servation"; // A traduire
$vocab["admin_type_modify_modify.php"] = "Modification d'un type de r�servation"; // A traduire
$vocab["msg_type_created"]             = "Vous venez de cr�er un nouveau type de r�servation."; // A traduire
$vocab["update_type_failed"]  = "La mise � jour du type de r�servation a �chou� : "; // A traduire
$vocab["explications_active_type"] = "<b>Remarque : </b>quand un type est d�coch�, celui-ci n'est plus propos� � l'utilisateur effectuant ou modifiant une r�servation, mais les r�servations d�j� effectu�es continuent d'appara�tre sur les plannings avec ce type, bien que d�sactiv�."; // A traduire

# Used in admin_type.php  // A traduire
$vocab["admin_type.php"] = "Types de r�servation";
$vocab["display_add_type"]          = "Ajouter un type de r�servation";
$vocab["type_num"]                  = "Identifiant";
$vocab["type_name"]                 = "Nom du type";
$vocab["type_order"]                = "Ordre d'affichage";
$vocab["type_color"]                = "Couleur";
$vocab["type_valide_domaine"]      = "Type valide pour le domaine";
$vocab["admin_type_explications"]  = "Par d�faut, lors de la cr�ation d'un nouveau type de r�servations, celui-ci est commun � tous les domaines. Vous pouvez ensuite sp�cifier, pour chaque domaine, les types valides ou non.";

# Used in admin_edit_room.php
$vocab["editarea"]                      = "Modificar el �mbito";
$vocab["editroom"]                      = "Modificar el recurso";
$vocab["update_room_failed"]            = "La actualizaci�n del recurso fall� : ";
$vocab["error_room"]                    = "Error : recurso ";
$vocab["not_found"]                     = " no encontrado";
$vocab["update_area_failed"]            = "La actualizaci�n del �mbito fall� : ";
$vocab["error_area"]                    = "Error: �mbito ";
$vocab["order_display"]                 = "Orden de visualizaci�n";
$vocab["max_booking"]                   = "Nombre m�x. de reservaciones por usuario (-1 si no hay restricci�n)";
$vocab["explain_max_booking"]           = "La restricci�n no se aplica a los gestores del recurso.";
$vocab["declarer_ressource_indisponible"] = "Declarar este recurso temporalmente indisponible. Entonces las reservaciones son imposibles.";
$vocab["montrer_fiche_pr�sentation_ressource"] = "Volver visible la fecha de presentaci�n del recurso en el interfaz p�blico.";
$vocab["choisir_image_ressource"]       = "Elegir una imagen del resurso para la ficha de presentaci�n (png, jpg et gif solamante)";
$vocab["supprimer_image_ressource"]     = "Suprimir la imagen actual del rescurso.";
$vocab["description compl�te"]          = "Descripci�n completa (visible en la ficha de presentaci�n) - Usted puede utilizar balizas HTML.";
$vocab['ip_adr']                        = "Direcci�n IP cliente";
$vocab['ip_adr_explain']                = "<b>Observaci�n sobre la direcci�n IP cliente : </b>
<br>Si la direcci�n IP de la m�quina cliente es id�ntica a esta direcci�n, este �mbito se convierte en el �mbito por defecto.
<br>Se supone por otra parte que :
<br>1) el administrador no defini� un �mbito por defecto en la p�gina de configuraci�n general,
<br>2) el usuario no defini� su propio �mbito por defecto en la p�gina de gesti�n de su cuenta.";
$vocab["morningstarts_area"]            = "Hora de comienzo de d�a";
$vocab["eveningends_area"]              = "Hora de fin de d�a (superior a la hora de comienzo de d�a)";
$vocab["resolution_area"]               = "El m�s peque�o bloque reservable, en segundos (1800 segundos";
$vocab["eveningends_minutes_area"]      = "Nombre de minutos a adicionar a la hora de fin de d�a para tener el fin real de un d�a.";
$vocab["weekstarts_area"]               = "Inicio de la semana";
$vocab["twentyfourhour_format_area"]    = "Formato de visualizaci�n del tiempo";
$vocab["twentyfourhour_format_12"]      = "Visualizaci�n 12h.";
$vocab["twentyfourhour_format_24"]      = "Visualizaci�n 24h.";
$vocab["configuration_plages_horaires"] = "Configuraci�n de la visualizaci�n de las planificaciones de los recursos de este �mbito";
$vocab["delais_max_resa_room"]          = "Nombre de d�as m�ximos m�s all� de los cuales el usuario no puede reservar o modificar una reservaci�n
(-1 si no hay restricci�n).
<br><b>Ejemplo</b> : un valor igual a 30 significa que un usuario no puede reservar un recurso sino 30 d�as de antemano al m�ximo.
<br><i>Esta limitaci�n no afecta a los gestores del recurso as� como a los administradores del �mbito.</i>";
$vocab["delais_max_resa_room_2"]        = "<b>Nombre de d�as m�ximos</b> m�s all� de los cuales el usuario no puede reservar o modificar una reservaci�n :";
$vocab["delais_min_resa_room"]          = "Tiempo <b>en minutos<b/> por debajo del cual el usuario no puede reservar o modificar une reservaci�n (0 si no hay restricci�n).
<br><b>Ejemplo</b> : un valor igual a 60 significa que un usuario no puede reservar un recurso o modificar una reservaci�n menos de 60 minutos antes del comienzo de la reservaci�n.
<br><i>Esta limitaci�n no afecta a los gestores del recurso as� como a los administradores del �mbito.</i>";
$vocab["delais_min_resa_room_2"]        = "<b>Tiempo en minutos</b> por abajo del cual el usuario no puede reservar o modificar una reservaci�n :";
$vocab["allow_action_in_past"]          = "Permetir las reservaciones anteriormente asi como las modificaciones/supresiones de reservaciones pasadas.";
$vocab["allow_action_in_past_explain"]  = "Si la casilla no tiene un cruz, un usuario (ni un gestor incluso o un administrador) no puede efectuar una reservaci�n anteriormente, ni modificar o suprimir una reservaci�n pasada. Solo el administrador general tiene esta posibilidad.";
$vocab["avertissement_change_type"] = "ATTENTION : les deux types de configuration des cr�neaux sont incompatibles entre eux : un changement du type de cr�neaux entra�ne donc, apr�s validation, un effacement de toutes les r�servations de ce domaine."; // A traduire
$vocab["intitule_creneau"] = "Intitul� du cr�neau n� "; // A traduire
$vocab["nombre_de_creneaux"] = "Nombre de cr�neaux"; // A traduire
$vocab["creneaux_de_reservation_temps"] = "Les cr�neaux de r�servation sont bas�s sur le temps."; // A traduire
$vocab["creneaux_de_reservation_pre_definis"] = "Les cr�neaux de r�servation sont bas�s sur des intitul�s pr�-d�finis."; // A traduire
$vocab["msg_option_de_reservation"] = "<b>Poser des r�servations \"sous r�serve\"</b> : indiquer une valeur diff�rente de 0 pour activer cette fonctionnalit�.<br>La valeur ci-contre d�signe le nombre maximal de jours pour confirmer une r�servation"; // A traduire
$vocab["type_affichage_reservation"] = "Pour une nouvelle r�servation ou modification d'une r�servation, l'utilisateur sp�cifie"; // A traduire
$vocab["affichage_reservation_duree"] = " date/heure de d�but de r�servation et <b>dur�e de la r�servation</b>"; // A traduire
$vocab["affichage_reservation_date_heure"] = " date/heure de d�but de r�servation et <b>date/heure de fin de r�servation</b>"; // A traduire
$vocab["cocher_jours_a_afficher"] = "Cochez ci-dessous les jours � afficher sur les diff�rents plannings.<br><b>Remarque </b>: si vous d�cidez de ne pas afficher certains jours, veillez �galement � d�sactiver ces jours dans le \"Calendrier hors r�servation\"."; // A traduire

# Used in admin_room_del.php
$vocab["deletefollowing"] = "Usted va suprimir las reservaciones siguientes";
$vocab["sure"]            = "�Est� usted seguro/a?";
$vocab["YES"]             = "SI";
$vocab["NO"]              = "NO";
$vocab["delarea"]         = "Debe suprimir todos los recursos de este �mbito antes de poder suprimir lo<p>";

# Used in help.php
$vocab["about_mrbs"]        = "A prop�sito de GRR (Gesti�n y Reservaci�n de Recursos)";
$vocab["database"]          = "Base de datos : ";
$vocab["system"]            = "Sistema de explotaci�n : ";
$vocab["please_contact"]    = "Contactar ";
$vocab["for_any_questions"] = "si usted tiene una cuesti�n que no se trata aqu�.";

# Used in mysql.inc AND pgsql.inc
$vocab["failed_connect_db"] = "Grave error: Derrota de la conexi�n a la base de datos ";

# Used in admin_room.php
$vocab["admin_room.php"]          = "�mbitos y recursos";
$vocab["admin_user.php"]          = "Usuarios";
$vocab["admin_right.php"]         = "Gesti�n des los recursos por los usuarios";
$vocab["admin_right_admin.php"]   = "Administraci�n de los �mbitos por los usuarios";
$vocab["admin_access_area.php"]   = "Acceso a los �mbitos limitados";
$vocab["admin_email_manager.php"] = "Emails autom�ticos";
$vocab["admin_config.php"]        = "Configuraci�n general";
$vocab["admin_calend.php"]        = "Reservaci�n d�as enteros";
$vocab["admin_overload.php"] = "Champs additionnels"; // A traduire
$vocab["admin_menu_general"] = "G�n�ral"; // A traduire
$vocab["admin_menu_arearoom"] = "Domaines et ressources"; // A traduire
$vocab["admin_menu_user"] = "Utilisateurs et acc�s"; // A traduire
$vocab["admin_menu_various"] = "Divers"; // A traduire
$vocab["admin_menu_auth"] = "Authentification et ldap"; // A traduire

# Used in admin_right_admin.php
$vocab["admin_right_admin_explain"] = "Adem�s de sus derechos normales, el administrador de un �mbito tiene la posibilidad de administrar enteramente un �mbito : creaci�n, supreci�n de un recurso, a�adido y supresi�n de los gestores de resevaciones, gesti�n de los emails autom�ticos.";
$vocab["administration_domaine"]    = "Administraci�n del �mbito :";
$vocab["no_admin_this_area"]        = "!Actualmente nadie, excepto los administradores, est� autorizado a administrar el �mbito que aparece al lado!";
$vocab["user_admin_area_list"]      = "Lista de las personas autorizadas a administrar el �mbito que aparece al lado (excepto los administradores) :";

# Used in admin_right.php
$vocab["select"]              = "(Seleccione)";
$vocab["select_all"]          = "(todas)";
$vocab["no_area"]             = "Nig�n �mbito fue seleccionado.";
$vocab["no_restricted_area"]  = "Nig�n �mbito con acceso limitado se defini�.";
$vocab["administration1"]     = "Gesti�n  del recurso :";
$vocab["administration2"]     = "Gesti�n de los recursos siguientes :";
$vocab["user_list"]           = "Lista de las personas autorizadas a administrar el/los recurso(s) que aparece(n) al lado (excepto los administradores) :";
$vocab["add_user_to_list"]    = "A�adir un usuario a la lista :";
$vocab["no_admin"]            = "�Actualmente nadie, excepto los administradores, tiene acceso  a este �mbito!";
$vocab["no_admin_all"]        = "�Actualmente nadie, excepto los administradores, est� autorizado a administrar el conjunto de los recursos al lado!";
$vocab["warning_exist"]       = "�El usuario que intent� a�adir ya pertenece a la lista!";
$vocab["nobody"]              = "(ninguno)";
$vocab["admin_right_explain"] = "Adem�s de sus derechos normales, el gestor de un recurso tiene la posibilidad de modificar o de suprimir cualquier reservaci�n de este recurso. Adem�s las restricciones relativas a las reservaciones maximales del recurso no se aplican.";

# Used in admin_access_area.php
$vocab["user_area_list"] = "Lista de las personas que tienen acceso a este �mbito (excepto los administradores) :";
$vocab["no_user_area"]   = "!Actualmente nadie, excepto los administradores, tiene acceso a este �mbito!";

# Used in admin_email_manager.php
$vocab["no_room"]                 = "Ning�n recurso fue seleccionado en este �mbito.";
$vocab["mail_user_list"]          = "Lista de los usuarios avisados por mail :";
$vocab["no_mail_user_list"]       = "!Actualmente no hay usario en la lista!";
$vocab["explain_automatic_mail2"] = "Por otra parte, cuando un usuario <b>reserva un recurso</b>, <b>modifica</b> o <b>suprime<b> una reservaci�n, algunos usuarios pueden ser avisados por e-mail. Para cada recurso, usted puede designar a uno o m�s usuarios a avisar :";
$vocab["explain_automatic_mail3"] = "Cuando un usuario <b>modifica</b> o <b>suprime<b> una reservaci�n efectuada por un otro usuario, este �ltimo (si el campo email fue indicado) es avisado autom�ticamente por un mensaje email. ";
$vocab["add_user_succeed"]        = "Un usuario fue a�adido a la lista.";
$vocab["del_user_succeed"]        = "Un usuario fue suprimido a la lista.";
$vocab["attention_mail_automatique_d�sactive"] = "ATENCI�N : se desactiva el env�o de mails autom�ticos. Para activarlo, encuentrese en el panel de configuraci�n general.";

# Used in admin_user_modify.php
$vocab["admin_user_modify_create.php"] = "Adici�n de un nuevo usuario";
$vocab["admin_user_modify_modify.php"] = "Modificaci�n de un usuario existente";
$vocab["please_enter_name"]            = "�Quiere introducir un apellido y un nombre para el usuario!";
$vocab["error_exist_login"]            = "***�Atenci�n! Ya existe un usuario que utiliza la misma clave de acceso . �Registro imposible! ***";
$vocab["msg_login_created"]            = "Acabe usted de crear un nuevo usuario. Por defecto, este usuario es considerado como activo.";
$vocab["msg_login_created_error"]      = "�Ocurri� un problema en el momento de la creaci�n de un nuevo usuario! Por favor contacte el soporte t�cnico.";
$vocab["message_records_error"]        = "Error durante el registro de los datos";
$vocab["only_letters_and_numbers"]     = "�El identificador del usario debe estar constituido solamente con letras y cifras!";
$vocab["change_pwd"]                   = "Cambiar de contrase�a";
$vocab["activ_no_activ"]               = "Estado";
$vocab["activ_user"]                   = "Activo";
$vocab["no_activ_user"]                = "No activo";
$vocab["required"]                     = "Los campos se�alados de un * son obligatorios.";
$vocab["statut_user"]                  = "Usuario";
$vocab["statut_visitor"]               = "Visitante";
$vocab["statut_administrator"]         = "Administrador";
$vocab["mail_user"]                    = "Email";
$vocab["pwd_toot_short"]               = "Contrase�a  (".$pass_leng." caracteres m�nimos)";
$vocab["confirm_pwd"]                  = "Contrase�a  (a confirmar)";
$vocab["Changer_source_utilisateur_local"] = "Marcar la casilla al lado para que la autentificaci�n sea local. (da la autentificaci�n externa como que Idap, CAS SSO, o Lemonldap definitivamente imposible). �Procedimiento irreversible!";
$vocab["administrateur du domaine"] = "Administrateur du domaine"; // A traduire
$vocab["gestionnaire des resources suivantes"] = "Gestionnaire des resource(s) suivante(s) :"; // A traduire
$vocab["liste_privileges"] = "Liste des privil�ges  de l'utilisateur "; // A traduire
$vocab["pas de privileges"] = "Pas de privil�ges"; // A traduire
$vocab["a acces au domaine"] = "Cet utilisateur a acc�s � ce domaine restreint."; // A traduire
$vocab["est prevenu par mail"] = "Cet utilisateur est averti automatiquement par mail pour le(s) ressource(s) suivante(s)."; // A traduire
$vocab["administrateur general"] = "Administrateur g�n�ral"; // A traduire

# Used in admin_user.php
$vocab["display_add_user"]          = "A�adir un usuario";
$vocab["display_add_user_list_csv"] = "Importar dans GRR un fichero de usuarios (formato CSV)";
$vocab["display_all_user.php"]      = "Indicar : todos los usarios";
$vocab["display_user_on.php"]       = "los usuarios activos";
$vocab["display_user_off.php"]      = "los usuarios inactivos";
$vocab["OK"]                        = "Validar";
$vocab["statut"]                    = "Estatuto";
$vocab["login_name"]                = "Nombre de la clave de acceso";
$vocab["names"]                     = "Apellido y nombre";
$vocab["authentification"]          = "Autentificaci�n :";
$vocab["Externe"]                   = "Externo";
$vocab["privileges"] = "privil�ges"; // A traduire
$vocab["maj_base_locale"] = "Nettoyage de la base locale"; // A traduire
$vocab["mess_maj_base_locale"] = "Recherche et suppression de la base locale de GRR, des utilisateurs LCS qui ne sont plus pr�sents dans la base LCS. Cliquez sur OK pour continuer."; // A traduire
$vocab["mess2_maj_base_locale"] = "Les utilisateurs suivants ont �t� supprim�s de la base locale car ils n'�taient plus pr�sents dans la base LCS :"; // A traduire
$vocab["mess3_maj_base_locale"] = "Il n'y a aucun utilisateur � supprimer de la base locale."; // A traduire
$vocab["synchro_base_locale"] = "Mise � jour de la base locale"; // A traduire
$vocab["mess_synchro_base_locale"] = "Mise � jour des utilisateurs de la base locale de GRR � partir des informations de la base LCS et insertion dans la base locale de GRR des utilisateurs LCS qui ne sont pas pr�sents localement. Cliquez sur OK pour continuer (cette op�ration peut �tre longue !)."; // A traduire
$vocab["liste_nouveaux_utilisateurs"] = "Liste des nouveaux utilisateurs ins�r� dans la base locale : "; // A traduire
$vocab["liste_pb_insertion"] = "Probl�me : les utilisateurs suivants n'ont pas pu �tre ins�r�s dans la base locale : "; // A traduire
$vocab["liste_utilisateurs_modifie"] =  "Liste des utilisateurs mis � jour dans la base locale : "; // A traduire
$vocab["liste_pb_update"] =  "Probl�me : les utilisateurs suivants n'ont pas pu �tre mis � jour dans la base locale : "; // A traduire

# Used in admin_config.php
$vocab["title_disable_login"]            = "Activaci�n/desactivaci�n de las conexiones";
$vocab["explain_disable_login"]          = "Desactivando las conexiones, usted hace que la conexi�n al sitio para los usuarios sea imposible, excepto para los administradores. Adem�s, los usuarios actualmente conectados son desconectados autom�ticamente.
<br>Sin embargo, si la conexi�n no es obligatoria para el acceso al sitio en visualizaci�n, este acceso sigue siendo posible.";
$vocab["disable_login_on"]               = "Desactivar las conexiones";
$vocab["disable_login_off"]              = "Activar las conexiones";
$vocab["submit"]                         = "Enviar";
$vocab["miscellaneous"]                  = "Informaciones diversas";
$vocab["title_home_page"]                = "T�tulo de la p�gina de conexi�n";
$vocab["message_home_page"]              = "Mensaje de advertencia de la p�gina de conexi�n";
$vocab["company"]                        = "Nombre del establecimiento";
$vocab["webmaster_name"]                 = "Apellido y nombre del gestor del sitio";
$vocab["webmaster_email"]                = "Direcci�n email del gestor del sitio";
$vocab["technical_support_email"]        = "Direcci�n email del soporte t�cnico";
$vocab["grr_url"]                        = "Direcci�n del sitio";
$vocab["title_session_max_length"]       = "Duraci�n m�xima de una sesi�n";
$vocab["session_max_length"]             = "Duraci�n m�xima de inactividad (en minutos) :";
$vocab["explain_session_max_length"]     = "Esta duraci�n indica el tiempo m�ximo de inactividad al final del cual un usuario est� desconectado autom�ticamente.";
$vocab["title_automatic_mail"]           = "Env�o de mails autom�ticos";
$vocab["warning_message_mail"]           = "Algunos alojamientos desactivan el env�o autom�tico de mails desde sus servidores. En este caso, la funcionalidad siguiente no funcionar�.";
$vocab["explain_automatic_mail"]         = "Cuando un usuario modifica o suprime una reservaci�n efectuada por otro usuario, este �ltimo (si el campo email se registr�) es informado autom�ticamente por un mensaje email.<br>Cuando un usuario reserva un recurso, modifica o suprime una reservaci�n, algunos usuarios designados por el administrador (en la r�brica de gesti�n de los mails autom�ticos) pueden ser avisados por emails. �Desea usted utilizar esta opci�n?";
$vocab["mail_admin_on"]                  = "Avisar por mail a los usuarios";
$vocab["mail_admin_off"]                 = "No prevenir por mail";
$vocab["title_backup"]                   = "Protecci�n de la base GRR";
$vocab["explain_backup"]                 = "Creaci�n de un fichero de protecci�n completo de la base GRR. En caso de problema sobre la base GRR, utilizar una herramienta como PHPMYADMIN para restaurar la base.";
$vocab["warning_message_backup"]         = "�Si un mensaje del tipo \"Tiempo m�ximo de ejecuci�n excedido\" aparece unos momentos despu�s de haber iniciado la copia de seguridad, eso significa que la creaci�n de la copia de seguridad fall�!";
$vocab["submit_backup"]                  = "Proceder a efectuar una copia de seguridad";
$vocab["title_begin_end_bookings"]       = "Inicio y fin de las reservaciones";
$vocab["begin_bookings"]                 = "Fecha de inicio de las reservaciones : ";
$vocab["begin_bookings_explain"]         = "No hay reservaci�n posible antes de esta fecha. Adem�s las reservaciones ya efectuadas antes de esta fecha se borrar�n de la base.";
$vocab["end_bookings"]                   = "Fecha de fin de las reservaciones : ";
$vocab["end_bookings_explain"]           = "No hay reservaci�n posible tras esta fecha. Adem�s las reservaciones ya efectuadas tras esta fecha se borrar�n de la base.";
$vocab["default_parameter_values_title"] = "Elecci�n de los par�metros de visualizaci�n por defecto";
$vocab["default_room_all"]               = "Todos los recursos (visualizaci�n 'd�a')";
$vocab["default_room_week_all"]          = "Todos los recursos (visualizaci�n 'semana')";
$vocab["default_room_month_all"]          = "Toutes les ressources (affichage 'mois')"; // A traduire
$vocab["default_room_month_all_bis"]          = "Toutes les ressources (affichage 'mois' bis)"; // A traduire
$vocab["display_week"]                   = "(visualizaci�n 'semana')";
$vocab["default_area"]                   = "�mbito por defecto : ";
$vocab["default_room"]                   = "Recursos(s) indicado(s) : ";
$vocab["choose_an_area"]                 = "(Elija un �mbito)";
$vocab["explain_default_parameter"]      = "Elija en esta r�brica, la visualizaci�n por defecto de la p�gina inicial. Son los par�metros que se aplicar�n si el propio usuario no eligi� par�metros de visualizaci�n por defecto.";
$vocab["restricted"]                     = "limitado";
$vocab["explain_area_list_format"]       = "Tipo de visualizaci�n de las listas de los �mbitos y recursos ";
$vocab["liste_area_list_format"]         = "Visualizaci�n en forma de una arborescencia";
$vocab["select_area_list_format"]        = "Visualizaci�n en forma de una lista desplegable";
$vocab["explain_default_area_and_room"]  = "�mbito y recurso por defecto";
$vocab["explain_css"]                    = "Elecci�n del estilo/tema";
$vocab["choose_css"]                     = "Elija en la lista que aparece al lado : ";
$vocab["reset"]                          = "Valores por defecto";
$vocab["choose_language"]                = "Elecci�n de la lengua por defecto";
$vocab["mess_avertissement_config"] = "Remarque : en plus des param�tres ci-dessous, d'autres param�tres de configuration de GRR sont � votre disposition dans le fichier � config.inc.php � (Consulter la documentation de GRR)."; // A traduire

# Used in admin_change_pwd.php.php
$vocab["passwd_error"]       = "�Error en la introducci�n de la contrase�a, int�ntelo de nuevo!";
$vocab["update_pwd_failed"]  = "�Error en la actualizaci�n de la contrase�a!";
$vocab["update_pwd_succeed"] = "�La contrase�a ha sido cambiada!";
$vocab["back"]               = "Retorno";
$vocab["pwd_change"]         = "Cambio de la contrase�a";
$vocab["login"]              = "Identificador";
$vocab["last_name"]          = "Apellido";
$vocab["first_name"]         = "Nombre";
$vocab["pwd_msg_warning"]    = "Atenci�n : la contrase�a debe implicar ".$pass_leng." caracteres m�nimos (letras y cifras,...). Se aconseja de no elegir una contrase�a muy simplista.";
$vocab["new_pwd1"]           = "Nueva contrase�a (".$pass_leng." caracteres minimos)";
$vocab["new_pwd2"]           = "Nueva contrase�a (� confirmar)";
$vocab["pwd_msg_warning2"]   = "�Por razones de seguridad, por favor utilice el m�dulo \"Administrar mi cuenta\" accesible a partir de la p�gina inicial para cambiar su contrase�a!";

# Used in my_account.php
$vocab["wrong_pwd2"]               = "�Error en la introducci�n de la contrase�a, por favor vuelva a empezar!";
$vocab["wrong_old_pwd"]            = "�La antigua contrase�a no es correcta!";
$vocab["update_email_succeed"]     = "�Se modific� la direcci�n email!";
$vocab["old_pwd"]                  = "Antigua contrase�a";
$vocab["click_here_to_modify_pwd"] = ">>>Clicar aqu� para modificar su contrase�a<<<";

# Used in admin_import_users_csv.php
$vocab["admin_import_users_csv0"]  = "Fichero CSV a importar : ";
$vocab["admin_import_users_csv1"]  = "El fichero a importar contiene una primera l�nea de encabezamiento, a ignorar ";
$vocab["admin_import_users_csv2"]  = "<p>El fichero de importaci�n debe ser al formato csv (separador : punto y coma)
<br>El fichero debe contener los distintos campos siguiente :<br>
--> <B>Identificador</B> : el identificador del usuario<br>
--> <B>Apellido</B><br>
--> <B>Nombre</B><br>
--> <B>contrase�a</B><br>
--> <B>Direcci�n e-mail</B><br>";
$vocab["admin_import_users_csv3"]  = "<p><b>ALGUNAS PRECISIONES :</b><br><br>
<b>identifidor</b><br>20 caracteres como m�ximo. Se puede tratar de cualquier secuencia de caracteres y/o de cifras sin espacio.
El car�cter _ es tambi�n autorizado. Si este formato no se respeta, la consecuencia de car�cter ??? aparece al lugar del identificador.
Los identificadores que aparecen en rojo corresponden a apellidos de usuarios ya existentes en la base GRR.
�Entonces los datos existentes ser�n aplastados por los datos presentes en el fichero importado!<br><br>
<b>Nom</b><br>Apellido del usuario. 30 caracteres m�ximos.
Puede tratarse de cualquier consecuencia de caracteres y/o de cifras con eventualmente espacios y ap�strofes.<br><br>
<b>Nombre</b><br>Nombre del usuario. Misma observaci�n que para el apellido.
Los apellidos y los nombres que aparecen en azul coresponden a usuarios que existen en la base GRR y que llevan los mismos apellidos y nombres.<br><br>
<b>Contrase�a</b><br>".$pass_leng." caracteres m�nimos y 30 caracateres m�ximos.<br><br><b>Direcci�n e-mail</b>
<br>100 caracteres como m�ximo. Poner el s�mbolo \"-\"si no hay una direcci�n e-mail<br><br></p>";
$vocab["admin_import_users_csv4"]  = "Imposible de abrir el fichero CSV";
$vocab["admin_import_users_csv5"]  = "Primera fase de la importaci�n : ";
$vocab["admin_import_users_csv6"]  = "entradas detectadas!";
$vocab["admin_import_users_csv7"]  = "<p><b>ADVERTENCIA</b> : las claves de acceso que parecen en rojo corresponden a claves de acceso que existen ya en la base GRR. �Por lo tanto, los datos existentes ser�n aplastados por los datos presentes en el fichero que va a importarse!</p>";
$vocab["admin_import_users_csv8"]  = "<p><b>ATENCI�N</b> : los apellidos y nombres que parecen en azul corresponden a usuarios ya que existen en la base GRR y que llevan los mismos apellidos y nombres.</p>";
$vocab["admin_import_users_csv9"]  = "<p><b>ADVERTENCIA</b> : los s�mbolos <b>???</b> significan que el campo en cuesti�n no es v�lido. <b>La operaci�n de importaci�n de los datos no puede continuar normalmente.</b> Por favor corrija el fichero a importar.<br></p>";
$vocab["admin_import_users_csv10"] = "�La importaci�n fall�!";
$vocab["admin_import_users_csv11"] = "�Ning�n fichero se seleccion�!";
$vocab["admin_import_users_csv12"] = "�Se cre� el usuario!";

# Used in admin_calend_ignore.php  // A traduire
$vocab["admin_calend_ignore.php"]     = "Calendrier hors r�servation";
$vocab["calendrier_des_jours_hors_reservation"] = "Calendrier des journ�es hors r�servation";
$vocab["les_journees_cochees_sont_ignorees"] = "Les journ�es coch�es correspondent � des journ�es pendant lesquelles il n'est pas possible de r�server.<br>En ce qui concerne les r�servations avec p�riodicit�, ces journ�es sont ignor�es lors de la validation de la r�servation.<br><br><b>Attention </b> : si des r�servations ont d�j� �t� enregistr�es sur les journ�es coch�es, celles-ci seront <b>automatiquement et irr�m�diablement supprim�es</b>. De plus, les personnes concern�es par les suppressions ne seront pas pr�venues par email.";

# Used in admin_calend.php
$vocab["check_all_the"]                = "Marcar todos los ";
$vocab["uncheck_all_the"]              = "Demarcar todos los ";
$vocab["uncheck_all_"]                 = "Demarcar todo";
$vocab["admin_calendar_title.php"]     = "Reservaci�n/Supresi�n en bloque de d�as enteros";
$vocab["admin_calendar_explain_1.php"] = "este procedimiento le permite de <b>reservar</b> o de </b>liberar</b> muy r�pidamente
d�as enteros simult�neamente sobre varios recursos de varios �mbitos y seg�n un calendario.
<br><br><b>Ejemplo :</b> Puede as� bloquear al a�o algunos d�as como el fin de semana, las vacaciones, los d�as de fiesta...
<br><b>Atenci�n :</b> si hay un conflicto con reservaciones existentes, estas ser�n <b>autom�ticamente e irremediablemente suprimidas</b> en favor
de la nueva reservaci�n. Adem�s, las personas afectadas por las supresiones no ser�n avisadas por email.
<br><br>Este procedimiento se desarrolla en tres etapas :
<ul>
<li>Elecci�n de los �mbitod y del tipo de acci�n</li>
<li>Elecci�n de los recursos que deben reservarse, nombre, descripci�n y tipo de las reservaciones</li>
<li>Elecci�n de las pr�ximas fechas sobre un calendario
</ul>
<b>Las modificaciones efectivas de las reservaciones solo se producen despu�s de la tercera etapa.</b>";
$vocab["etape_n"]                      = "Etapa n� ";
$vocab["choix_domaines"]               = "Elija los �mbitos a los cuales se referir�n las reservaciones o las supresiones de las reservaciones :";
$vocab["choix_action"]                 = "Elija el tipo de acci�n :";
$vocab["choose_a_room"]                = "Debe elegir al menos un recurso.";
$vocab["reservation_en_bloc"]          = "Reservaci�n de d�as enteros";
$vocab["reservation_en_bloc_result"]   = "Se termina el procedimiento de reservaci�n.";
$vocab["reservation_en_bloc_result2"]  = "<b>Reservaciones que entraban en conflicto con las nuevas reservaciones fueron suprimidas al nombre de : </b>";
$vocab["suppression_en_bloc"]          = "Supresi�n de reservaciones sobre d�as enteros";
$vocab["suppression_en_bloc_result"]   = "Se termina el procedimiento de supresi�n de reservaciones. <br><b>Nombre de supresiones efectuadas : </b>";

# Used in admin_confirm_change_date_bookings.php
$vocab["cancel"]                                 = "Anular";
$vocab["admin_confirm_change_date_bookings.php"] = "Confirmaci�n de cambio de las fechas de comienzo y fin de las reservaciones";
$vocab["msg_del_bookings"]                       = "�Atenci�n, los cambios de las fechas de comienzo y de fin de las reservaciones que usted efectu� van a ocasionar la supresi�n definitiva de las reservaciones ya efectuadas fuera de las nuevas fechas autorizadas!<br><br>�Quiere usted continuar?";

# Used in admin_view_connexions.php
$vocab["admin_view_connexions.php"] = "Seguimiento de las conexiones";
$vocab["users_connected"]           = "Usuarios actualmente conectados";
$vocab["sen_a_mail"]                = "Enviar un mail";
$vocab["deconnect_changing_pwd"]    = "Desconectar cambiando la contrase�a";
$vocab["log"]                       = "Diario de las conexiones desde el ";
$vocab["msg_explain_log"]           = "Las fechas que aparecen en rojo se�alan a los usuarios desconectados autom�ticamente despu�s de un largo plazo de inactividad.<br>Las l�neas que aparecen en verde se�alan a los usuarios actualmente conectados.";
$vocab["begining_of_session"]       = "Inicio de sesi�n";
$vocab["end_of_session"]            = "Fin de sesi�n";
$vocab["ip_adress"]                 = "Direcci�n IP";
$vocab["navigator"]                 = "Navegador";
$vocab["referer"]                   = "Procedencia";
$vocab["start_history"]             = "Inicio del recuento hist�rico";
$vocab["erase_log"]                 = "Las entradas anteriores del diario desepareceran a la fecha de debajo.";
$vocab["cleaning_log"]              = "Limpieza del diario";
$vocab["logs_number"]               = "Nombre de entradas actualmente presentes en el diario de conexi�n : ";
$vocab["older_date_log"]            = "Actualmente, el diario contiene los antecedentes de las conexiones desde el ";
$vocab["delete_up_to"]              = "Borrar hasta el";

# Used for functions.js
$vocab["confirm_del"] = "Confirmar la supresi�n";

# Used in mincals.inc
$vocab["see_month_for_this_room"]         = "Ver las reservaciones del mes para este recurso";
$vocab["see_week_for_this_room"]          = "Ver las reservaciones de la semana para este recurso";
$vocab["see_all_the_rooms_for_the_day"]   = "Ver todos los recursos del �mbito para este d�a";
$vocab["see_all_the_rooms_for_the_month"] = "Ver las reservaciones del mes para todos los recursos";
$vocab["see_week_for_this_area"]          = "Ver las reservaciones de la semana para todos los recursos";

# Used in admin_maj.inc
$vocab["admin_maj.php"]              = "N�mero de versi�n y actualizaci�n";
$vocab["num_version_title"]          = "N�mero de versi�n de GRR";
$vocab["num_version"]                = "N�mero de versi�n: <b>GRR";
$vocab["maj_bdd"]                    = "Actualizaci�n de la base de datos (acceso administrador)";
$vocab["maj_bdd_not_update"]         = "ATENCI�N: Su base de datos no parece estar al d�a.";
$vocab["maj_version_bdd"]            = "N�mero de versi�n de la base de datos : GRR";
$vocab["maj_do_update"]              = "Clicar el siguiente bot�n para efectuar la actualizaci�n hacia la versi�n GRR";
$vocab["maj_submit_update"]          = "Poner al d�a";
$vocab["maj_no_update_to_do"]        = "Usted no tiene de actualizaci�n de la base de datos que efectuar.";
$vocab["maj_go_www"]                 = "Encuentrese en el lugar de GRR para conocer la �ltima versi�n : ";
$vocab["maj_good"]                   = "Actualizaci�n efectuada. (lea atentamente el resultado de la actualizaci�n, en la parte inferior de la p�gina)";
$vocab["please_go_to_admin_maj.php"] = "Se aconseja mucho poner al d�a su base encontrandose en la parte GESTI�N.";

# Used in admin_maj.inc
$vocab["capacity_2"]              = "Capacidad : ";
$vocab["Pas d'image disponible"]  = "No hay imagen disponible";
$vocab["Image de la ressource"]   = "Imagen del recurso&nbsp;";

# Used in csv.php
$vocab["enrecherchant"] = "buscando :";

# Used in view_room.php  // A traduire
$vocab["utilisateurs ayant privileges"] = "Liste des utilisateurs ayant des privil�ges sur cette ressource (hormis les administrateurs g�n�raux) :";
$vocab["utilisateurs gestionnaires ressource"] = "Les utilisateurs suivants sont gestionnaires de cette ressource :";
$vocab["utilisateurs mail automatique"] = "Les utilisateurs suivants sont avertis par email :";
$vocab["utilisateurs acces restreint"] = "Les utilisateurs suivants sont autoris�s � acc�der � cette ressource :";
$vocab["utilisateurs administrateurs"] = "Les utilisateurs suivants sont administrateurs du domaine contenant cette ressource :";
$vocab["aucun autilisateur"] = "Pas d'utilisateurs";

# Used in view_rights_area.php  // A traduire
$vocab["utilisateurs ayant privileges sur domaine"] = "Liste des utilisateurs ayant des privil�ges sur ce domaine (hormis les administrateurs g�n�raux) :";
$vocab["utilisateurs acces restreint domaine"] = "Les utilisateurs suivants sont autoris�s � acc�der � ce domaine :";
$vocab["utilisateurs administrateurs domaine"] = "Les utilisateurs suivants sont administrateurs du domaine :";

# Used in admin_overload.php // A traduire
$vocab["explication_champs_additionnels"] = "Sur cette page, vous avez la possibilit� de d�finir, domaine par domaine, des champs additionnels de votre choix et qui appara�tront dans les formulaires de saisie des r�servations comme autant de champs suppl�mentaires facultatifs.";
$vocab["fieldname"] = "Intitul� du champ";
$vocab["fieldtype"] = "Type du champ";
$vocab["add"] = "Ajouter";
$vocab["del"] = "Effacer";
$vocab["change"] = "Modifier";
$vocab["type_text"] = "Une ligne (text)";
$vocab["type_area"] = "Multi-lignes (textarea)";
$vocab["avertissement_suppression_champ_additionnel"] = "La suppression d'un champ additionnel est d�finitive et entra�ne la suppression des donn�es correspondantes dans les r�servations d�j� effectu�es.";
# Used in use_change_pwd.php // A traduire
$vocab["init"] = "R�initialisation du mot de passe";
$vocab["msg_init"] = "contrase�a olvidada ! ";
$vocab["user_mail"] = "Veuillez saisir votre Identifiant et votre adresse de messagerie, pour recevoir votre mot de passe :";
$vocab["adresse_mail1"] = "Votre adresse de messagerie ";
$vocab["adresse_mail2"] = "Votre adresse de messagerie (confirmation) ";
$vocab["error_mail"] = "Erreur de saisie de votre adresse de messagerie ";
$vocab["message_mail"] = "Votre mot de passe a �t� r�initialis�, veuillez en prendre note :   ";
$vocab["sujet_mail"] = "R�initialisation de votre mot de passe ! ";
$vocab["error_mail_empty"] = "Cette adresse n'existe pas dans la base ! ";
$vocab["msg_mail"] = "Votre mot de passe a �t� r�initialis� ! ";
?>