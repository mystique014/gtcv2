<?php
# GRR : fichier de langue espagnole
# Last modification : 10/07/2007


# Charset (jeu de caractères) utilisé dans l'en-tête des pages HTML
$charset_html = "iso-8859-1";

$vocab = array();

//
//
$vocab["deux_points"] = "&nbsp;: ";

# Used in admin_config_sso.php // A traduire
$vocab["admin_config_sso.php"]     = "Configuration SSO";
$vocab["Ne_pas_activer_Service_sso"] = "Ne pas activer la prise en compte d'un SSO.";
$vocab["config_cas_title"]           = "Consideración de un medio CAS (SSO)";
$vocab["CAS_SSO_explain"]            = "Si usted tiene a su disposición un contexto <b>CAS</b> (Central Authentification Service), usted puede activar la consideración de este servicio por <B>GRR</B> a continuación. Diríjase a la documentación de GRR para saber más.";
$vocab["Statut_par_defaut_utilisateurs_importes"] = "Estatus por defecto de los usuarios importados";
$vocab["choix_statut_CAS_SSO"]       = "Elija el estatuto que se asignará a las personas cuando se conectan a GRR por primera vez utilizando el servicio CAS. Usted podrá más tarde modificar este valor por cada usuario";
$vocab["Url_de_deconnexion"]         = "Url de Desconexión ";
$vocab["Url_de_deconnexion_explain"] = "Cuando un usuario se desconecta, el navegador va de nuevo hacia la página cuyo URL figura a continuación.";
$vocab["Url_de_deconnexion_explain2"] = "Si le champ est vide, selon la valeur du paramètre \$authentification_obli (fichier config.inc.php),l'utilisateur est dirigé soit vers la page d'accueil soit vers la page de déconnexion."; // A traduire
$vocab["admin_config_lemon.php"]     = "Configuración Lemonldap (SSO)";
$vocab["config_lemon_title"]         = "Consideración de un medio Lemonldap (SSO)";
$vocab["lemon_SSO_explain"]          = "Si usted tiene a su disposición un contexto <b>Lemonldap</b>, Usted puede activar la consideración de este servicio por <B>GRR</B> a continuación. Diríjase a la documentación de GRR para mayor información.";
$vocab["choix_statut_lemon_SSO"]     = "Elija el estatuto que se asignará a las personas cuando se conectan a GRR por primera vez utilizando al servicio Lemonldap. Usted podrá modificar más  adelante este valor para cada usuario";
$vocab["config_lcs_title"] = "Prise en compte d'un environnement SSO d'un serveur LCS";
$vocab["lcs_SSO_explain"] = "Si vous installez GRR sur un serveur <b>LCS</b>, vous pouvez activer ci-dessous la prise en compte du système d'authentification par <B>GRR</B>. Reportez-vous à la documentation de GRR pour en savoir plus.";
$vocab["choix_statut_lcs_SSO"] = "Activez la prise en compte du service <b>SSO</b> de LCS et choisissez ci-dessous le statut qui sera attribué aux personnes lorsqu'elles se connectent à GRR pour la première fois en utilisant le service CAS. Vous pourrez par la suite modifier cette valeur pour chaque utilisateur.";
$vocab["active_lcs"] = "Activez la prise en compte du service SSO de LCS";
$vocab["statut_eleve"] = "Statut des élèves";
$vocab["statut_non_eleve"] = "Statut des autres utilisateurs (non élève)";

# Used in admin_config_ldap.php
$vocab["admin_config_ldap.php"]      = "Configuración LDAP";

# Used in login.php
$vocab["msg_login1"]                  = "<b>GRR</b> es una aplicación PHP/MySql bajo licencia GPL, adaptada de <a href='http://mrbs.sourceforge.net'> MRBS</a>.<br>Para cualquier información relativa a <b>GRR</b>, ir a este sitio : ";
$vocab["msg_login3"]                  = "El sitio se halla momentaneamente inaccesible. ¡Le pedimos disculpas por este inconveniente!";
$vocab["autor_contact"]               = "Contactar al autor";
$vocab["pwd"]                         = "Contraseña";
$vocab["identification"]              = "Identificación";
$vocab["wrong_pwd"]                   = "Identificador o contraseña incorrecta";
$vocab["grr_version"]                 = "Versión ";
$vocab["importation_impossible"]      = "La identificación es correcta pero la importación del perfil es imposible. Por favor señale este problema al administrador GRR";
$vocab["echec_connexion_GRR"]         = "Falla de conexión a GRR.";
$vocab["causes_possibles"]            = "Posibles causas :";
$vocab["echec_authentification_ldap"] = "Error en la autentificación Idap ";
$vocab["authentification_CAS"]        = "Autentificarse en el Servicio Central de Autentificación (CAS SSO)";
$vocab["authentification_lcs"] = "S'authentifier directement à votre espace perso LCS"; // A traduire

# Used in logout.php
$vocab["msg_logout1"] = "Usted cerró su sesión GRR.";
$vocab["msg_logout2"] = "Su sesión GRR expiró, o fue desconectado,<br/>o no estaba conectado.";
$vocab["msg_logout3"] = "Abrir una sesión";

# Used in functions.inc
$vocab["welcome"]               = "Recepción";
$vocab["welcome_to"]            = "Bienvenida ";
$vocab["mrbs"]                  = "GRR (Gestión y Reserva de Recursos) ";
$vocab["report"]                = "Informe";
$vocab["admin"]                 = "Administración";
$vocab["help"]                  = "Ayuda";
$vocab["not_php3"]              = "<H1> ATENCIÓN : Esta aplicación puede funcionar mal con PHP3</H1>";
$vocab["connect"]               = "Conectarse";
$vocab["disconnect"]            = "Desconectarse";
$vocab["manage_my_account"]     = "Administrar mi cuenta";
$vocab["technical_contact"]     = "Contactar el soporte técnico";
$vocab["administrator_contact"] = "Contactar el administrador";
$vocab["subject_mail1"]         = "GRR : opinión ";
$vocab["subject_mail_creation"] = " - Nueva reservación";
$vocab["subject_mail_modify"]   = " - Modificación de una reservación";
$vocab["subject_mail_delete"]   = " - Supresión de una reservación";
$vocab["title_mail"]            = "Mensaje automático emitido por el GRR : ";
$vocab["the_user"]              = "El usuario ";
$vocab["creation_booking"]      = " reservó ";
$vocab["modify_booking"]        = " modificó la reservación de ";
$vocab["delete_booking"]        = " suprimió la reservación de ";
$vocab["the_room"]              = "el recurso : ";
$vocab["start_of_the_booking"]  = "Inicio de la  reservación : ";
$vocab["msg_no_email"]          = "Si usted no desea recibir estos mensajes automáticos, escriba al gestor de Grr : ";
$vocab["created_by"]            = " realizado por ";
$vocab["created_by_you"]        = " que realizó.";
$vocab["title_mail_creation"]   = "Se registró una reservacíon en GRR :\n";
$vocab["title_mail_modify"]     = "Se modificó una reservacíon en GRR :\n";
$vocab["title_mail_delete"]     = "Se suprimió una reservacíon en GRR :\n";
$vocab["user_name"]             = "Apellido y nombre del usuario :";
$vocab["email"]                 = "Email del usuario :";
$vocab["tentative_reservation_ressource_indisponible"] = "¡PROBLEMA : Usted intentó reservar un medio temporalmente indisponible!";
$vocab["suppression_automatique"] = "Le délai de confirmation de réservation a été dépassé.\nSuppression automatique de la réservation de "; // A traduire

# Used in day.php
$vocab["bookingsfor"]       = "Reservación para<br> ";
$vocab["bookingsforpost"]   = "";
$vocab["areas"]             = "Ámbitos&nbsp;:&nbsp;";
$vocab["daybefore"]         = "Ir al día anterior";
$vocab["dayafter"]          = "Ir al día siguiente";
$vocab["gototoday"]         = "Hoy";
$vocab["goto"]              = " Indicar";
$vocab["number_max"]        = " persona max.";
$vocab["number_max2"]       = " personas max.";
$vocab["one_connected"]     = " persona conectada";
$vocab["several_connected"] = " personas conectadas";
$vocab["week"]              = "Semana";
$vocab["month"]             = "Mes";
$vocab["ressource_temporairement_indisponible"] = "Temporalmente indisponible";
$vocab["fiche_ressource"]   = "Ficha de presentación del recurso";
$vocab["Configurer la ressource"] = "Configurar el recurso";
$vocab["cliquez_pour_effectuer_une_reservation"] = "Cliquez pour effectuer une réservation"; // A traduire
$vocab["reservation_impossible"] = "Réservation impossible"; // A traduire

# Used in trailer.inc
$vocab["viewday"]   = "Ver el día";
$vocab["viewweek"]  = "Ver la semana";
$vocab["viewmonth"] = "Ver el mes";
$vocab["ppreview"]  = "Formato imprimible";

# Used in edit_entry.php
$vocab["addentry"]                          = "Adicionar una reservación";
$vocab["editentry"]                         = "Modificar una  reservación";
$vocab["editseries"]                        = "Modificar una periodicidad";
$vocab["namebooker"]                        = "Breve descripción :";
$vocab["fulldescription"]                   = "Descripción completa: (Número de personas, etc.)";
$vocab["date"]                              = "Début de la réservation"; // A traduire
$vocab["fin_reservation"]                   = "Fin de la réservation"; // A traduire
$vocab["start_date"]                        = "Fecha de inicio :";
$vocab["end_date"]                          = "Fecha de fin :";
$vocab["time"]                              = "Hora&nbsp;:";
$vocab["duration"]                          = "Duración :";
$vocab["seconds"]                           = "segundo(s)";
$vocab["minutes"]                           = "minuto(s)";
$vocab["hours"]                             = "hora(s)";
$vocab["days"]                              = "día(s)";
$vocab["weeks"]                             = "semana(s)";
$vocab["years"]                             = "año(s)";
$vocab["all_day"]                           = "Todo el día";
$vocab["type"]                              = "Tipo :";
$vocab["save"]                              = "Registrar";
$vocab["rep_type"]                          = "Tipo de periodicidad : ";
$vocab["rep_type_0"]                        = "Ninguna";
$vocab["rep_type_1"]                        = "Cada día";
$vocab["rep_type_2"]                        = "Cada semana";
$vocab["rep_type_3"]                        = "Cada mes, la misma fecha";
$vocab["rep_type_4"]                        = "Cada año, la misma fecha";
$vocab["rep_type_5"]                        = "Cada mes, el mismo día en la semana";
$vocab["rep_type_6"]                        = "todas las n semanas";
$vocab["rep_end_date"]                      = "Fecha de fin de la periodicidad :";
$vocab["rep_rep_day"]                       = "Día :";
$vocab["rep_for_weekly"]                    = "(para una periodicidad semanal)";
$vocab["rep_freq"]                          = "Frecuencia :";
$vocab["rep_num_weeks"]                     = "Intervalo semanal";
$vocab["rep_for_nweekly"]                   = "(para n-semanas)";
$vocab["ctrl_click"]                        = "CTRL + clic en el ratón para seleccionar más de un recurso";
$vocab["entryid"]                           = "Reservacíon n° ";
$vocab["repeat_id"]                         = "periodicidad n° ";
$vocab["you_have_not_entered"]              = "No introdujo los datos ";
$vocab["valid_time_of_day"]                 = "una hora válida.";
$vocab["brief_description"]                 = "la descripción breve.";
$vocab["useful_n-weekly_value"]             = "un intervalo semanal válido.";
$vocab["no_compatibility_with_repeat_type"] = "El tipo de periodicidad que usted eligió es incompatible con la elección : día (para n-semanas).";
$vocab['choose_a_day'] = "Choisissez au moins un jour dans la semaine."; // A traduire
$vocab["no_compatibility_n-weekly_value"]   = "Usted no puede definir un intervalo semanal si elige este tipo de periodicidad.";
$vocab["message_records"]                   = "¡Se registraron las modificaciones!";
$vocab["click_here_for_series_open"]        = ">>>Clicar aquí para abrir las opciones de la periodicidad<<<";
$vocab["click_here_for_series_close"]       = ">>>Pulsar aquí para cerrar las opciones de la periodicidad<<<";
$vocab["choose_a_type"]                     = "Usted tiene que elegir un tipo de reservación.";
$vocab["every week"]                        = "cada semana";
$vocab["week 1/2 "]                         = "una semana sobre dos";
$vocab["week 1/3"]                          = "una semana sobre tres";
$vocab["week 1/4"]                          = "una semana sobre cuatro";
$vocab["week 1/5"]                          = "una semana sobre cinco";
$vocab["choose"]                            = "(Elija)";
$vocab["signaler_reservation_en_cours"]     = "Avisar que la reservación está en curso de utilización (reservado a los gestores del recurso)";
$vocab["reservation_en_cours"]              = "Reservación en curso de utilización.";
$vocab["une_seule_reservation_en_cours"]    = "Observación : para un recurso dado, se puede indicar una única reservación \"en curso de utilización\".";
$vocab["period"] = "Créneau :"; // A traduire
$vocab["periods"] = "créneau(x)"; // A traduire

# Used in view_entry.php
$vocab["description"]      = "Descripción :";
$vocab["room"]             = "Recurso :";
$vocab["createdby"]        = "Creado por :";
$vocab["lastupdate"]       = "Última actualización :";
$vocab["deleteentry"]      = "Suprimir una reservación";
$vocab["deleteseries"]     = "Suprimir una periodicidad";
$vocab["confirmdel"]       = "¿Está seguro/a\\nde querer suprimir\\nesta reserva?\\n\\n";
$vocab["returnprev"]       = "Regresar a la página anterior";
$vocab["invalid_entry_id"] = "n° de reservación no válida";
$vocab["reservation_a_confirmer_au_plus_tard_le"] = "Réservation à confirmer au plus tard le :"; // A traduire
$vocab["avertissement_reservation_a_confirmer"] = "(les réservations non confirmées sont automatiquement supprimées)"; // A traduire
$vocab["Reservation confirmee"] = "Réservation confirmée"; // A traduire
$vocab["confirmer reservation"] = "Cocher la case pour confirmer la réservation"; // A traduire

# Used in edit_entry_handler.php
$vocab["error"]                      = "Error";
$vocab["sched_conflict"]             = "Conflicto entre reservaciones ";
$vocab["conflict"]                   = "La nueva reservación entra en conflicto con la(s) reserva(s) siguiente(s) :";
$vocab["too_may_entrys"]             = "Las opciones elegidas crearán demasiadas reservaciones.<BR>¡Elija opciones diferentes!";
$vocab["returncal"]                  = "Retorno al calendario";
$vocab["failed_to_acquire"]          = "Error, imposible de obtener el acceso exclusivo a la base de datos";
$vocab["booking_in_past"]            = "Reservación anterior a la fecha actual";
$vocab["booking_in_past_explain"]    = "La fecha de inicio de la nueva reservación pasó. Quiere elegir una fecha de comienzo de reservación posterior a la fecha actual :";
$vocab["booking_in_past_explain_with_periodicity"] = "Una o màs reservaciones plantean un problema ya que se sitúan anteriormente.<br>Quiere elegir fechas de fin de reservación posteriores a la fecha actual :";
$vocab["error_delais_max_resa_room"] = "No se les autorizó a reservar este recurso tambíen a largo tiempo anticipado.";
$vocab["error_delais_min_resa_room"] = "No se les autorizó a reservar este recurso o a efectuar esta modificación : El término mínimo de reservación de este recurso pasó.";
$vocab["del_entry_in_conflict"]      = "Suprimir la(s) reservacion(es) anteriores con el fin de validar la nueva reservación.";
$vocab["error_date_confirm_reservation"] = "Vous devez choisir une date de confirmation inférieure à la date de début de réservation."; // A traduire

# Authentication stuff
$vocab["accessdenied"]               = "Acceso rechazado";
$vocab["norights"]                   = "Usted no tiene los derechos suficientes para efectuar esta operación.";
$vocab["nobookings"]                 = "¡No hay reservación posible para esta fecha!";
$vocab["msg_max_booking"]            = "El nombre máximo de reservaciones de este recurso por usuario se fijó a : ";
$vocab["accessdeniedtoomanybooking"] = "Acceso rechazado : ¡se rechaza su solicitud de reservación ya que superaría el máximo de reservaciones autorizado!";

# Used in search.php
$vocab["invalid_search"]  = "Búsqueda no válida.";
$vocab["search_results"]  = "Resultados de la búsqueda para :";
$vocab["nothing_found"]   = "Se encontró ninguna reservación.";
$vocab["records"]         = "Registros ";
$vocab["through"]         = " a ";
$vocab["of"]              = " sobre ";
$vocab["previous"]        = "Anterior";
$vocab["next"]            = "Siguiente";
$vocab["entry"]           = "Reservación";
$vocab["view"]            = "Ver";
$vocab["advanced_search"] = "Búsqueda avanzada";
$vocab["search_button"]   = "Búsqueda";
$vocab["search_for"]      = "Buscar";
$vocab["from"]            = "a partir de";
$vocab["dans les champs suivants : "] = "En los campos siguientes&nbsp;: ";

# Used in report.php
$vocab["report_on"]          = "Informe de las reservaciones :";
$vocab["report_start"]       = "Fecha de inicio del informe :";
$vocab["report_end"]         = "Fecha de fin del informe :";
$vocab["match_area"]         = "Ámbito :";
$vocab["match_room"]         = "Recurso :";
$vocab["match_entry"]        = "Breve descripción :";
$vocab["match_descr"]        = "Descripción completa&nbsp;";
$vocab["include"]            = "Incluir :";
$vocab["report_only"]        = "el informe solamente";
$vocab["summary_only"]       = "el resumen solamente";
$vocab["report_and_summary"] = "el informe y el resumen";
$vocab["summarize_by"]       = "Resumido por :";
$vocab["sum_by_descrip"]     = "Breve descripción";
$vocab["sum_by_creator"]     = "Creador";
$vocab["entry_found"]        = "reservación encontrada";
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
$vocab["allday"]            = "Día";

# Used in week_all.php
$vocab["all_rooms"]         = "Todos los recursos";

# Used in month.php
$vocab["monthbefore"]       = "Ver el mes anterior";
$vocab["monthafter"]        = "Ver el mes siguiente";

# Used in month_all.php
$vocab["all_areas"]         = "Todas las reservaciones";
$vocab["change_view"]       = "Cambiar el modo de visualización";

# Used in {day week month}.php
$vocab["no_rooms_for_area"] = "Ningún recurso se define para este ámbito.";

# Used in admin_room.php
$vocab["edit"]     = "Modificar";
$vocab["delete"]   = "Suprimir";
$vocab["rooms"]    = "Recursos";
$vocab["in"]       = "de :";
$vocab["noareas"]  = "No hay ámbito";
$vocab["addarea"]  = "Añadir un ámbito";
$vocab["name"]     = "Appellido";
$vocab["noarea"]   = "seleccionar en primer lugar un ámbito.";
$vocab["addroom"]  = "Adicionar un recurso";
$vocab["capacity"] = "Nombre máxims de personas máximas autorizadas en la sala (0 si no se trata de una sala)";
$vocab["norooms"]  = "Ningún recurso se creó para este ámbito.";
$vocab['access']   = "Acceso limitado";
$vocab["edittype"]    = "Types de réservations"; // A traduire

# Used in admin_type_modify.php
$vocab["admin_type_modify_create.php"] = "Ajout d'un nouveau type de réservation"; // A traduire
$vocab["admin_type_modify_modify.php"] = "Modification d'un type de réservation"; // A traduire
$vocab["msg_type_created"]             = "Vous venez de créer un nouveau type de réservation."; // A traduire
$vocab["update_type_failed"]  = "La mise à jour du type de réservation a échoué : "; // A traduire
$vocab["explications_active_type"] = "<b>Remarque : </b>quand un type est décoché, celui-ci n'est plus proposé à l'utilisateur effectuant ou modifiant une réservation, mais les réservations déjà effectuées continuent d'apparaître sur les plannings avec ce type, bien que désactivé."; // A traduire

# Used in admin_type.php  // A traduire
$vocab["admin_type.php"] = "Types de réservation";
$vocab["display_add_type"]          = "Ajouter un type de réservation";
$vocab["type_num"]                  = "Identifiant";
$vocab["type_name"]                 = "Nom du type";
$vocab["type_order"]                = "Ordre d'affichage";
$vocab["type_color"]                = "Couleur";
$vocab["type_valide_domaine"]      = "Type valide pour le domaine";
$vocab["admin_type_explications"]  = "Par défaut, lors de la création d'un nouveau type de réservations, celui-ci est commun à tous les domaines. Vous pouvez ensuite spécifier, pour chaque domaine, les types valides ou non.";

# Used in admin_edit_room.php
$vocab["editarea"]                      = "Modificar el ámbito";
$vocab["editroom"]                      = "Modificar el recurso";
$vocab["update_room_failed"]            = "La actualización del recurso falló : ";
$vocab["error_room"]                    = "Error : recurso ";
$vocab["not_found"]                     = " no encontrado";
$vocab["update_area_failed"]            = "La actualización del ámbito falló : ";
$vocab["error_area"]                    = "Error: ámbito ";
$vocab["order_display"]                 = "Orden de visualización";
$vocab["max_booking"]                   = "Nombre máx. de reservaciones por usuario (-1 si no hay restricción)";
$vocab["explain_max_booking"]           = "La restricción no se aplica a los gestores del recurso.";
$vocab["declarer_ressource_indisponible"] = "Declarar este recurso temporalmente indisponible. Entonces las reservaciones son imposibles.";
$vocab["montrer_fiche_présentation_ressource"] = "Volver visible la fecha de presentación del recurso en el interfaz público.";
$vocab["choisir_image_ressource"]       = "Elegir una imagen del resurso para la ficha de presentación (png, jpg et gif solamante)";
$vocab["supprimer_image_ressource"]     = "Suprimir la imagen actual del rescurso.";
$vocab["description complète"]          = "Descripción completa (visible en la ficha de presentación) - Usted puede utilizar balizas HTML.";
$vocab['ip_adr']                        = "Dirección IP cliente";
$vocab['ip_adr_explain']                = "<b>Observación sobre la dirección IP cliente : </b>
<br>Si la dirección IP de la máquina cliente es idéntica a esta dirección, este ámbito se convierte en el ámbito por defecto.
<br>Se supone por otra parte que :
<br>1) el administrador no definió un ámbito por defecto en la página de configuración general,
<br>2) el usuario no definió su propio ámbito por defecto en la página de gestión de su cuenta.";
$vocab["morningstarts_area"]            = "Hora de comienzo de día";
$vocab["eveningends_area"]              = "Hora de fin de día (superior a la hora de comienzo de día)";
$vocab["resolution_area"]               = "El más pequeño bloque reservable, en segundos (1800 segundos";
$vocab["eveningends_minutes_area"]      = "Nombre de minutos a adicionar a la hora de fin de día para tener el fin real de un día.";
$vocab["weekstarts_area"]               = "Inicio de la semana";
$vocab["twentyfourhour_format_area"]    = "Formato de visualización del tiempo";
$vocab["twentyfourhour_format_12"]      = "Visualización 12h.";
$vocab["twentyfourhour_format_24"]      = "Visualización 24h.";
$vocab["configuration_plages_horaires"] = "Configuración de la visualización de las planificaciones de los recursos de este ámbito";
$vocab["delais_max_resa_room"]          = "Nombre de días máximos más allá de los cuales el usuario no puede reservar o modificar una reservación
(-1 si no hay restricción).
<br><b>Ejemplo</b> : un valor igual a 30 significa que un usuario no puede reservar un recurso sino 30 días de antemano al máximo.
<br><i>Esta limitación no afecta a los gestores del recurso así como a los administradores del ámbito.</i>";
$vocab["delais_max_resa_room_2"]        = "<b>Nombre de días máximos</b> más allá de los cuales el usuario no puede reservar o modificar una reservación :";
$vocab["delais_min_resa_room"]          = "Tiempo <b>en minutos<b/> por debajo del cual el usuario no puede reservar o modificar une reservación (0 si no hay restricción).
<br><b>Ejemplo</b> : un valor igual a 60 significa que un usuario no puede reservar un recurso o modificar una reservación menos de 60 minutos antes del comienzo de la reservación.
<br><i>Esta limitación no afecta a los gestores del recurso así como a los administradores del ámbito.</i>";
$vocab["delais_min_resa_room_2"]        = "<b>Tiempo en minutos</b> por abajo del cual el usuario no puede reservar o modificar una reservación :";
$vocab["allow_action_in_past"]          = "Permetir las reservaciones anteriormente asi como las modificaciones/supresiones de reservaciones pasadas.";
$vocab["allow_action_in_past_explain"]  = "Si la casilla no tiene un cruz, un usuario (ni un gestor incluso o un administrador) no puede efectuar una reservación anteriormente, ni modificar o suprimir una reservación pasada. Solo el administrador general tiene esta posibilidad.";
$vocab["avertissement_change_type"] = "ATTENTION : les deux types de configuration des créneaux sont incompatibles entre eux : un changement du type de créneaux entraîne donc, après validation, un effacement de toutes les réservations de ce domaine."; // A traduire
$vocab["intitule_creneau"] = "Intitulé du créneau n° "; // A traduire
$vocab["nombre_de_creneaux"] = "Nombre de créneaux"; // A traduire
$vocab["creneaux_de_reservation_temps"] = "Les créneaux de réservation sont basés sur le temps."; // A traduire
$vocab["creneaux_de_reservation_pre_definis"] = "Les créneaux de réservation sont basés sur des intitulés pré-définis."; // A traduire
$vocab["msg_option_de_reservation"] = "<b>Poser des réservations \"sous réserve\"</b> : indiquer une valeur différente de 0 pour activer cette fonctionnalité.<br>La valeur ci-contre désigne le nombre maximal de jours pour confirmer une réservation"; // A traduire
$vocab["type_affichage_reservation"] = "Pour une nouvelle réservation ou modification d'une réservation, l'utilisateur spécifie"; // A traduire
$vocab["affichage_reservation_duree"] = " date/heure de début de réservation et <b>durée de la réservation</b>"; // A traduire
$vocab["affichage_reservation_date_heure"] = " date/heure de début de réservation et <b>date/heure de fin de réservation</b>"; // A traduire
$vocab["cocher_jours_a_afficher"] = "Cochez ci-dessous les jours à afficher sur les différents plannings.<br><b>Remarque </b>: si vous décidez de ne pas afficher certains jours, veillez également à désactiver ces jours dans le \"Calendrier hors réservation\"."; // A traduire

# Used in admin_room_del.php
$vocab["deletefollowing"] = "Usted va suprimir las reservaciones siguientes";
$vocab["sure"]            = "¿Está usted seguro/a?";
$vocab["YES"]             = "SI";
$vocab["NO"]              = "NO";
$vocab["delarea"]         = "Debe suprimir todos los recursos de este ámbito antes de poder suprimir lo<p>";

# Used in help.php
$vocab["about_mrbs"]        = "A propósito de GRR (Gestión y Reservación de Recursos)";
$vocab["database"]          = "Base de datos : ";
$vocab["system"]            = "Sistema de explotación : ";
$vocab["please_contact"]    = "Contactar ";
$vocab["for_any_questions"] = "si usted tiene una cuestión que no se trata aquí.";

# Used in mysql.inc AND pgsql.inc
$vocab["failed_connect_db"] = "Grave error: Derrota de la conexión a la base de datos ";

# Used in admin_room.php
$vocab["admin_room.php"]          = "Ámbitos y recursos";
$vocab["admin_user.php"]          = "Usuarios";
$vocab["admin_right.php"]         = "Gestión des los recursos por los usuarios";
$vocab["admin_right_admin.php"]   = "Administración de los ámbitos por los usuarios";
$vocab["admin_access_area.php"]   = "Acceso a los ámbitos limitados";
$vocab["admin_email_manager.php"] = "Emails automáticos";
$vocab["admin_config.php"]        = "Configuración general";
$vocab["admin_calend.php"]        = "Reservación días enteros";
$vocab["admin_overload.php"] = "Champs additionnels"; // A traduire
$vocab["admin_menu_general"] = "Général"; // A traduire
$vocab["admin_menu_arearoom"] = "Domaines et ressources"; // A traduire
$vocab["admin_menu_user"] = "Utilisateurs et accès"; // A traduire
$vocab["admin_menu_various"] = "Divers"; // A traduire
$vocab["admin_menu_auth"] = "Authentification et ldap"; // A traduire

# Used in admin_right_admin.php
$vocab["admin_right_admin_explain"] = "Además de sus derechos normales, el administrador de un ámbito tiene la posibilidad de administrar enteramente un ámbito : creación, supreción de un recurso, añadido y supresión de los gestores de resevaciones, gestión de los emails automáticos.";
$vocab["administration_domaine"]    = "Administración del ámbito :";
$vocab["no_admin_this_area"]        = "!Actualmente nadie, excepto los administradores, está autorizado a administrar el ámbito que aparece al lado!";
$vocab["user_admin_area_list"]      = "Lista de las personas autorizadas a administrar el ámbito que aparece al lado (excepto los administradores) :";

# Used in admin_right.php
$vocab["select"]              = "(Seleccione)";
$vocab["select_all"]          = "(todas)";
$vocab["no_area"]             = "Nigún ámbito fue seleccionado.";
$vocab["no_restricted_area"]  = "Nigún ámbito con acceso limitado se definió.";
$vocab["administration1"]     = "Gestión  del recurso :";
$vocab["administration2"]     = "Gestión de los recursos siguientes :";
$vocab["user_list"]           = "Lista de las personas autorizadas a administrar el/los recurso(s) que aparece(n) al lado (excepto los administradores) :";
$vocab["add_user_to_list"]    = "Añadir un usuario a la lista :";
$vocab["no_admin"]            = "¡Actualmente nadie, excepto los administradores, tiene acceso  a este ámbito!";
$vocab["no_admin_all"]        = "¡Actualmente nadie, excepto los administradores, está autorizado a administrar el conjunto de los recursos al lado!";
$vocab["warning_exist"]       = "¡El usuario que intentó añadir ya pertenece a la lista!";
$vocab["nobody"]              = "(ninguno)";
$vocab["admin_right_explain"] = "Además de sus derechos normales, el gestor de un recurso tiene la posibilidad de modificar o de suprimir cualquier reservación de este recurso. Además las restricciones relativas a las reservaciones maximales del recurso no se aplican.";

# Used in admin_access_area.php
$vocab["user_area_list"] = "Lista de las personas que tienen acceso a este ámbito (excepto los administradores) :";
$vocab["no_user_area"]   = "!Actualmente nadie, excepto los administradores, tiene acceso a este ámbito!";

# Used in admin_email_manager.php
$vocab["no_room"]                 = "Ningún recurso fue seleccionado en este ámbito.";
$vocab["mail_user_list"]          = "Lista de los usuarios avisados por mail :";
$vocab["no_mail_user_list"]       = "!Actualmente no hay usario en la lista!";
$vocab["explain_automatic_mail2"] = "Por otra parte, cuando un usuario <b>reserva un recurso</b>, <b>modifica</b> o <b>suprime<b> una reservación, algunos usuarios pueden ser avisados por e-mail. Para cada recurso, usted puede designar a uno o más usuarios a avisar :";
$vocab["explain_automatic_mail3"] = "Cuando un usuario <b>modifica</b> o <b>suprime<b> una reservación efectuada por un otro usuario, este último (si el campo email fue indicado) es avisado automáticamente por un mensaje email. ";
$vocab["add_user_succeed"]        = "Un usuario fue añadido a la lista.";
$vocab["del_user_succeed"]        = "Un usuario fue suprimido a la lista.";
$vocab["attention_mail_automatique_désactive"] = "ATENCIÓN : se desactiva el envío de mails automáticos. Para activarlo, encuentrese en el panel de configuración general.";

# Used in admin_user_modify.php
$vocab["admin_user_modify_create.php"] = "Adición de un nuevo usuario";
$vocab["admin_user_modify_modify.php"] = "Modificación de un usuario existente";
$vocab["please_enter_name"]            = "¡Quiere introducir un apellido y un nombre para el usuario!";
$vocab["error_exist_login"]            = "***¡Atención! Ya existe un usuario que utiliza la misma clave de acceso . ¡Registro imposible! ***";
$vocab["msg_login_created"]            = "Acabe usted de crear un nuevo usuario. Por defecto, este usuario es considerado como activo.";
$vocab["msg_login_created_error"]      = "¡Ocurrió un problema en el momento de la creación de un nuevo usuario! Por favor contacte el soporte técnico.";
$vocab["message_records_error"]        = "Error durante el registro de los datos";
$vocab["only_letters_and_numbers"]     = "¡El identificador del usario debe estar constituido solamente con letras y cifras!";
$vocab["change_pwd"]                   = "Cambiar de contraseña";
$vocab["activ_no_activ"]               = "Estado";
$vocab["activ_user"]                   = "Activo";
$vocab["no_activ_user"]                = "No activo";
$vocab["required"]                     = "Los campos señalados de un * son obligatorios.";
$vocab["statut_user"]                  = "Usuario";
$vocab["statut_visitor"]               = "Visitante";
$vocab["statut_administrator"]         = "Administrador";
$vocab["mail_user"]                    = "Email";
$vocab["pwd_toot_short"]               = "Contraseña  (".$pass_leng." caracteres mínimos)";
$vocab["confirm_pwd"]                  = "Contraseña  (a confirmar)";
$vocab["Changer_source_utilisateur_local"] = "Marcar la casilla al lado para que la autentificación sea local. (da la autentificación externa como que Idap, CAS SSO, o Lemonldap definitivamente imposible). ¡Procedimiento irreversible!";
$vocab["administrateur du domaine"] = "Administrateur du domaine"; // A traduire
$vocab["gestionnaire des resources suivantes"] = "Gestionnaire des resource(s) suivante(s) :"; // A traduire
$vocab["liste_privileges"] = "Liste des privilèges  de l'utilisateur "; // A traduire
$vocab["pas de privileges"] = "Pas de privilèges"; // A traduire
$vocab["a acces au domaine"] = "Cet utilisateur a accès à ce domaine restreint."; // A traduire
$vocab["est prevenu par mail"] = "Cet utilisateur est averti automatiquement par mail pour le(s) ressource(s) suivante(s)."; // A traduire
$vocab["administrateur general"] = "Administrateur général"; // A traduire

# Used in admin_user.php
$vocab["display_add_user"]          = "Añadir un usuario";
$vocab["display_add_user_list_csv"] = "Importar dans GRR un fichero de usuarios (formato CSV)";
$vocab["display_all_user.php"]      = "Indicar : todos los usarios";
$vocab["display_user_on.php"]       = "los usuarios activos";
$vocab["display_user_off.php"]      = "los usuarios inactivos";
$vocab["OK"]                        = "Validar";
$vocab["statut"]                    = "Estatuto";
$vocab["login_name"]                = "Nombre de la clave de acceso";
$vocab["names"]                     = "Apellido y nombre";
$vocab["authentification"]          = "Autentificación :";
$vocab["Externe"]                   = "Externo";
$vocab["privileges"] = "privilèges"; // A traduire
$vocab["maj_base_locale"] = "Nettoyage de la base locale"; // A traduire
$vocab["mess_maj_base_locale"] = "Recherche et suppression de la base locale de GRR, des utilisateurs LCS qui ne sont plus présents dans la base LCS. Cliquez sur OK pour continuer."; // A traduire
$vocab["mess2_maj_base_locale"] = "Les utilisateurs suivants ont été supprimés de la base locale car ils n'étaient plus présents dans la base LCS :"; // A traduire
$vocab["mess3_maj_base_locale"] = "Il n'y a aucun utilisateur à supprimer de la base locale."; // A traduire
$vocab["synchro_base_locale"] = "Mise à jour de la base locale"; // A traduire
$vocab["mess_synchro_base_locale"] = "Mise à jour des utilisateurs de la base locale de GRR à partir des informations de la base LCS et insertion dans la base locale de GRR des utilisateurs LCS qui ne sont pas présents localement. Cliquez sur OK pour continuer (cette opération peut être longue !)."; // A traduire
$vocab["liste_nouveaux_utilisateurs"] = "Liste des nouveaux utilisateurs inséré dans la base locale : "; // A traduire
$vocab["liste_pb_insertion"] = "Problème : les utilisateurs suivants n'ont pas pu être insérés dans la base locale : "; // A traduire
$vocab["liste_utilisateurs_modifie"] =  "Liste des utilisateurs mis à jour dans la base locale : "; // A traduire
$vocab["liste_pb_update"] =  "Problème : les utilisateurs suivants n'ont pas pu être mis à jour dans la base locale : "; // A traduire

# Used in admin_config.php
$vocab["title_disable_login"]            = "Activación/desactivación de las conexiones";
$vocab["explain_disable_login"]          = "Desactivando las conexiones, usted hace que la conexión al sitio para los usuarios sea imposible, excepto para los administradores. Además, los usuarios actualmente conectados son desconectados automáticamente.
<br>Sin embargo, si la conexión no es obligatoria para el acceso al sitio en visualización, este acceso sigue siendo posible.";
$vocab["disable_login_on"]               = "Desactivar las conexiones";
$vocab["disable_login_off"]              = "Activar las conexiones";
$vocab["submit"]                         = "Enviar";
$vocab["miscellaneous"]                  = "Informaciones diversas";
$vocab["title_home_page"]                = "Título de la página de conexión";
$vocab["message_home_page"]              = "Mensaje de advertencia de la página de conexión";
$vocab["company"]                        = "Nombre del establecimiento";
$vocab["webmaster_name"]                 = "Apellido y nombre del gestor del sitio";
$vocab["webmaster_email"]                = "Dirección email del gestor del sitio";
$vocab["technical_support_email"]        = "Dirección email del soporte técnico";
$vocab["grr_url"]                        = "Dirección del sitio";
$vocab["title_session_max_length"]       = "Duración máxima de una sesión";
$vocab["session_max_length"]             = "Duración máxima de inactividad (en minutos) :";
$vocab["explain_session_max_length"]     = "Esta duración indica el tiempo máximo de inactividad al final del cual un usuario está desconectado automáticamente.";
$vocab["title_automatic_mail"]           = "Envío de mails automáticos";
$vocab["warning_message_mail"]           = "Algunos alojamientos desactivan el envío automático de mails desde sus servidores. En este caso, la funcionalidad siguiente no funcionará.";
$vocab["explain_automatic_mail"]         = "Cuando un usuario modifica o suprime una reservación efectuada por otro usuario, este último (si el campo email se registró) es informado automáticamente por un mensaje email.<br>Cuando un usuario reserva un recurso, modifica o suprime una reservación, algunos usuarios designados por el administrador (en la rúbrica de gestión de los mails automáticos) pueden ser avisados por emails. ¿Desea usted utilizar esta opción?";
$vocab["mail_admin_on"]                  = "Avisar por mail a los usuarios";
$vocab["mail_admin_off"]                 = "No prevenir por mail";
$vocab["title_backup"]                   = "Protección de la base GRR";
$vocab["explain_backup"]                 = "Creación de un fichero de protección completo de la base GRR. En caso de problema sobre la base GRR, utilizar una herramienta como PHPMYADMIN para restaurar la base.";
$vocab["warning_message_backup"]         = "¡Si un mensaje del tipo \"Tiempo máximo de ejecución excedido\" aparece unos momentos después de haber iniciado la copia de seguridad, eso significa que la creación de la copia de seguridad falló!";
$vocab["submit_backup"]                  = "Proceder a efectuar una copia de seguridad";
$vocab["title_begin_end_bookings"]       = "Inicio y fin de las reservaciones";
$vocab["begin_bookings"]                 = "Fecha de inicio de las reservaciones : ";
$vocab["begin_bookings_explain"]         = "No hay reservación posible antes de esta fecha. Además las reservaciones ya efectuadas antes de esta fecha se borrarán de la base.";
$vocab["end_bookings"]                   = "Fecha de fin de las reservaciones : ";
$vocab["end_bookings_explain"]           = "No hay reservación posible tras esta fecha. Además las reservaciones ya efectuadas tras esta fecha se borrarán de la base.";
$vocab["default_parameter_values_title"] = "Elección de los parámetros de visualización por defecto";
$vocab["default_room_all"]               = "Todos los recursos (visualización 'día')";
$vocab["default_room_week_all"]          = "Todos los recursos (visualización 'semana')";
$vocab["default_room_month_all"]          = "Toutes les ressources (affichage 'mois')"; // A traduire
$vocab["default_room_month_all_bis"]          = "Toutes les ressources (affichage 'mois' bis)"; // A traduire
$vocab["display_week"]                   = "(visualización 'semana')";
$vocab["default_area"]                   = "Ámbito por defecto : ";
$vocab["default_room"]                   = "Recursos(s) indicado(s) : ";
$vocab["choose_an_area"]                 = "(Elija un ámbito)";
$vocab["explain_default_parameter"]      = "Elija en esta rúbrica, la visualización por defecto de la página inicial. Son los parámetros que se aplicarán si el propio usuario no eligió parámetros de visualización por defecto.";
$vocab["restricted"]                     = "limitado";
$vocab["explain_area_list_format"]       = "Tipo de visualización de las listas de los ámbitos y recursos ";
$vocab["liste_area_list_format"]         = "Visualización en forma de una arborescencia";
$vocab["select_area_list_format"]        = "Visualización en forma de una lista desplegable";
$vocab["explain_default_area_and_room"]  = "Ámbito y recurso por defecto";
$vocab["explain_css"]                    = "Elección del estilo/tema";
$vocab["choose_css"]                     = "Elija en la lista que aparece al lado : ";
$vocab["reset"]                          = "Valores por defecto";
$vocab["choose_language"]                = "Elección de la lengua por defecto";
$vocab["mess_avertissement_config"] = "Remarque : en plus des paramètres ci-dessous, d'autres paramètres de configuration de GRR sont à votre disposition dans le fichier « config.inc.php » (Consulter la documentation de GRR)."; // A traduire

# Used in admin_change_pwd.php.php
$vocab["passwd_error"]       = "¡Error en la introducción de la contraseña, inténtelo de nuevo!";
$vocab["update_pwd_failed"]  = "¡Error en la actualización de la contraseña!";
$vocab["update_pwd_succeed"] = "¡La contraseña ha sido cambiada!";
$vocab["back"]               = "Retorno";
$vocab["pwd_change"]         = "Cambio de la contraseña";
$vocab["login"]              = "Identificador";
$vocab["last_name"]          = "Apellido";
$vocab["first_name"]         = "Nombre";
$vocab["pwd_msg_warning"]    = "Atención : la contraseña debe implicar ".$pass_leng." caracteres mínimos (letras y cifras,...). Se aconseja de no elegir una contraseña muy simplista.";
$vocab["new_pwd1"]           = "Nueva contraseña (".$pass_leng." caracteres minimos)";
$vocab["new_pwd2"]           = "Nueva contraseña (à confirmar)";
$vocab["pwd_msg_warning2"]   = "¡Por razones de seguridad, por favor utilice el módulo \"Administrar mi cuenta\" accesible a partir de la página inicial para cambiar su contraseña!";

# Used in my_account.php
$vocab["wrong_pwd2"]               = "¡Error en la introducción de la contraseña, por favor vuelva a empezar!";
$vocab["wrong_old_pwd"]            = "¡La antigua contraseña no es correcta!";
$vocab["update_email_succeed"]     = "¡Se modificó la dirección email!";
$vocab["old_pwd"]                  = "Antigua contraseña";
$vocab["click_here_to_modify_pwd"] = ">>>Clicar aquí para modificar su contraseña<<<";

# Used in admin_import_users_csv.php
$vocab["admin_import_users_csv0"]  = "Fichero CSV a importar : ";
$vocab["admin_import_users_csv1"]  = "El fichero a importar contiene una primera línea de encabezamiento, a ignorar ";
$vocab["admin_import_users_csv2"]  = "<p>El fichero de importación debe ser al formato csv (separador : punto y coma)
<br>El fichero debe contener los distintos campos siguiente :<br>
--> <B>Identificador</B> : el identificador del usuario<br>
--> <B>Apellido</B><br>
--> <B>Nombre</B><br>
--> <B>contraseña</B><br>
--> <B>Dirección e-mail</B><br>";
$vocab["admin_import_users_csv3"]  = "<p><b>ALGUNAS PRECISIONES :</b><br><br>
<b>identifidor</b><br>20 caracteres como máximo. Se puede tratar de cualquier secuencia de caracteres y/o de cifras sin espacio.
El carácter _ es también autorizado. Si este formato no se respeta, la consecuencia de carácter ??? aparece al lugar del identificador.
Los identificadores que aparecen en rojo corresponden a apellidos de usuarios ya existentes en la base GRR.
¡Entonces los datos existentes serán aplastados por los datos presentes en el fichero importado!<br><br>
<b>Nom</b><br>Apellido del usuario. 30 caracteres máximos.
Puede tratarse de cualquier consecuencia de caracteres y/o de cifras con eventualmente espacios y apóstrofes.<br><br>
<b>Nombre</b><br>Nombre del usuario. Misma observación que para el apellido.
Los apellidos y los nombres que aparecen en azul coresponden a usuarios que existen en la base GRR y que llevan los mismos apellidos y nombres.<br><br>
<b>Contraseña</b><br>".$pass_leng." caracteres mínimos y 30 caracateres máximos.<br><br><b>Dirección e-mail</b>
<br>100 caracteres como máximo. Poner el símbolo \"-\"si no hay una dirección e-mail<br><br></p>";
$vocab["admin_import_users_csv4"]  = "Imposible de abrir el fichero CSV";
$vocab["admin_import_users_csv5"]  = "Primera fase de la importación : ";
$vocab["admin_import_users_csv6"]  = "entradas detectadas!";
$vocab["admin_import_users_csv7"]  = "<p><b>ADVERTENCIA</b> : las claves de acceso que parecen en rojo corresponden a claves de acceso que existen ya en la base GRR. ¡Por lo tanto, los datos existentes serán aplastados por los datos presentes en el fichero que va a importarse!</p>";
$vocab["admin_import_users_csv8"]  = "<p><b>ATENCIÓN</b> : los apellidos y nombres que parecen en azul corresponden a usuarios ya que existen en la base GRR y que llevan los mismos apellidos y nombres.</p>";
$vocab["admin_import_users_csv9"]  = "<p><b>ADVERTENCIA</b> : los símbolos <b>???</b> significan que el campo en cuestión no es válido. <b>La operación de importación de los datos no puede continuar normalmente.</b> Por favor corrija el fichero a importar.<br></p>";
$vocab["admin_import_users_csv10"] = "¡La importación falló!";
$vocab["admin_import_users_csv11"] = "¡Ningún fichero se seleccionó!";
$vocab["admin_import_users_csv12"] = "¡Se creó el usuario!";

# Used in admin_calend_ignore.php  // A traduire
$vocab["admin_calend_ignore.php"]     = "Calendrier hors réservation";
$vocab["calendrier_des_jours_hors_reservation"] = "Calendrier des journées hors réservation";
$vocab["les_journees_cochees_sont_ignorees"] = "Les journées cochées correspondent à des journées pendant lesquelles il n'est pas possible de réserver.<br>En ce qui concerne les réservations avec périodicité, ces journées sont ignorées lors de la validation de la réservation.<br><br><b>Attention </b> : si des réservations ont déjà été enregistrées sur les journées cochées, celles-ci seront <b>automatiquement et irrémédiablement supprimées</b>. De plus, les personnes concernées par les suppressions ne seront pas prévenues par email.";

# Used in admin_calend.php
$vocab["check_all_the"]                = "Marcar todos los ";
$vocab["uncheck_all_the"]              = "Demarcar todos los ";
$vocab["uncheck_all_"]                 = "Demarcar todo";
$vocab["admin_calendar_title.php"]     = "Reservación/Supresión en bloque de días enteros";
$vocab["admin_calendar_explain_1.php"] = "este procedimiento le permite de <b>reservar</b> o de </b>liberar</b> muy rápidamente
días enteros simultáneamente sobre varios recursos de varios ámbitos y según un calendario.
<br><br><b>Ejemplo :</b> Puede así bloquear al año algunos días como el fin de semana, las vacaciones, los días de fiesta...
<br><b>Atención :</b> si hay un conflicto con reservaciones existentes, estas serán <b>automáticamente e irremediablemente suprimidas</b> en favor
de la nueva reservación. Además, las personas afectadas por las supresiones no serán avisadas por email.
<br><br>Este procedimiento se desarrolla en tres etapas :
<ul>
<li>Elección de los ámbitod y del tipo de acción</li>
<li>Elección de los recursos que deben reservarse, nombre, descripción y tipo de las reservaciones</li>
<li>Elección de las próximas fechas sobre un calendario
</ul>
<b>Las modificaciones efectivas de las reservaciones solo se producen después de la tercera etapa.</b>";
$vocab["etape_n"]                      = "Etapa n° ";
$vocab["choix_domaines"]               = "Elija los ámbitos a los cuales se referirán las reservaciones o las supresiones de las reservaciones :";
$vocab["choix_action"]                 = "Elija el tipo de acción :";
$vocab["choose_a_room"]                = "Debe elegir al menos un recurso.";
$vocab["reservation_en_bloc"]          = "Reservación de días enteros";
$vocab["reservation_en_bloc_result"]   = "Se termina el procedimiento de reservación.";
$vocab["reservation_en_bloc_result2"]  = "<b>Reservaciones que entraban en conflicto con las nuevas reservaciones fueron suprimidas al nombre de : </b>";
$vocab["suppression_en_bloc"]          = "Supresión de reservaciones sobre días enteros";
$vocab["suppression_en_bloc_result"]   = "Se termina el procedimiento de supresión de reservaciones. <br><b>Nombre de supresiones efectuadas : </b>";

# Used in admin_confirm_change_date_bookings.php
$vocab["cancel"]                                 = "Anular";
$vocab["admin_confirm_change_date_bookings.php"] = "Confirmación de cambio de las fechas de comienzo y fin de las reservaciones";
$vocab["msg_del_bookings"]                       = "¡Atención, los cambios de las fechas de comienzo y de fin de las reservaciones que usted efectuó van a ocasionar la supresión definitiva de las reservaciones ya efectuadas fuera de las nuevas fechas autorizadas!<br><br>¿Quiere usted continuar?";

# Used in admin_view_connexions.php
$vocab["admin_view_connexions.php"] = "Seguimiento de las conexiones";
$vocab["users_connected"]           = "Usuarios actualmente conectados";
$vocab["sen_a_mail"]                = "Enviar un mail";
$vocab["deconnect_changing_pwd"]    = "Desconectar cambiando la contraseña";
$vocab["log"]                       = "Diario de las conexiones desde el ";
$vocab["msg_explain_log"]           = "Las fechas que aparecen en rojo señalan a los usuarios desconectados automáticamente después de un largo plazo de inactividad.<br>Las líneas que aparecen en verde señalan a los usuarios actualmente conectados.";
$vocab["begining_of_session"]       = "Inicio de sesión";
$vocab["end_of_session"]            = "Fin de sesión";
$vocab["ip_adress"]                 = "Dirección IP";
$vocab["navigator"]                 = "Navegador";
$vocab["referer"]                   = "Procedencia";
$vocab["start_history"]             = "Inicio del recuento histórico";
$vocab["erase_log"]                 = "Las entradas anteriores del diario desepareceran a la fecha de debajo.";
$vocab["cleaning_log"]              = "Limpieza del diario";
$vocab["logs_number"]               = "Nombre de entradas actualmente presentes en el diario de conexión : ";
$vocab["older_date_log"]            = "Actualmente, el diario contiene los antecedentes de las conexiones desde el ";
$vocab["delete_up_to"]              = "Borrar hasta el";

# Used for functions.js
$vocab["confirm_del"] = "Confirmar la supresión";

# Used in mincals.inc
$vocab["see_month_for_this_room"]         = "Ver las reservaciones del mes para este recurso";
$vocab["see_week_for_this_room"]          = "Ver las reservaciones de la semana para este recurso";
$vocab["see_all_the_rooms_for_the_day"]   = "Ver todos los recursos del ámbito para este día";
$vocab["see_all_the_rooms_for_the_month"] = "Ver las reservaciones del mes para todos los recursos";
$vocab["see_week_for_this_area"]          = "Ver las reservaciones de la semana para todos los recursos";

# Used in admin_maj.inc
$vocab["admin_maj.php"]              = "Número de versión y actualización";
$vocab["num_version_title"]          = "Número de versión de GRR";
$vocab["num_version"]                = "Número de versión: <b>GRR";
$vocab["maj_bdd"]                    = "Actualización de la base de datos (acceso administrador)";
$vocab["maj_bdd_not_update"]         = "ATENCIÓN: Su base de datos no parece estar al día.";
$vocab["maj_version_bdd"]            = "Número de versión de la base de datos : GRR";
$vocab["maj_do_update"]              = "Clicar el siguiente botón para efectuar la actualización hacia la versión GRR";
$vocab["maj_submit_update"]          = "Poner al día";
$vocab["maj_no_update_to_do"]        = "Usted no tiene de actualización de la base de datos que efectuar.";
$vocab["maj_go_www"]                 = "Encuentrese en el lugar de GRR para conocer la última versión : ";
$vocab["maj_good"]                   = "Actualización efectuada. (lea atentamente el resultado de la actualización, en la parte inferior de la página)";
$vocab["please_go_to_admin_maj.php"] = "Se aconseja mucho poner al día su base encontrandose en la parte GESTIÓN.";

# Used in admin_maj.inc
$vocab["capacity_2"]              = "Capacidad : ";
$vocab["Pas d'image disponible"]  = "No hay imagen disponible";
$vocab["Image de la ressource"]   = "Imagen del recurso&nbsp;";

# Used in csv.php
$vocab["enrecherchant"] = "buscando :";

# Used in view_room.php  // A traduire
$vocab["utilisateurs ayant privileges"] = "Liste des utilisateurs ayant des privilèges sur cette ressource (hormis les administrateurs généraux) :";
$vocab["utilisateurs gestionnaires ressource"] = "Les utilisateurs suivants sont gestionnaires de cette ressource :";
$vocab["utilisateurs mail automatique"] = "Les utilisateurs suivants sont avertis par email :";
$vocab["utilisateurs acces restreint"] = "Les utilisateurs suivants sont autorisés à accéder à cette ressource :";
$vocab["utilisateurs administrateurs"] = "Les utilisateurs suivants sont administrateurs du domaine contenant cette ressource :";
$vocab["aucun autilisateur"] = "Pas d'utilisateurs";

# Used in view_rights_area.php  // A traduire
$vocab["utilisateurs ayant privileges sur domaine"] = "Liste des utilisateurs ayant des privilèges sur ce domaine (hormis les administrateurs généraux) :";
$vocab["utilisateurs acces restreint domaine"] = "Les utilisateurs suivants sont autorisés à accéder à ce domaine :";
$vocab["utilisateurs administrateurs domaine"] = "Les utilisateurs suivants sont administrateurs du domaine :";

# Used in admin_overload.php // A traduire
$vocab["explication_champs_additionnels"] = "Sur cette page, vous avez la possibilité de définir, domaine par domaine, des champs additionnels de votre choix et qui apparaîtront dans les formulaires de saisie des réservations comme autant de champs supplémentaires facultatifs.";
$vocab["fieldname"] = "Intitulé du champ";
$vocab["fieldtype"] = "Type du champ";
$vocab["add"] = "Ajouter";
$vocab["del"] = "Effacer";
$vocab["change"] = "Modifier";
$vocab["type_text"] = "Une ligne (text)";
$vocab["type_area"] = "Multi-lignes (textarea)";
$vocab["avertissement_suppression_champ_additionnel"] = "La suppression d'un champ additionnel est définitive et entraîne la suppression des données correspondantes dans les réservations déjà effectuées.";
?>