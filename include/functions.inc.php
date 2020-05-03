<?php
#########################################################################
#                        functions.inc.php                              #
#                                                                       #
#                Bibliothèque de fonctions                              #
#                                                                       #
#            Dernière modification : 24/01/2010                       #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
 * D'après http://mrbs.sourceforge.net/
 *
 * Modification S Duchemin
 * Ajout cherche adversaire
 * Ajout fonction vérification réservation double
 * Ajout fonction vérification compteur invité
 * Ajout fonction vérification nombre de réservation maximum par semaine si max_booking_week différent de -1
 * Ajout fonction recherche extension fichier(photos)
 * Ajout fonction limitation du nombre de réservation actives sur l'ensemble des installations ( maxallressources != -1)
 * Pour championnat individuel, les résa ne sont pas comptabilisées dans Fonction : UserRoomMaxBooking
 * Dans fonction verif_booking_week test pour championnat individuel
 * 
 * This file is part of GRR.
 *
 * GRR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GRR is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GRR; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// Pour les utilisateurs ayant des versions antérieures à PHP 4.3.0 :
// la fonction html_entity_decode() est disponible a partir de la version 4.3.0 de php.
function html_entity_decode_all_version ($string)
{
   global $use_function_html_entity_decode;
   if (isset($use_function_html_entity_decode) and ($use_function_html_entity_decode == 0)) {
       // Remplace les entités numériques
       $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
       $string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);
       // Remplace les entités litérales
       $trans_tbl = get_html_translation_table (HTML_ENTITIES);
       $trans_tbl = array_flip ($trans_tbl);
       return strtr ($string, $trans_tbl);
   } else
       return html_entity_decode($string);
}

function verif_version() {
    global $version_grr, $version_grr_RC;
    $version_old = getSettingValue("version");
    $versionRC_old = getSettingValue("versionRC");
    // S'il s'agit de la version stable, on positionne malgré tout $versionRC_old = 9 pour la cohérence du test
    if ($versionRC_old == "") $versionRC_old = 9;
    // S'il s'agit de la version stable, on positionne malgré tout $versionRC_old = 9 pour la cohérence du test
    if ($version_grr_RC == "") $version_grr_RC = 9;
    if (
        ($version_old =='')
        or ($version_grr > $version_old)
        or (($version_grr == $version_old) and ($version_grr_RC > $versionRC_old))
       )
        return true;
    else
        return false;
}

function affiche_version() {
    global $version_grr, $version_grr_RC, $sous_version_grr;
    if (getSettingValue("versionRC")!="")
  		return "<a href='http://clubtcr.teria.org/'>".getSettingValue("version").$sous_version_grr;
    else
     	return "<a href='http://clubtcr.teria.org/'>".getSettingValue("version").$sous_version_grr;
    
}


function affiche_date($x) {
 $j   = date("d",$x);
$m = date("m",$x);
$a  = date("Y",$x);
$h  = date("H",$x);
$mi = date("i",$x);
$s = date("s",$x);
$result = $h.":".$mi.":".$s.": le ".$j."/".$m."/".$a;
return $result;
}


# L'heure d'été commence le dernier dimanche de mars * et se termine le dernier dimanche d'octobre
# Passage à l'heure d'hiver : -1h, le changement s'effectue à 3h
# Passage à l'heure d'été : +1h, le changement s'effectue à 2h
# Si type = hiver => La fonction retourne la date du jour de passage à l'heure d'hiver
# Si type = ete =>  La fonction retourne la date du jour de passage à l'heure d'été
function heure_ete_hiver($type, $annee, $heure)
 {
    if ($type == "ete")
       $debut = mktime($heure,0,0,03,31,$annee); // 31-03-$annee
    else
       $debut = mktime($heure,0,0,10,31,$annee); // 31-10-$annee

    while (date("D", $debut ) !='Sun')
    {
       $debut = mktime($heure,0,0,date("m",$debut), date("d",$debut)-1, date("Y",$debut)); //On retire 1 jour par rapport à la date examinée
    }
    return $debut;
}

# Remove backslash-escape quoting if PHP is configured to do it with
# magic_quotes_gpc. Use this whenever you need the actual value of a GET/POST
# form parameter (which might have special characters) regardless of PHP's
# magic_quotes_gpc setting.
function unslashes($s)
{
    if (get_magic_quotes_gpc()) return stripslashes($s);
    else return $s;
}

// Corrige les caracteres degoutants utilises par les Windozeries
function corriger_caracteres($texte) {
    // 145,146,180 = simple quote ; 147,148 = double quote ; 150,151 = tiret long
    $texte = strtr($texte, chr(145).chr(146).chr(180).chr(147).chr(148).chr(150).chr(151), "'''".'""--');
    return str_replace( chr(133), "...", $texte );
}

// Traite les données avant insertion dans une requête SQL
function protect_data_sql($_value) {
    global $use_function_mysql_real_escape_string;
	global $con;
    if (get_magic_quotes_gpc()) $_value = stripslashes($_value);
    if (!is_numeric($_value)) {
        if (isset($use_function_mysql_real_escape_string) and ($use_function_mysql_real_escape_string==0))
             $_value = mysqli_escape_string($con,$_value);
        else
             $_value = mysqli_real_escape_string($con,$_value);
    }
    return $_value;
}

// Traite les données envoyées par la methode GET de la variable $_GET["page"]
function verif_page() {
    if (isset($_GET["page"]))
        if (($_GET["page"] == "day") or ($_GET["page"] == "week") or ($_GET["page"] == "month") or ($_GET["page"] == "week_all") or ($_GET["page"] == "month_all"))
            return $_GET["page"];
        else
            return "day";
    else
       return "day";
}

function page_accueil($param='no') {
   global $authentification_obli;
   // Definition de $defaultroom
   if (isset($_SESSION['default_room']) or ($authentification_obli==1)) {
      $defaultroom = $_SESSION['default_room'];
   } else {
      $defaultroom = getSettingValue("default_room");
   }

   // Definition de $defaultarea
   if (isset($_SESSION['default_area']) or ($authentification_obli==1)) {
      $defaultarea = $_SESSION['default_area'];
   } else {
      $defaultarea = getSettingValue("default_area");
   }

   // Calcul de $page_accueil
   if (((!$defaultroom and !$defaultarea)) or ($defaultarea == -1)) {
      $page_accueil="day.php";
      if ($param=='yes') $page_accueil=$page_accueil."?";
   } else if ($defaultroom == -1) {
      $area_accueil = $defaultarea;
      $page_accueil="day.php?area=$area_accueil";
      if ($param=='yes') $page_accueil=$page_accueil."&amp;";
   } else if ($defaultroom == -2) {
      $area_accueil = $defaultarea;
      $page_accueil="week_all.php?area=$area_accueil";
      if ($param=='yes') $page_accueil=$page_accueil."&amp;";
   } else if ($defaultroom == -3) {
      $area_accueil = $defaultarea;
      $page_accueil="month_all.php?area=$area_accueil";
      if ($param=='yes') $page_accueil=$page_accueil."&amp;";
   } else if ($defaultroom == -4) {
      $area_accueil = $defaultarea;
      $page_accueil="month_all2.php?area=$area_accueil";
      if ($param=='yes') $page_accueil=$page_accueil."&amp;";
   } else {
      $area_accueil = $defaultarea;
      $room_accueil = $defaultroom;
      $page_accueil="week.php?area=$area_accueil&amp;room=$room_accueil";
      if ($param=='yes') $page_accueil=$page_accueil."&amp;";
   }
   return $page_accueil;
}

function begin_page($title,$page="with_session")
{
	if ($page=="with_session")
	{
		if (isset($_SESSION['default_style'])) $sheetcss = "themes/".$_SESSION['default_style']."/css/style.css";
		else $sheetcss="themes/default/css/style.css";

		if (isset($_GET['default_language']))
		{
        $_SESSION['default_language'] = $_GET['default_language'];
			if (isset($_SESSION['chemin_retour']) and ($_SESSION['chemin_retour'] != ''))
            header("Location: ".$_SESSION['chemin_retour']);
			else
            header("Location: ".$_SERVER['PHP_SELF']);
			die();
		}
	}
	else
	{
		if (getSettingValue("default_css")) $sheetcss = "themes/".getSettingValue("default_css")."/css/style.css";
		else $sheetcss="themes/default/css/style.css";
		if (isset($_GET['default_language']))
		{
			$_SESSION['default_language'] = $_GET['default_language'];
			if (isset($_SESSION['chemin_retour']) && ($_SESSION['chemin_retour'] != ''))
				header("Location: ".$_SESSION['chemin_retour']);
			else
				header("Location: ".traite_grr_url());
			die();
		}
	}

 global $vocab, $charset_html, $unicode_encoding, $clock_file;
 header("Content-Type: text/html;charset=". ((isset($unicode_encoding) and ($unicode_encoding==1)) ? "utf-8" : $charset_html)); header("Pragma: no-cache");                          // HTTP 1.0
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past


	$a = '<!DOCTYPE html>'.PHP_EOL;
	$a .= '<html lang="fr">'.PHP_EOL;
	$a .= '<head>'.PHP_EOL;
	$a .= '<meta charset="utf-8">'.PHP_EOL;
	$a .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">'.PHP_EOL;
	$a .= '<meta name="viewport" content="width=device-width, initial-scale=1">'.PHP_EOL;
	$a .= '<meta name="Robots" content="noindex" />'.PHP_EOL;
	$a .= '<title>'.$title.'</title>'.PHP_EOL;
	$a .= '<link rel="shortcut icon" href="./favicon.ico" />'.PHP_EOL;


    $a .= '<link rel="stylesheet" href="'.$sheetcss.'" type="text/css" />'.PHP_EOL;
		// Pour le format imprimable, on impose un fond de page blanc
		if ((isset($_GET['pview'])) and ($_GET['pview'] == 1))
        $a .= '<link rel="stylesheet" type="text/css" href="themes/print/css/style.css"  />'.PHP_EOL;
		$a .= '<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />'.PHP_EOL;
		$a .= '<script type="text/javascript" src="js/functions.js" ></script>'.PHP_EOL;
		
		if (@file_exists($clock_file)) 
		{
		$a .='<script type="text/javascript" src="'.$clock_file.'"></script>';
		}
# show a warning if this is using a low version of php
if (substr(phpversion(), 0, 1) == 3)  $a .=get_vocab('not_php3');

	$a .= '</head>'.PHP_EOL;
	$a .= '<body>'.PHP_EOL;
return $a;
}

function print_header($day, $month, $year, $area, $type="with_session", $page="no_admin")
{
   global $vocab, $search_str, $grrSettings, $session_statut, $authentification_obli, $clock_file, $allow_search_for_not_connected, $is_authentified_lcs;
   if ($type == "with_session")
       echo begin_page(getSettingValue("company").get_vocab("deux_points").get_vocab("mrbs"),"with_session");
   else
       echo begin_page(getSettingValue("company").get_vocab("deux_points").get_vocab("mrbs"),"no_session");
   // Si nous ne sommes pas dans un format imprimable
   if ((!isset($_GET['pview'])) or ($_GET['pview'] != 1)) 
   {
		# If we dont know the right date then make it up
		if(!$day) $day   = date("d");
		if(!$month)$month = date("m");
		if(!$year) $year  = date("Y");
		if (!(isset($search_str))) $search_str = get_vocab("search_for");
		if (empty($search_str)) $search_str = "";
   }
  //----------------------------------------------------------------
  // Début d'élément à cacher
  //-----------------------------------------------------------------
?>

<script type="text/javascript">
     
    function ouvrirFermerSpoiler(div)
    {
        var divContenu = div.getElementsByTagName('div')[1];
         
        if(divContenu.style.display == 'block')
            divContenu.style.display = 'none';
        else
            divContenu.style.display = 'block';
    }
     
</script>
<?php
echo '<div class="spoilerDiv" onclick="ouvrirFermerSpoiler(this);">';
	//echo '<span class="lienAfficher">Clique pour afficher</span>';
	echo '<div class="spoiler">';
		echo '<div class="contenuSpoiler">';
			echo'<div class="container-fluid">'.PHP_EOL;
				echo'<div class="row">'.PHP_EOL;
					echo'<div class="col-md-3 center">'.PHP_EOL;
					$param= 'yes';
		
		  
		
	//Affichage de l'heure
	if (@file_exists($clock_file)) 
	{
        echo "<script type=\"text/javascript\" LANGUAGE=\"javascript\">";
		echo "<!--\n";
		echo "new LiveClock();\n";
		echo "//-->";
		echo "</SCRIPT><br>";
    }
	
		//Affichage de la version de GTC
		echo "<span class=\"small\">".affiche_version()."</span> - ";
		echo "<A HREF=\"help.php\">".get_vocab("help")."</A><br>";
		if (($type == "with_session") or (isset($allow_search_for_not_connected) and ($allow_search_for_not_connected==1))) 
		{
			echo "<a href=\"".getSettingValue("grr_url")."annonce.php\"><img src=\"img_grr/adversaire.jpg\" alt=\"Adversaires\" border=\"0\" title=\"Recherche d'adversaire\" width=\"30\" height=\"30\" /></a><br>\n";
		}
				$_SESSION['chemin_retour'] = '';
				if (isset($_SERVER['QUERY_STRING']) and ($_SERVER['QUERY_STRING'] != '')) 
				{
                    // Il y a des paramètres à passer
                    $parametres_url = $_SERVER['QUERY_STRING']."&amp;";
                    $_SESSION['chemin_retour'] = $_SERVER['PHP_SELF']."?". $_SERVER['QUERY_STRING'];
					//Affichage des langues
					echo '<a onclick="charger();"href="'.$_SERVER['PHP_SELF'].'?'.$parametres_url.'default_language=fr"><img src="img_grr/fr_dp.png" alt="France" title="france" width="20" height="13" align="middle" border="0" /></a>'.PHP_EOL;
					echo '<a onclick="charger();"href="'.$_SERVER['PHP_SELF'].'?'.$parametres_url.'default_language=de"><img src="img_grr/de_dp.png" alt="Deutch" title="deutch" width="20" height="13" align="middle" border="0" /></a>'.PHP_EOL;
					echo '<a onclick="charger();"href="'.$_SERVER['PHP_SELF'].'?'.$parametres_url.'default_language=en"><img src="img_grr/en_dp.png" alt="English" title="English" width="20" height="13" align="middle" border="0" /></a>'.PHP_EOL;
					echo '<a onclick="charger();"href="'.$_SERVER['PHP_SELF'].'?'.$parametres_url.'default_language=it"><img src="img_grr/it_dp.png" alt="Italiano" title="Italiano" width="20" height="13" align="middle" border="0" /></a>'.PHP_EOL;
					echo '<a onclick="charger();"href="'.$_SERVER['PHP_SELF'].'?'.$parametres_url.'default_language=es"><img src="img_grr/es_dp.png" alt="Spanish" title="Spanish" width="20" height="13" align="middle" border="0" /></a>'.PHP_EOL;
		
                }
			
		//echo "<A HREF=\"".page_accueil($param)."day=$day&amp;year=$year&amp;month=$month\">".get_vocab("welcome")."</A>";
			if ($type == 'no_session') 
			{
			
				echo "<br>&nbsp;<a href='login.php'>".get_vocab("connect")."</a>";
				echo "&nbsp;&nbsp;-&nbsp;&nbsp;<A HREF=\"user_change_pwd.php\">".get_vocab('msg_init')."</a>";
			} else 
			{
				echo "<br>&nbsp;<b>".get_vocab("welcome_to").$_SESSION['nom']." ".$_SESSION['prenom']."</b>";
				if ($_SESSION['statut'] != 'visiteur')
				echo "<br>&nbsp;<a href=\"my_account.php?day=".$day."&amp;year=".$year."&amp;month=".$month."\">".get_vocab("manage_my_account")."</a>";
				//if ($type == "with_session") {
				$parametres_url = '';
				
                 
				//}
				if (!((getSettingValue("sso_statut") == 'lcs') and ($_SESSION['source_login']=='ext') and ($is_authentified_lcs == "yes")))
				if ($authentification_obli == 1) 
				{
                 echo "<br>&nbsp;<a href=\"./logout.php?auto=0\" >".get_vocab('disconnect')."</a>";
				} else 
				{
					echo "<br>&nbsp;<a href=\"./logout.php?auto=0&amp;authentif_obli=no\" >".get_vocab('disconnect')."</a>";
				}
			}
	echo'</div>'.PHP_EOL;   
	echo'<div class="col-md-3 center hidden-xs bordure" >'.PHP_EOL; 
		echo "<img src=\"img_grr/logo.gif\" class=\"img-fluid\" alt=\"logo\" title=\"grr\" height=\"100%\" align=\"middle\" border=\"0\" /></TD>";
    echo'</div>'.PHP_EOL;
	echo'<div class="col-md-3 center hidden-xs bordure">'.PHP_EOL;
		
	echo "<h3><a href='".page_accueil($param)."day=$day&amp;year=$year&amp;month=$month'>".get_vocab('welcome')."</a>";
	echo "<a href='login.php'>-".getSettingValue("company")."</a>";
	
     if ($page=="no_admin") {
     ?>
         <?php /*
           <FORM ACTION="day.php" METHOD=GET>
           <?php
           genDateSelector("", $day, $month, $year,"");
           if (!empty($area)) echo "<INPUT TYPE=HIDDEN NAME=area VALUE=$area>"
           ?>
           <INPUT TYPE=SUBMIT VALUE="<?php echo get_vocab("goto") ?>">
           </FORM>
           <?php
           // Il faut être connecté pour avoir accès à l'outil de recherche
           // Ou bien il faut que $allow_search_for_not_connected=1
           
           ?>
           <FORM METHOD=GET ACTION="search.php">
           <INPUT TYPE=TEXT   NAME="search_str" VALUE="<?php echo $search_str ?>" onFocus="if (this.value==chaine_recherche) {this.value=''}" SIZE=10>
           <INPUT TYPE=SUBMIT VALUE="OK">
           <INPUT TYPE=HIDDEN NAME=day  VALUE="<?php echo $day        ?>"        >
           <INPUT TYPE=HIDDEN NAME=month      VALUE="<?php echo $month      ?>"        >
           <INPUT TYPE=HIDDEN NAME=year       VALUE="<?php echo $year       ?>"        >
           <?php
           if (!empty($area)) echo "<INPUT TYPE=HIDDEN NAME=area VALUE=$area>"
           ?>
           </FORM>
		  */ ?>
		          
         <?php
     }
     
     
     echo'</div>'.PHP_EOL;
	 echo'<div class="col-md-3 center">'.PHP_EOL;
	//minicals($year, $month, $day, $area, -1, 'day');
	 //Choix affichage en fonction du niveau de connexion
	 if(authGetUserLevel(getUserName(),-1,'area') >= 4) {
	 
	 if (($type == "with_session") and ($_SESSION['statut'] != 'visiteur')) {
          echo "<A HREF=\"report.php\">".get_vocab("report")."</A><br>";
      }
     } else {
       echo'<div class="col-md-3 center hidden-xs">'.PHP_EOL;
	 }
     	  
      //echo "<span class=\"small\">".affiche_version()."</span> - ";
     /* if ($type == "with_session") {
          if ($_SESSION['statut'] == 'administrateur') {
              echo "<a href='mailto:".getSettingValue("technical_support_email")."'>".get_vocab("technical_contact")."</a><br>";
          } else {
              echo "<a href='mailto:".getSettingValue("webmaster_email")."'>".get_vocab("administrator_contact")."</a><br>";
          }
      } else {
           echo "<a href='mailto:".getSettingValue("webmaster_email")."'>".get_vocab("administrator_contact")."</a><br>";
      }
	  */
	  
		//Affichage lien poursauvegarde
		if ($type == "with_session") {
          if(authGetUserLevel(getUserName(),-1,'area') >= 4) {
          
           echo "<A HREF='admin_accueil.php?day=$day&amp;month=$month&amp;year=$year'>".get_vocab("admin")."</A>\n
           <br><form action=\"admin_save_mysql.php\" method=\"GET\" name=sav>\n
           <input type=\"submit\" value=\"".get_vocab("submit_backup")."\" >\n
           </form>";
      }
     }
	echo'</div>'.PHP_EOL;
    echo'</div>'.PHP_EOL;
    echo'</div>'.PHP_EOL;
	echo '</div>';
  echo '</div>';
echo '</div>';
   echo '</div>';
}

// Tr    ansforme $dur en un nombre entier
// $dur : durée
// $units : unité
function toTimeString(&$dur, &$units)
{
    global $vocab;
    if($dur >= 60)
    {
        $dur = $dur/60;

        if($dur >= 60)
        {
            $dur /= 60;

            if(($dur >= 24) && ($dur % 24 == 0))
            {
                $dur /= 24;

                if(($dur >= 7) && ($dur % 7 == 0))
                {
                    $dur /= 7;

                    if(($dur >= 52) && ($dur % 52 == 0))
                    {
                        $dur  /= 52;
                        $units = get_vocab("years");
                    }
                    else
                        $units = get_vocab("weeks");
                }
                else
                    $units = get_vocab("days");
            }
            else
                $units = get_vocab("hours");

        }
        else
            $units = get_vocab("minutes");
    }
    else
        $units = get_vocab("seconds");
}

// Transforme $dur en un nombre entier
// $dur : durée
// $units : unité
function toPeriodString($start_period, &$dur, &$units)
{
    global $enable_periods, $periods_name, $vocab;
    $max_periods = count($periods_name);
    $dur /= 60;
        if( $dur >= $max_periods || $start_period == 0 )
        {
                if( $start_period == 0 && $dur == $max_periods )
                {
                        $units = get_vocab("days");
                        $dur = 1;
                        return;
                }

                $dur /= 60;
                if(($dur >= 24) && is_int($dur))
                {
                    $dur /= 24;
            $units = get_vocab("days");
                        return;
                }
                else
                {
            $dur *= 60;
                        $dur = ($dur % $max_periods) + floor( $dur/(24*60) ) * $max_periods;
                        $units = get_vocab("periods");
                        return;
        }
        }
        else
        $units = get_vocab("periods");
}




function genDateSelectorForm($prefix, $day, $month, $year,$option)
{
    global $nb_year_calendar;
    $selector_data = "";

    // Compatibilité avec version GRR < 1.9
    if (!isset($nb_year_calendar)) $nb_year_calendar = 5;

    if($day   == 0) $day = date("d");
    if($month == 0) $month = date("m");
    if($year  == 0) $year = date("Y");

    $selector_data .= "<SELECT NAME=\"${prefix}day\">\n";

    for($i = 1; $i <= 31; $i++)
      $selector_data .= "<OPTION" . ($i == $day ? " SELECTED" : "") . ">$i</OPTION>\n";

    $selector_data .= "</SELECT>";
    $selector_data .= "<SELECT NAME=\"${prefix}month\">\n";

    for($i = 1; $i <= 12; $i++)
    {
        $m = utf8_strftime("%b", mktime(0, 0, 0, $i, 1, $year));

        $selector_data .=  "<OPTION VALUE=\"$i\"" . ($i == $month ? " SELECTED" : "") . ">$m</OPTION>\n";
    }

    $selector_data .=  "</SELECT>";
    $selector_data .=  "<SELECT NAME=\"${prefix}year\">\n";

    $min = strftime("%Y", getSettingValue("begin_bookings"));
    if ($option == "more_years") $min = date("Y") - $nb_year_calendar;

    $max = strftime("%Y", getSettingValue("end_bookings"));
    if ($option == "more_years") $max = date("Y") + $nb_year_calendar;

    for($i = $min; $i <= $max; $i++)
      $selector_data .= "<OPTION" . ($i == $year ? " SELECTED" : "") . ">$i</OPTION>\n";

    $selector_data .= "</SELECT>";

    return $selector_data;
}


function genDateSelector($prefix, $day, $month, $year,$option)
{
  echo genDateSelectorForm($prefix, $day, $month, $year,$option);
}




# Error handler - this is used to display serious errors such as database
# errors without sending incomplete HTML pages. This is only used for
# errors which "should never happen", not those caused by bad inputs.
# If $need_header!=0 output the top of the page too, else assume the
# caller did that. Alway outputs the bottom of the page and exits.
function fatal_error($need_header, $message)
{
    global $vocab;
    if ($need_header) print_header(0, 0, 0, 0);
    echo $message;
    include "trailer.inc.php";
    exit;
}


# Retourne le domaine par défaut; Utilisé si aucun domaine n'a été défini.
function get_default_area()
{
    if (OPTION_IP_ADR==1) {
        // Affichage d'un domaine par defaut en fonction de l'adresse IP de la machine cliente
        $res = grr_sql_query("SELECT id FROM grr_area WHERE ip_adr='".protect_data_sql($_SERVER['REMOTE_ADDR'])."' ORDER BY access, order_display, area_name");
        if ($res && grr_sql_count($res)>0 ) {
            $row = grr_sql_row($res, 0);
            return $row[0];

        }
    }
    if(authGetUserLevel(getUserName(),-1) >= 5)
        // si l'admin est connecté, on cherche le premier domaine venu
        $res = grr_sql_query("SELECT id FROM grr_area ORDER BY access, order_display, area_name");
    else
        // s'il ne s'agit pas de l'admin, on cherche le premier domaine à accès non restreint
        $res = grr_sql_query("SELECT id FROM grr_area where access!='r' ORDER BY access, order_display, area_name");

    if ($res && grr_sql_count($res)>0 ) {
        $row = grr_sql_row($res, 0);
        return $row[0];
    } else {
        // On cherche le premier domaine à accès restreint
        $res = grr_sql_query("select id from grr_area, grr_j_user_area where
        grr_area.id=grr_j_user_area.id_area and
        login='" . getUserName() . "'
        ORDER BY order_display, area_name");
        if ($res && grr_sql_count($res)>0 ) {
            $row = grr_sql_row($res, 0);
            return $row[0];
        }
    else
        return 0;
    }

}

# Get the local day name based on language. Note 2000-01-02 is a Sunday.
function day_name($daynumber)
{
    return utf8_strftime("%A", mktime(0,0,0,1,2+$daynumber,2000));
}

function hour_min_format()
{
        global $twentyfourhour_format;
        if ($twentyfourhour_format)
    {
              return "H:i";
    }
    else
    {
        return "h:ia";
    }
}

function period_date_string($t, $mod_time=0)
{
    global $periods_name, $dformat;
    $time = getdate($t);
    $p_num = $time["minutes"] + $mod_time;
    if( $p_num < 0 ) $p_num = 0;
    if( $p_num >= count($periods_name) - 1 ) $p_num = count($periods_name) - 1;
    return array($p_num, $periods_name[$p_num] . utf8_strftime(", ".$dformat,$t));
}


function period_time_string($t, $mod_time=0)
{
    global $periods_name;
    $time = getdate($t);
    $p_num = $time["minutes"] + $mod_time;
    if( $p_num < 0 ) $p_num = 0;
    if( $p_num >= count($periods_name) - 1 ) $p_num = count($periods_name) - 1;
    return $periods_name[$p_num];
}


function time_date_string($t,$dformat)
{
        global $twentyfourhour_format;
        # This bit's necessary, because it seems %p in strftime format
        # strings doesn't work
        $ampm = date("a",$t);
        if ($twentyfourhour_format)
    {
              return utf8_strftime("%H:%M:%S - ".$dformat,$t);
    }
    else
    {
            return utf8_strftime("%I:%M:%S$ampm - ".$dformat,$t);
    }
}

function time_date_string_jma($t,$dformat)
{
        global $twentyfourhour_format;
        # This bit's necessary, because it seems %p in strftime format
        # strings doesn't work
        $ampm = date("a",$t);
        if ($twentyfourhour_format)
    {
              return utf8_strftime($dformat,$t);
    }
    else
    {
            return utf8_strftime($dformat,$t);
    }
}


// Renvoie une balise span avec un style backgrounf-color correspondant au type de  la réservation
function span_bgground($colclass)
{
    global $tab_couleur;
    static $ecolors;
    $num_couleur = grr_sql_query1("select couleur from grr_type_area where type_letter='".$colclass."'");
    echo "<span style=\"background-color: ".$tab_couleur[$num_couleur]."; background-image: none; background-repeat: repeat; background-attachment: scroll;\">";
}


# Output a start table cell tag <td> with color class and fallback color.
function tdcell($colclass, $width='')
{
    if ($width!="") $temp = " style=\"vertical-align: width: ".$width."%;\" "; else $temp = "";
    global $tab_couleur;
    static $ecolors;
    if (($colclass >= "A") and ($colclass <= "Z")) {
        $num_couleur = grr_sql_query1("select couleur from grr_type_area where type_letter='".$colclass."'");
        echo "<td bgcolor=\"".$tab_couleur[$num_couleur]."\" ".$temp.">";
    } else
        echo "<td class=\"$colclass\" ".$temp.">";
}

// Paul Force
function tdcell_rowspan($colclass , $step)
{
    global $tab_couleur;
    static $ecolors;
    if (($colclass >= "A") and ($colclass <= "Z")) {
        $num_couleur = grr_sql_query1("select couleur from grr_type_area where type_letter='".$colclass."'");
        echo "<td rowspan=$step bgcolor=\"".$tab_couleur[$num_couleur]."\">";
    } else
        echo "<td rowspan=$step td class=\"".$colclass."\">";
}


# Display the entry-type color key. This has up to 2 rows, up to 10 columns.
function show_colour_key($area_id)
{
    echo "<table border=0  width=\"100%\"><tr>\n";
    $nct = 0;
    $sql = "SELECT DISTINCT t.id, t.type_name, t.type_letter FROM grr_type_area t
    LEFT JOIN grr_j_type_area j on j.id_type=t.id
    WHERE (j.id_area  IS NULL or j.id_area != '".$area_id."')
    ORDER BY t.order_display";
    $res = grr_sql_query($sql);
    if ($res) {
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
        // La requête sql précédente laisse passer les cas où un type est non valide dans le domaine concerné ET au moins dans un autre domaine, d'où le test suivant
        $test = grr_sql_query1("select id_type from grr_j_type_area where id_type = '".$row[0]."' and id_area='".$area_id."'");
        if ($test == -1) {
            $id_type        = $row[0];
            $type_name        = $row[1];
            $type_letter          = $row[2];
            if (++$nct > 10)
                {
                    $nct = 0;
                    echo "</tr><tr>";
                }
            tdcell($type_letter);
            echo "$type_name</td>\n";
        }
    }
    echo "</tr></table>\n";
    }
}




# Round time down to the nearest resolution
function round_t_down($t, $resolution, $am7)
{
        return (int)$t - (int)abs(((int)$t-(int)$am7)
                  % $resolution);
}

# Round time up to the nearest resolution
function round_t_up($t, $resolution, $am7)
{
    if (($t-$am7) % $resolution != 0)
    {
        return $t + $resolution - abs(((int)$t-(int)
                           $am7) % $resolution);
    }
    else
    {
        return $t;
    }
}

# generates some html that can be used to select which area should be
# displayed.
function make_area_select_html( $link, $current, $year, $month, $day, $user )
{
    global $vocab;
    $out_html = "<b><i>".get_vocab("areas")."</i></b><form name=\"area\">
                 <select name=\"area\" onChange=\"area_go()\">";

    $sql = "select id, area_name from grr_area order by order_display, area_name";
       $res = grr_sql_query($sql);
       if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
       {
        $selected = ($row[0] == $current) ? "selected" : "";
        $link2 = "$link?year=$year&amp;month=$month&amp;day=$day&amp;area=$row[0]";
        if (authUserAccesArea($user,$row[0])==1) {
            $out_html .= "<option $selected value=\"$link2\">" . htmlspecialchars($row[1]);
        }
       }
    $out_html .= "</select>
       <SCRIPT type=\"text/javascript\" language=\"JavaScript\">
       <!--
       function area_go()
        {
        box = document.forms[\"area\"].area;
        destination = box.options[box.selectedIndex].value;
        if (destination) location.href = destination;
        }
        // -->
        </SCRIPT>

        <noscript>
        <input type=submit value=\"Change\">
        </noscript>
        </form>";

    return $out_html;
} # end make_area_select_html

function make_room_select_html( $link, $area, $current, $year, $month, $day )
{
    global $vocab;
    $out_html = "<b><i>".get_vocab('rooms')."</i></b><br><form name=\"room\">
                 <select name=\"room\" onChange=\"room_go()\">";

    $out_html .= "<option value=\"".$link."_all.php?year=$year&amp;month=$month&amp;day=$day&amp;area=$area\">".get_vocab("all_rooms")."</option>";
    $sql = "select id, room_name, description from grr_room where area_id='".protect_data_sql($area)."' order by order_display,room_name";
       $res = grr_sql_query($sql);
       if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
       {
        if ($row[2]) {$temp = " (".htmlspecialchars($row[2]).")";} else {$temp="";}
        $selected = ($row[0] == $current) ? "selected" : "";
        $link2 = "$link.php?year=$year&amp;month=$month&amp;day=$day&amp;area=$area&amp;room=$row[0]";
        $out_html .= "<option $selected value=\"$link2\">" . htmlspecialchars($row[1].$temp)."</option>";
       }
    $out_html .= "</select>
       <SCRIPT type=\"text/javascript\" language=\"JavaScript\">
       <!--
       function room_go()
        {
        box = document.forms[\"room\"].room;
        destination = box.options[box.selectedIndex].value;
        if (destination) location.href = destination;
        }
        // -->
        </SCRIPT>

        <noscript>
        <input type=submit value=\"Change\">
        </noscript>
        </form>";

    return $out_html;
} # end make_room_select_html

function make_area_list_html($link, $current, $year, $month, $day, $user) {
   global $vocab;
   echo "<b><i><span class=\"bground\">".get_vocab("areas")."</span></i></b><br>";
   $sql = "select id, area_name from grr_area order by order_display, area_name";
   $res = grr_sql_query($sql);
   if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
    {
    if (authUserAccesArea($user,$row[0])==1) {
       if ($row[0] == $current)
          {
             echo "<b><span class=\"week\">>&nbsp;<a href=\"".$link."?year=$year&amp;month=$month&amp;day=$day&amp;area=$row[0]\">".htmlspecialchars($row[1])."</a></span></b><br>\n";
          } else {
             echo "<a href=\"".$link."?year=$year&amp;month=$month&amp;day=$day&amp;area=$row[0]\">".htmlspecialchars($row[1])."</a><br>\n";
          }
       }
   }
}
function make_room_list_html($link, $area, $current, $year, $month, $day) {
   global $vocab;
   echo "<b><i><span class=\"bground\">".get_vocab("rooms")."</span></i></b><br>";
   $sql = "select id, room_name, description from grr_room where area_id='".protect_data_sql($area)."' order by order_display,room_name";
   $res = grr_sql_query($sql);
   if ($res) for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
   {
      if ($row[0] == $current)
      {
        echo "<b><span class=\"week\">>&nbsp;".htmlspecialchars($row[1])."</span></b><br>\n";
      } else {
        echo "<a href=\"".$link."?year=$year&amp;month=$month&amp;day=$day&amp;area=$area&amp;room=$row[0]\">".htmlspecialchars($row[1]). "</a><br>\n";
      }
   }
}


function send_mail($id_entry,$action,$dformat)
{
    global $vocab, $grrSettings, $locale, $weekstarts, $enable_periods, $periods_name;
    require_once "lib.inc.php";
    setlocale(LC_ALL,$locale);
    $sql = "
    SELECT grr_entry.name,
    grr_entry.description,
    grr_entry.create_by,
    grr_room.room_name,
    grr_area.area_name,
    grr_entry.type,
    grr_entry.room_id,
    grr_entry.repeat_id,
    " . grr_sql_syntax_timestamp_to_unix("grr_entry.timestamp") . ",
    (grr_entry.end_time - grr_entry.start_time),
    grr_entry.start_time,
    grr_entry.end_time,
    grr_room.area_id,
    grr_room.delais_option_reservation,
    grr_entry.option_reservation
    FROM grr_entry, grr_room, grr_area
    WHERE grr_entry.room_id = grr_room.id
    AND grr_room.area_id = grr_area.id
    AND grr_entry.id='".protect_data_sql($id_entry)."'
    ";
    $res = grr_sql_query($sql);
    if (! $res) fatal_error(0, grr_sql_error());
    if(grr_sql_count($res) < 1) fatal_error(0, get_vocab('invalid_entry_id'));
    $row = grr_sql_row($res, 0);
    grr_sql_free($res);
    // Récupération des données concernant l'affichage du planning du domaine
    get_planning_area_values($row[12]);
    $name         = removeMailUnicode(htmlspecialchars($row[0]));
    $description  = removeMailUnicode(htmlspecialchars($row[1]));
    $create_by    = htmlspecialchars($row[2]);
    $room_name    = removeMailUnicode(htmlspecialchars($row[3]));
    $area_name    = removeMailUnicode(htmlspecialchars($row[4]));
    $type         = $row[5];
    $room_id      = $row[6];
    $repeat_id    = $row[7];
    $updated      = time_date_string($row[8],$dformat);
    $date_avis    = strftime("%Y/%m/%d",$row[10]);
    $delais_option_reservation = $row[13];
    $option_reservation = $row[14];
    $duration     = $row[9];
    if($enable_periods=='y')
        list( $start_period, $start_date) =  period_date_string($row[10]);
    else
        $start_date = time_date_string($row[10],$dformat);
    if($enable_periods=='y')
        list( , $end_date) =  period_date_string($row[11], -1);
    else
        $end_date = time_date_string($row[11],$dformat);
    $rep_type = 0;

    if($repeat_id != 0)
    {
        $res = grr_sql_query("SELECT rep_type, end_date, rep_opt, rep_num_weeks FROM grr_repeat WHERE id='".protect_data_sql($repeat_id)."'");
        if (! $res) fatal_error(0, grr_sql_error());

        if (grr_sql_count($res) == 1)
        {
            $row2 = grr_sql_row($res, 0);

            $rep_type     = $row2[0];
            $rep_end_date = strftime($dformat,$row2[1]);
            $rep_opt      = $row2[2];
            $rep_num_weeks = $row2[3];
        }
        grr_sql_free($res);
    }
    if ($enable_periods=='y')
        toPeriodString($start_period, $duration, $dur_units);
    else
        toTimeString($duration, $dur_units);
    $repeat_key = "rep_type_" . $rep_type;

    # Now that we know all the data we start drawing it

    $sql = "select nom, prenom, email, etat from grr_utilisateurs where login='$create_by'";
    $res = grr_sql_query($sql);
    if (! $res) fatal_error(0, grr_sql_error());
    $row_user = grr_sql_row($res, 0);

    if ($action != '4') {
        $user_login=$_SESSION['login'];
        $sql = "select nom, prenom, email from grr_utilisateurs where login='$user_login'";
        $res = grr_sql_query($sql);
        if (! $res) fatal_error(0, grr_sql_error());
        $row_user_login = grr_sql_row($res, 0);
    }
    $message = removeMailUnicode(getSettingValue("company"))." - ".$vocab["title_mail"];
    $message = $message.getSettingValue("grr_url")."\n\n";

    $sujet = $vocab["subject_mail1"].$room_name." ".$date_avis;
    if ($action == 1) {
        $message = $message.$vocab["the_user"].removeMailUnicode($row_user_login[0])." ".removeMailUnicode($row_user_login[1])." (".$row_user_login[2].")";
        $message = $message.$vocab["creation_booking"];
        $sujet = $sujet.$vocab["subject_mail_creation"];
    } else if ($action == 2) {
        $message = $message.$vocab["the_user"].removeMailUnicode($row_user_login[0])." ".removeMailUnicode($row_user_login[1])." (".$row_user_login[2].")";
        $message = $message.$vocab["modify_booking"];
        $sujet = $sujet.$vocab["subject_mail_modify"];
    } else if ($action == 3) {
        $message = $message.$vocab["the_user"].removeMailUnicode($row_user_login[0])." ".removeMailUnicode($row_user_login[1])." (".$row_user_login[2].")";
        $message = $message.$vocab["delete_booking"];
        $sujet = $sujet.$vocab["subject_mail_delete"];
    } else {
        $message = $message.$vocab["suppression_automatique"];
        $sujet = $sujet.$vocab["subject_mail_delete"];
    }
    $message=$message.$vocab["the_room"].$room_name." (".$area_name.") \n";

    if (($action == 2) or ($action==3)) {
        $message = $message.$vocab["created_by"];
        $message = $message.$vocab["the_user"].removeMailUnicode($row_user[0])." ".removeMailUnicode($row_user[1])." (".$row_user[2].") \n";
    }

    if ($action == 4)
        $repondre = getSettingValue("webmaster_email");
    else
        $repondre = $row_user_login[2];
    $expediteur = getSettingValue("webmaster_email");


    //
    // texte de la réservation
    //
    $reservation = '';
    $reservation = $reservation.$vocab["start_of_the_booking"]." ".$start_date."\n";
    $reservation = $reservation.$vocab["duration"]." ".$duration." ".$dur_units."\n";
    $reservation = $reservation.$vocab["namebooker"].str_replace("&nbsp;", " ",$vocab["deux_points"])." ".$name."\n";
    if ($description !='') {
        $reservation = $reservation.$vocab["description"]." ".$description."\n";
    }
    #Type de réservation
    $temp = grr_sql_query1("select type_name from grr_type_area where type_letter='".$row[5]."'");
    if ($temp == -1) $temp = "?".$row[5]."?"; else $temp = removeMailUnicode($temp);
    $reservation = $reservation.$vocab["type"].str_replace("&nbsp;", " ",$vocab["deux_points"])." ".$temp."\n";
    if($rep_type != 0) {
        $reservation = $reservation.$vocab["rep_type"]." ".$vocab[$repeat_key]."\n";
    }

    if($rep_type != 0)
    {
        $opt = "";
        if (($rep_type == 2) || ($rep_type == 6))
        {
            # Display day names according to language and preferred weekday start.
            for ($i = 0; $i < 7; $i++)
            {
                $daynum = ($i + $weekstarts) % 7;
                if ($rep_opt[$daynum]) $opt .= day_name($daynum) . " ";
            }
        }
        if ($rep_type == 6)
        {    $reservation = $reservation.$vocab["rep_num_weeks"].$vocab["rep_for_nweekly"]." ".$rep_num_weeks."\n";
        }
        if($opt)
            $reservation = $reservation.$vocab["rep_rep_day"]." ".$opt."\n";
        $reservation = $reservation.$vocab["rep_end_date"]." ".$rep_end_date."\n";

    }
    if (($delais_option_reservation > 0) and ($option_reservation != -1))
        $reservation = $reservation."*** ".$vocab["reservation_a_confirmer_au_plus_tard_le"]." ".time_date_string_jma($option_reservation,$dformat)." ***\n";


    $reservation = $reservation."-----\n";

    $message = $message.$reservation;
    $message = $message.$vocab["msg_no_email"].$expediteur;
    $message = html_entity_decode_all_version($message);


    // ------------------------------------------------------------------------- //
    // Classe Mail                                                               //
    // ------------------------------------------------------------------------- //
    // Auteur: Nicolas <progweb@free.fr>                                         //
    // Web:    http://www.progweb.com/                                           //
    // ------------------------------------------------------------------------- //
    /*
    Cette classe permet d'envoyer des mails très simplement.
    Tout comme un vrai un client mail, elle gère : champs From, To, Cc, Bcc, ReplyTo, Priority, Organization, Subject, Body,
    Format (html / text), Attachment (le mime type du document est détecté automatiquement).
    Cette classe permet également de vérifier la valider des mails.
    */
    // Cas d'une modification ou d'une suppression d'un message par un utilisateur différent du créateur :
    // On envoie un message au créateur de la réservation pour l'avertir d'une modif ou d'une suppression
    //
    if ((($action == 2) or ($action==3))  and   ($user_login != $create_by)  and ($row_user[2]!='') and ($row_user[3]=='actif')) {
        $sujet2 = $vocab["subject_mail1"].$room_name." ".$date_avis;
        $message2 = removeMailUnicode(getSettingValue("company"))." - ".$vocab["title_mail"];
        $message2 = $message2.getSettingValue("grr_url")."\n\n";
        $message2 = $message2.$vocab["the_user"].removeMailUnicode($row_user_login[0])." ".removeMailUnicode($row_user_login[1])." (".$row_user_login[2].")";
        if ($action == 2) {
            $sujet2 = $sujet2.$vocab["subject_mail_modify"];
            $message2 = $message2.$vocab["modify_booking"];
        } else {
            $sujet2 = $sujet2.$vocab["subject_mail_delete"];
            $message2 = $message2.$vocab["delete_booking"];
        }
        $message2=$message2.$vocab["the_room"].$room_name." (".$area_name.")";
        $message2 = $message2.$vocab["created_by_you"];
        $message2 = $message2."\n".$reservation;
        $message2 = html_entity_decode_all_version($message2);
        $destinataire2 = $row_user[2];
        $repondre2 = $row_user_login[2];
        $expediteur2 = getSettingValue("webmaster_email");
        $m2= new Mail;
        $m2->AutoCheck(false);
        $m2->From( $expediteur2 );
        $m2->To( $destinataire2 );
        $m2->Subject( $sujet2 );
        $m2->Body( $message2, "iso-8859-15");
        $m2->ReplyTo( $repondre2 );
        $m2->Organization( "Grr" );
        $m2->Format("text");
        $m2->Priority(3);
        $m2->Send();
        $m2->Get();
    }

    $sql = "SELECT u.email FROM grr_utilisateurs u, grr_j_mailuser_room j WHERE
    (j.id_room='".protect_data_sql($room_id)."' and u.login=j.login and u.etat='actif')  order by u.nom, u.prenom";
    $res = grr_sql_query($sql);
    $nombre = grr_sql_count($res);
    if ($nombre==0) {
        return;
    } else {
        for ($i = 0; ($row = grr_sql_row($res, $i)); $i++)
        {
        $destinataire[$i] = $row[0];
        }
    }



    // Create the mail
    $m= new Mail;
    // Check mail or not (by default mails is checked)
    $m->AutoCheck(false);
    // Set From
    $m->From( $expediteur );
    // Set To (it can be an array or a string)
    // ["mail1", "mail2"] or "mail1, mail2"
    $m->To( $destinataire );
    // Set Subject
    $m->Subject( $sujet );
    // Set Body
    $m->Body( $message, "iso-8859-15");
    // Set Reply To
    $m->ReplyTo( $repondre );
    // Set Organization
    // $m->Organization( "Grr" );
    // Set Format html / text
    $m->Format("text");
    // Set Priority
    // 1 (Highest); 2 (High); 3 (Normal); 4 (Low); 5 (Lowest)
    $m->Priority(3);
    // Attach a file (it can be an array or a string)
    // ["file1", "file2"] or "file"
    // $m->Attach( "toto.gif" );
    // Get a reception (by default not)
    // $m->Receipt();
    // Send the mail
    $m->Send();
    // Get the source mail
    $m->Get();


}
function getUserName()
{
    if (isset($_SESSION['login'])) return $_SESSION['login'];
}

/* getWritable($creator, $user, $id)
 *
 * Determines if a user is able to modify an entry
 *
 * $creator - The creator of the entry
 * $user    - Who wants to modify it
 * $id -   Which room are we checking
 *
 * Returns:
 *   0        - The user does not have the required access
 *   non-zero - The user has the required access
 */
function getWritable($creator, $user, $id)
{
    $id_room = grr_sql_query1("SELECT room_id FROM grr_entry WHERE id='".protect_data_sql($id)."'");
    $dont_allow_modify = grr_sql_query1("select dont_allow_modify from grr_room where id = '".$id_room."'");
    if ($dont_allow_modify != 'y') {  // si la valeur de dont_allow_modify est "n" ou bien "-1"
        // Always allowed to modify your own stuff
        if($creator == $user)
        return 1;
    }
    // allowed to modify stuffs if utilisateur has spécifics rights or statut = admin

    if(authGetUserLevel($user,$id_room) > 2)
        return 1;

    // Unathorised access
    return 0;
}

/* authGetUserLevel($user,$id,$type)
 *
 * Determine le niveau d'accès de l'utilisateur
 *
 * $user - l'identifiant de l'utilisateur
 * $id -   l'identifiant de la ressource ou du domaine
 * $type - argument optionnel : 'room' (par défaut) si $id désigne une ressource et 'area' si $id désigne un domaine.
 *
 * Retourne le niveau d'accès de l'utilisateur
 */
function authGetUserLevel($user,$id, $type='room')
{
    $level = "";
    // User not logged in, user level '0'
    if(!isset($user)) return 0;
    $res = grr_sql_query("SELECT statut FROM grr_utilisateurs WHERE login ='".protect_data_sql($user)."'");
    if (!$res || grr_sql_count($res) == 0) return 0;
    $status = mysqli_fetch_row($res);
    if (strtolower($status[0]) == 'visiteur') return 1;
    if (strtolower($status[0]) == 'administrateur') return 5;
    if (strtolower($status[0]) == 'utilisateur') {
        if ($type == 'room') {
        // On regarde si l'utilisateur est gestionnaire des réservations pour une ressource
            $res2 = grr_sql_query("SELECT * FROM grr_utilisateurs u, grr_j_user_room j
            WHERE u.login=j.login and u.login = '".protect_data_sql($user)."' and j.id_room='".protect_data_sql($id)."'");
            if (grr_sql_count($res2) > 0)
                return 3;
        // On regarde si l'utilisateur est administrateur du domaine auquel la ressource $id appartient
            $id_area = grr_sql_query1("select area_id from grr_room where id='".protect_data_sql($id)."'");
            $res3 = grr_sql_query("SELECT u.login FROM grr_utilisateurs u, grr_j_useradmin_area j
            WHERE (u.login=j.login and j.id_area='".protect_data_sql($id_area)."' and u.login='".protect_data_sql($user)."')");
            if (grr_sql_count($res3) > 0)
                return 4;
            // Sinon il s'agit d'un simple utilisateur
            return 2;

        }
        // On regarde si l'utilisateur est administrateur d'un domaine
        if ($type == 'area') {
            if ($id == '-1') {
                //On regarde si l'utilisateur est administrateur d'un domaine quelconque
                $res2 = grr_sql_query("SELECT u.login FROM grr_utilisateurs u, grr_j_useradmin_area j
                WHERE (u.login=j.login and u.login='".protect_data_sql($user)."')");
                if (grr_sql_count($res2) > 0)
                    return 4;
            } else {
                //On regarde si l'utilisateur est administrateur du domaine dont l'id est $id
                $res3 = grr_sql_query("SELECT u.login FROM grr_utilisateurs u, grr_j_useradmin_area j
                WHERE (u.login=j.login and j.id_area='".protect_data_sql($id)."' and u.login='".protect_data_sql($user)."')");
                if (grr_sql_count($res3) > 0)
                    return 4;
            }
        }

    }
}

/* authUserAccesArea($user,$id)
 *
 * Determines if the user access area
 *
 * $user - The user name
 * $id -   Which area are we checking
 *
 */
function authUserAccesArea($user,$id)
{
    if ($id=='') {
        return 0;
        die();
    }
    $sql = "SELECT * FROM grr_utilisateurs WHERE (login = '".protect_data_sql($user)."' and statut='administrateur')";
    $res = grr_sql_query($sql);
    if (grr_sql_count($res) != "0") return 1;

    $sql = "SELECT * FROM grr_area WHERE (id = '".protect_data_sql($id)."' and access='r')";
    $res = grr_sql_query($sql);
    $test = grr_sql_count($res);
    if ($test == "0") {
        return 1;
    } else {
        $sql2 = "SELECT * FROM grr_j_user_area WHERE (login = '".protect_data_sql($user)."' and id_area = '".protect_data_sql($id)."')";
        $res2 = grr_sql_query($sql2);
        $test2 = grr_sql_count($res2);
        if ($test2 != "0") {
            return 1;
        } else {
            return 0;
        }
    }
}
// function UserRoomMaxBooking
// Cette fonction teste si l'utilisateur a la possibilité d'effectuer une réservation, compte tenu
// des limitations éventuelles de la ressources et du nombre de réservations déjà effectuées.
// Pour championnat individuel et mode solo les résa ne sont pas comptabilisées
function UserRoomMaxBooking($user, $id_room, $number) {
  if ($id_room == '') return 0;
  // On regarde si le nombre de réservation de la ressource est limité
  $sql = "SELECT max_booking FROM grr_room WHERE id = '".protect_data_sql($id_room)."'";
  $result = grr_sql_query1($sql);
  if ($result > 0) {
     if(authGetUserLevel($user,$id_room) < 2 ) {
       return 0;
     } else if(authGetUserLevel($user,$id_room) == 2) {
       $day   = date("d");
        $month = date("m");
        $year  = date("Y");
        $hour  = date("H");
        $minute = date("i");
        $now = mktime($hour, $minute, 0, $month, $day, $year);
        $max_booking = grr_sql_query1("SELECT max_booking FROM grr_room WHERE id='".protect_data_sql($id_room)."'");
		$adversaire = $_SESSION['nom']." ".$_SESSION['prenom'];
		$sql2 = "SELECT * FROM grr_entry WHERE (room_id = '".protect_data_sql($id_room)."' and description != 'championnat individuel' and (create_by = '".protect_data_sql($user)."' OR description = '".$adversaire."') and end_time > '$now')";
        $res = grr_sql_query($sql2);
        $nb_bookings = grr_sql_count($res) + $number;
        if ($nb_bookings > $max_booking) {
          return 0;
        } else {
          return 1;
        }
      } else {
        // l'utilisateur est soit admin, soit administrateur de la ressource.
        return 1;
      }
    } else if ($result == 0) {
     if(authGetUserLevel($user,$id_room) >= 3) {
        return 1;
     } else {
        return 0;
     }
  } else {
     return 1;
  }
}

// function AdvRoomMaxBooking
// Cette fonction teste si l'adversaire a la possibilité d'être choisi, compte tenu
// des limitations éventuelles de la ressources et du nombre de réservations déjà effectuées.
// Pour championnat individuel, invite et mode solo les résa ne sont pas comptabilisées
function AdvRoomMaxBooking($user, $description, $id_room, $number) {
  if ($id_room == '') return 0;
  // On regarde si le nombre de réservation de la ressource est limité
  $sql = "SELECT max_booking FROM grr_room WHERE id = '".protect_data_sql($id_room)."'";
  $result = grr_sql_query1($sql);
  if ($result > 0) {
     if(authGetUserLevel($user,$id_room) < 2 ) {
       return 0;
     } else if(authGetUserLevel($user,$id_room) == 2) {
       $day   = date("d");
        $month = date("m");
        $year  = date("Y");
        $hour  = date("H");
        $minute = date("i");
        $now = mktime($hour, $minute, 0, $month, $day, $year);
        $max_booking = grr_sql_query1("SELECT max_booking FROM grr_room WHERE id='".protect_data_sql($id_room)."'");
		//recherche du login de l'adversaire pour vérifier s'il a déjà des réservations en cours (vérification avec la fonction AdvRoomMaxbooking)
			if (isset($description)){
			$tableau = explode(" ", $description);
			$exp = count($tableau);
			if ($exp == 2){
			$nomadv = $tableau[0];
			$prenomadv = $tableau[1];
			$sql = "select login from grr_utilisateurs where nom ='".$nomadv."' and prenom = '".$prenomadv."'order by nom";
			$adv = grr_sql_query($sql);
	
			for ($i = 0; ($row = grr_sql_row($adv, $i)); $i++)
			$adversaire = $row[0];
			}
			}
        $sql2 = "SELECT * FROM grr_entry WHERE (room_id = '".protect_data_sql($id_room)."' and create_by = '".protect_data_sql($adversaire)."' and end_time > '$now')";
        $res = grr_sql_query($sql2);
        $nb_bookings = grr_sql_count($res) + $number;
        if ($nb_bookings > $max_booking) {
          return 0;
        } else {
          return 1;
        }
      } else {
        // l'utilisateur est soit admin, soit administrateur de la ressource.
        return 1;
      }
    } else if ($result == 0) {
     if(authGetUserLevel($user,$id_room) >= 3) {
        return 1;
     } else {
        return 0;
     }
  } else {
     return 1;
  }
}

// function UserAreaGroup
// Cette fonction teste si l'utilisateur appartient au groupe d'utilisateurs de l'area sur 
// laquelle il veut réserver.
function UserAreaGroup($user, $id_area) {

	// On teste si l'utilisateur est administrateur
  $sql = "select statut from grr_utilisateurs WHERE login = '".protect_data_sql($user)."'";
  $statut_user = grr_sql_query1($sql);
  if ($statut_user == 'administrateur') {
    return true;
    die();
  }
  
  // On cherche le groupe de l'utilisateur
  $sql2= "SELECT group_id FROM grr_utilisateurs WHERE login='".protect_data_sql($user)."'"; 
  $result_group = grr_sql_query1($sql2);
  
  global $enable_periods;
  // On regarde si id de l'area est le même que le groupe de l'utilisateur qui réserve
  $sql = "SELECT group_id FROM grr_area WHERE id = '".protect_data_sql($id_area)."'";
  $result_area = grr_sql_query1($sql);
  if (($result_group == $result_area) OR ($enable_periods != 'n')) {
       return true;
	   die();
     } else {
       return false;
     }
}

// function verif_booking_date($user, $id, $date_booking, $date_now)
// $user : le login de l'utilisateur
// $id : l'id de la résa. Si -1, il s'agit d'une nouvelle réservation
// $id_room : id de la ressource
// $date_booking : la date de la réservation (n'est utile que si $id=-1)
// $date_now : la date actuelle
//
function verif_booking_date($user, $id, $id_room, $date_booking, $date_now, $enable_periods, $endtime='') {
  global $allow_user_delete_after_beginning, $correct_diff_time_local_serveur;

  // On teste si l'utilisateur est administrateur
  $sql = "select statut from grr_utilisateurs WHERE login = '".protect_data_sql($user)."'";
  $statut_user = grr_sql_query1($sql);
  if ($statut_user == 'administrateur') {
    return true;
    die();
  }

  // A-t-on le droit d'agir dans le passé ?
  $allow_action_in_past = grr_sql_query1("select allow_action_in_past from grr_room where id = '".protect_data_sql($id_room)."'");
  if ($allow_action_in_past == 'y') {
    return true;
    die();
  }

  // Correction de l'avance en nombre d'heure du serveur sur les postes clients
  if ((isset($correct_diff_time_local_serveur)) and ($correct_diff_time_local_serveur!=0))
      $date_now -= 3600*$correct_diff_time_local_serveur;

  // Créneaux basés sur les intitulés
  // Dans ce cas, on prend comme temps présent le jour même à minuit.
  // Cela signifie qu'il est possible de modifier/réserver/supprimer tout au long d'une journée
  // même si l'heure est passée.
  // Cela demande donc à être amélioré en introduisant pour chaque créneau une heure limite de réservation.
  if ($enable_periods == "y") {
      $month =  date("m",$date_now);
      $day =  date("d",$date_now);
      $year = date("Y",$date_now);
      $date_now = mktime(0, 0, 0, $month, $day, $year);
  }


  if ($id != -1) {
    // il s'agit de l'edition d'une réservation existante
    if (($endtime != '') and ($endtime < $date_now)) {
      return false;
      die();
    }

    if (isset($allow_user_delete_after_beginning) and ($allow_user_delete_after_beginning == 1))
        $sql = "SELECT end_time FROM grr_entry WHERE id = '".protect_data_sql($id)."'";
    else
        $sql = "SELECT start_time FROM grr_entry WHERE id = '".protect_data_sql($id)."'";
    $date_booking = grr_sql_query1($sql);
    if ($date_booking < $date_now) {
      return false;
      die();
    } else {
      return true;
    }
  } else {
    if ($date_booking>$date_now) {
      return true;
    } else {
      return false;
    }
  }
}
// function verif_booking_double($create_by, $name, $description ,$id_room, $starttime)
// $create_by: le login de l'utilisateur
// $name : nom prénom de la personne qui réserve
// $description : nom de l'adversaire
// $id_room : id de la ressource
// $starttime : début de résa
//
function verif_booking_double($create_by, $name, $description, $area, $id_room, $starttime, $endtime) {
  
 // On teste si l'utilisateur est administrateur général, de domaine ou de ressource !
  //admin de domaine
  $sqldom = "select id_area from grr_j_useradmin_area WHERE login = '".protect_data_sql($create_by)."' AND id_area = '".protect_data_sql($area)."'";
  $statut_user_dom = grr_sql_query($sqldom);
  $result_dom = grr_sql_count($statut_user_dom);
  
  //admin de ressource
  $sqlres = "select id_room from grr_j_user_room WHERE login = '".protect_data_sql($create_by)."' AND id_room = '".protect_data_sql($id_room)."'";
  $statut_user_res = grr_sql_query($sqlres);
  $result_res = grr_sql_count($statut_user_res);
  
  $sql = "select statut from grr_utilisateurs WHERE login = '".protect_data_sql($create_by)."'";
  $statut_user = grr_sql_query1($sql);
  if (($statut_user == 'administrateur') OR ($result_dom == 1) OR ($result_res == 1)){
    return true;
    die();
  }
  
  //on recherche si les joueurs qui réservent ont déjà une réservation avant ou après celle souhaitée !
$sql = " SELECT id FROM grr_entry WHERE name = '".protect_data_sql($description)."' AND room_id = '".protect_data_sql($id_room)."' AND end_time= '".protect_data_sql($starttime)."'
						 OR description = '".protect_data_sql($description)."' AND description !='invite' AND description !='solo' AND room_id = '".protect_data_sql($id_room)."' AND end_time= '".protect_data_sql($starttime)."'
						 OR name = '".protect_data_sql($description)."' AND room_id = '".protect_data_sql($id_room)."' AND start_time= '".protect_data_sql($endtime)."'
						 OR description = '".protect_data_sql($description)."' AND description !='invite' AND description !='solo' AND room_id = '".protect_data_sql($id_room)."' AND start_time= '".protect_data_sql($endtime)."'
						 OR name = '".protect_data_sql($name )."' AND room_id = '".protect_data_sql($id_room)."' AND end_time= '".protect_data_sql($starttime)."'
						 OR description = '".protect_data_sql($name )."' AND room_id = '".protect_data_sql($id_room)."' AND end_time= '".protect_data_sql($starttime)."'
						 OR name = '".protect_data_sql($name )."' AND room_id = '".protect_data_sql($id_room)."' AND start_time= '".protect_data_sql($endtime)."'
						 OR description = '".protect_data_sql($name )."' AND room_id = '".protect_data_sql($id_room)."' AND start_time= '".protect_data_sql($endtime)."'";
$result = grr_sql_query($sql);
if (mysqli_num_rows($result) == 1){
      return false;
	  die();
          } else {
     return true;
	 die();
    }
}

//Permet de limiter le nombre de réservation par individu, par court et par semaine !
function verif_booking_week($create_by, $name, $description, $id_room, $starttime, $endtime) {
  
  // On teste si l'utilisateur est administrateur
  $sql = "select statut from grr_utilisateurs WHERE login = '".protect_data_sql($create_by)."'";
  $statut_user = grr_sql_query1($sql);
  if ($statut_user == 'administrateur') {
    return true;
    die();
  }

//Recherche du nombre en temps UTC du début de la semaine de demande de réservation (604800 = 7*24*60*60)
// 259200 correspond au décalage de 3 jours par rapport au jeudi 1 JANVIER 1970

$reserv_week = (int)(($starttime + 259200)/604800);		//nombre de semaine en ENTIER
$debsem = (($reserv_week) * 604800);
$begin_week = $debsem - 259200;
$end_week = $begin_week + 604800;


 
 // On regarde si le nombre de réservation de la ressource est limité
  $sql = "SELECT max_booking_week FROM grr_room WHERE id = '".protect_data_sql($id_room)."'";
  $result_week = grr_sql_query1($sql);



  //on recherche si les joueurs qui réservent ont déjà dépassé leur quota pour la semaine !
  //premier test sur le paramètre 'championnat individuel' pour permettre aux joueurs d'éffecteur plusieurs résa de champ ind malgré la restriction du nombre de résa sur le court
	if ($description != 'championnat individuel')
	{
	$sql2 = " SELECT id FROM grr_entry WHERE name = '".protect_data_sql($name)."' AND room_id = '".protect_data_sql($id_room)."'AND start_time > '".protect_data_sql($begin_week)."'AND end_time < '".protect_data_sql($end_week)."'
						OR description = '".protect_data_sql($name)."' AND room_id = '".protect_data_sql($id_room)."'AND start_time > '".protect_data_sql($begin_week)."'AND end_time < '".protect_data_sql($end_week)."'";
						
	$result2 = grr_sql_query($sql2);
	$sql3 = " SELECT id FROM grr_entry WHERE  name = '".protect_data_sql($description)."' AND room_id = '".protect_data_sql($id_room)."'AND start_time > '".protect_data_sql($begin_week)."'AND end_time < '".protect_data_sql($end_week)."'
						OR description = '".protect_data_sql($description)."' AND room_id = '".protect_data_sql($id_room)."'AND start_time > '".protect_data_sql($begin_week)."'AND end_time < '".protect_data_sql($end_week)."'";
	$result3 = grr_sql_query($sql3);
	} else {
	$result2 = '0';
	$result3 = '0';
	}
	

if ($result_week == -1 ) {
      return true;
	  die();
          } elseif ((mysqli_num_rows($result2) < $result_week) AND (mysqli_num_rows($result3) < $result_week)) {
     return true;
	 die();
    }else {
     return false;
	 die();
    }

}

//Permet de limiter le nombre de réservation sur l'ensemble des ressources ! (joueur qui réserve et adversaire)
function verif_booking_max_all_ressources($create_by, $name, $description, $area, $room_id, $starttime) {

  
  // On teste si l'utilisateur est administrateur général, de domaine ou de ressource !
  //admin de domaine
  $sqldom = "select id_area from grr_j_useradmin_area WHERE login = '".protect_data_sql($create_by)."' AND id_area = '".protect_data_sql($area)."'";
  $statut_user_dom = grr_sql_query($sqldom);
  $result_dom = grr_sql_count($statut_user_dom);
  
  //admin de ressource
  $sqlres = "select id_room from grr_j_user_room WHERE login = '".protect_data_sql($create_by)."' AND id_room = '".protect_data_sql($room_id)."'";
  $statut_user_res = grr_sql_query($sqlres);
  $result_res = grr_sql_count($statut_user_res);
  
  $sql = "select statut from grr_utilisateurs WHERE login = '".protect_data_sql($create_by)."'";
  $statut_user = grr_sql_query1($sql);
  if (($statut_user == 'administrateur') OR ($result_dom == 1) OR ($result_res == 1)){
    return true;
    die();
  }
	$hour  = date("H");
	$min   = date("i");
	$day   = date("d");
    $month = date("m");
    $year  = date("Y");
    $date_now = mktime($hour,$min,0,$month,$day,$year);

	
	//vérifions si la paramètre maxallressources !=-1 
	if (getSettingValue("maxallressources") == -1) {
	return true;
	  die();
	} else {
		//on recherche si les joueurs qui réservent vont dépasser leur quota de réservation active pour toutes les installations !
		//recherche pour le joueur qui reserve
	
		//Recherche nom prénom du joueur qui réserve
		$query = "SELECT nom, prenom FROM grr_utilisateurs WHERE login='$create_by'";
		$resul = grr_sql_query ($query) or die ("Erreur pendant la requête");
		$line = mysqli_fetch_array ($resul);
		list($nomres, $prenomres)=$line;
	
		$sql = " SELECT id FROM grr_entry WHERE ((create_by = '".protect_data_sql($create_by)."' AND (description <> 'championnat individuel')) OR description = '".protect_data_sql($nomres)." ".protect_data_sql($prenomres)."') AND end_time > '".protect_data_sql($date_now)."'";
		$res = grr_sql_query($sql);
		$result = grr_sql_count($res);
	
	
		//recherche pour le joueur  adversaire
		$sql1 = " SELECT id FROM grr_entry WHERE ((( description = '".protect_data_sql($description)."' AND description <> 'invite') OR name = '".protect_data_sql($description)."') AND description <> 'championnat individuel') AND end_time > '".protect_data_sql($date_now)."'";
		$res1 = grr_sql_query($sql1);
		$result1 = grr_sql_count($res1);	
		
		if (( $result < getSettingValue("maxallressources")) AND ($result1 < getSettingValue("maxallressources"))){
		return true;
		die();
		} else {
		return false;
		die();
		}
	}
}

//vérification si un utilisateur peut réserver avec un invité (le compteur doit être alimenté pour cela dans l'interface d'administration)
function verif_invite($user, $description) {
  
	// On teste si l'utilisateur est administrateur
	$sql = "select statut from grr_utilisateurs WHERE login = '".protect_data_sql($user)."'";
	$statut_user = grr_sql_query1($sql);
	if ($statut_user == 'administrateur') {
    return true;
    die();
	}elseif($description == 'invite ')
	{
	//on recherche si l'invité a encore du crédit et on décrémente de 1
	$sql = " SELECT invite FROM grr_utilisateurs WHERE login = '".protect_data_sql($user)."'";
	$result = grr_sql_query1($sql);
	  if ( $result[0] == 0){
      return false;
	  die();
      } else {
	   $result--;
	   grr_sql_query("UPDATE grr_utilisateurs SET invite = '".protect_data_sql($result)."' WHERE login = '".protect_data_sql($user)."'");
       return true;
	   die();
      }
	}else{
	return true;
	die();
	}
}


//vérification si un utilisateur réserve une autre ressource à la même date/heure.
function verif_booking_same_date_other_room ($user, $name, $description, $area, $room_id, $starttime) {
  
// On teste si l'utilisateur est administrateur général, de domaine ou de ressource !
  //admin de domaine
  $sqldom = "select id_area from grr_j_useradmin_area WHERE login = '".protect_data_sql($user)."' AND id_area = '".protect_data_sql($area)."'";
  $statut_user_dom = grr_sql_query($sqldom);
  $result_dom = grr_sql_count($statut_user_dom);
  
  //admin de ressource
  $sqlres = "select id_room from grr_j_user_room WHERE login = '".protect_data_sql($user)."' AND id_room = '".protect_data_sql($room_id)."'";
  $statut_user_res = grr_sql_query($sqlres);
  $result_res = grr_sql_count($statut_user_res);
  
  $sql = "select statut from grr_utilisateurs WHERE login = '".protect_data_sql($user)."'";
  $statut_user = grr_sql_query1($sql);
  if (($statut_user == 'administrateur') OR ($result_dom == 1) OR ($result_res == 1)){
    return true;
    die();
  }
//on recherche si la résa existe avec le nom d'une autre ressource
$sql = " SELECT id FROM grr_entry WHERE ((name ='".protect_data_sql($name)."' OR name ='".protect_data_sql($description)."') OR (description ='".protect_data_sql($name)."' OR (description ='".protect_data_sql($description)."' AND description != 'invite' AND description != 'solo'))) AND start_time= '".protect_data_sql($starttime)."'";
$result = grr_sql_query($sql);
if (mysqli_num_rows($result) == 0){
      return true;
	  die();
          }else{
     return false;
	 die();
    }

}


// function verif_delais_max_resa_room($user, $id_room, $date_booking)
// $user : le login de l'utilisateur
// $id_room : l'id de la ressource. Si -1, il s'agit d'une nouvelle ressoure
// $date_booking : la date de la réservation (n'est utile que si $id=-1)
// $date_now : la date actuelle
//
function verif_delais_max_resa_room($user, $id_room, $date_booking) {
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    $datenow = mktime(0,0,0,$month,$day,$year);

  if(authGetUserLevel($user,$id_room) >= 3) {
  // On teste si l'utilisateur est administrateur
    return true;
    die();
  }
  $delais_max_resa_room = grr_sql_query1("select delais_max_resa_room from grr_room where id='".protect_data_sql($id_room)."'");
  if ($delais_max_resa_room == -1) {
    return true;
    die();
  } else if ($datenow + $delais_max_resa_room*24*3600 +1 < $date_booking) {
    return false;
    die();
  } else {
    return true;
    die();
  }
}

// function verif_delais_min_resa_room($user, $id_room, $date_booking)
// $user : le login de l'utilisateur
// $id_room : l'id de la ressource. Si -1, il s'agit d'une nouvelle ressoure
// $date_booking : la date de la réservation (n'est utile que si $id=-1)
// $date_now : la date actuelle
//
function verif_delais_min_resa_room($user, $id_room, $date_booking) {
  if(authGetUserLevel($user,$id_room) >= 3) {
  // On teste si l'utilisateur est administrateur
    return true;
    die();
  }
  $delais_min_resa_room = grr_sql_query1("select delais_min_resa_room from grr_room where id='".protect_data_sql($id_room)."'");
  if ($delais_min_resa_room == 0) {
    return true;
    die();
  } else {
    $hour = date("H");
    $minute  = date("i")+$delais_min_resa_room;
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    $date_limite = mktime($hour,$minute,0,$month,$day,$year);
    if ($date_limite > $date_booking) {
        return false;
        die();
    } else {
        return true;
        die();
    }
  }
}

// Vérifie que la date de confirmation est inférieur à la date de début de réservation
function verif_date_option_reservation($option_reservation, $starttime) {
    if ($option_reservation == -1)
        return true;
    else {
        $day   = date("d",$starttime);
        $month = date("m",$starttime);
        $year  = date("Y",$starttime);
        $date_starttime = mktime(0,0,0,$month,$day,$year);
        if ($option_reservation < $date_starttime)
            return true;
        else
            return false;
    }
}


/* showAccessDenied()
 *
 * Displays an appropate message when access has been denied
 *
 * Returns: Nothing
 */
function showAccessDenied($day, $month, $year, $area, $back)
{
    global $vocab, $authentification_obli;
    if (($authentification_obli==0) and (!isset($_SESSION['login']))) {
        $type_session = "no_session";
    } else {
        $type_session = "with_session";
    }
    print_header($day, $month, $year, $area,$type_session);
    ?>
   <H1><?php echo get_vocab("accessdenied")?></H1>
   <P>
   <?php echo get_vocab("norights")?>
   </P>
   <P>
   <A HREF="<?php echo $back; ?>"><?php echo get_vocab("returnprev"); ?></A>
   </P>
</BODY>
</HTML>
<?php
}

/* showAccessDeniedMaxBookings()
 *
 * Displays an appropate message when access has been denied
 *
 * Returns: Nothing
 */
function showAccessDeniedMaxBookings($day, $month, $year, $area, $id_room,$back)
{
    global $vocab;

    print_header($day, $month, $year, $area);
    ?>
    <H1><?php echo get_vocab("accessdenied")?></H1>
    <P>
    <?php
    $max_booking = grr_sql_query1("SELECT max_booking FROM grr_room WHERE id='".protect_data_sql($id_room)."'");
    echo get_vocab("msg_max_booking").$max_booking."<br><br>".get_vocab("accessdeniedtoomanybooking");
    ?>
    </P>
    <P>
    <A HREF="<?php echo $back; ?>"><?php echo get_vocab("returnprev"); ?></A>
    </P>
    </BODY>
    </HTML>
    <?php
}


function check_begin_end_bookings($day, $month, $year) {
    $date = mktime(0,0,0,$month,$day,$year);
    if (($date < getSettingValue("begin_bookings")) or ($date > getSettingValue("end_bookings")))
    return -1;
}
function showNoBookings($day, $month, $year, $area, $back, $type_session)
{
    global $vocab;

    print_header($day, $month, $year, $area,$type_session);
?>
  <H1><?php echo get_vocab("accessdenied")?></H1>
  <P>
   <?php echo get_vocab("nobookings")?>
  </P>
  <P>
  <?php if ($back != "") { ?>
   <A HREF="<?php echo $back; ?>"><?php echo get_vocab("returnprev"); ?></A>
   <?php }
  ?>
  </P>
 </BODY>
</HTML>
<?php
}

function date_time_string($t,$dformat)
{
    global $twentyfourhour_format;
    if ($twentyfourhour_format)
                $timeformat = "%T";
    else
    {
                $ampm = date("a",$t);
                $timeformat = "%I:%M:%S$ampm";
    }
    return strftime($dformat.$timeformat, $t);
}

# Convert a start period and end period to a plain language description.
# This is similar but different from the way it is done in view_entry.
function describe_period_span($starts, $ends)
{
    global $enable_periods, $periods_name, $vocab, $duration;
    list( $start_period, $start_date) =  period_date_string($starts);
    list( , $end_date) =  period_date_string($ends, -1);
    $duration = $ends - $starts;
    toPeriodString($start_period, $duration, $dur_units);
    if ($duration > 1) {
        list( , $start_date) =  period_date_string($starts);
        list( , $end_date) =  period_date_string($ends, -1);
        $temp = $start_date . " ==> " . $end_date;
    } else {
        $temp = $start_date . " - " . $duration . " " . $dur_units;
    }
    return $temp;
}



#Convertit l'heure de début et de fin en période.
function describe_span($starts, $ends, $dformat)
{
    global $vocab, $twentyfourhour_format;
    $start_date = utf8_strftime($dformat, $starts);
        if ($twentyfourhour_format)
    {
                $timeformat = "%T";
    }
    else
    {
                $ampm = date("a",$starts);
                $timeformat = "%I:%M:%S$ampm";
    }
    $start_time = strftime($timeformat, $starts);
    $duration = $ends - $starts;
    if ($start_time == "00:00:00" && $duration == 60*60*24)
        return $start_date . " - " . get_vocab("all_day");
    toTimeString($duration, $dur_units);
    return $start_date . " " . $start_time . " - " . $duration . " " . $dur_units;
}

// Opération inverse de htmlentities
function unhtmlentities ($string)
    {
       $trans_tbl = get_html_translation_table (HTML_ENTITIES,ENT_QUOTES);
       if( $trans_tbl["'"] != '&#039;' ) { # some versions of PHP match single quotes to &#39;
            $trans_tbl["'"] = '&#039;';
    }
       $trans_tbl = array_flip ($trans_tbl);
       return strtr ($string, $trans_tbl);
    }

function get_planning_area_values($id_area) {
    global $resolution, $morningstarts, $eveningends, $eveningends_minutes, $weekstarts, $twentyfourhour_format, $enable_periods, $periods_name, $display_day, $nb_display_day;
    $sql = "SELECT calendar_default_values, resolution_area, morningstarts_area, eveningends_area, eveningends_minutes_area, weekstarts_area, twentyfourhour_format_area, enable_periods, display_days
    FROM grr_area
    WHERE id = '".protect_data_sql($id_area)."'";
    $res = grr_sql_query($sql);
    if (! $res) {
    //    fatal_error(0, grr_sql_error());
        include "trailer.inc.php";
        exit;
    }
    $row_ = grr_sql_row($res, 0);

    $nb_display_day = 0;
    for ($i = 0; $i < 7; $i++)
    {
      if (substr($row_[8],$i,1) == 'y') {
          $display_day[$i] = 1;
          $nb_display_day++;
      } else
          $display_day[$i] = 0;
    }


    // Créneaux basés sur les intitulés
    if ($row_[7] == 'y') {
        $resolution = 60;
        $morningstarts = 12;
        $eveningends = 12;
        $sql_periode = grr_sql_query("SELECT nom_periode FROM grr_area_periodes where id_area='".$id_area."'");
        $eveningends_minutes = grr_sql_count($sql_periode)-1;
        $i = 0;
        while ($i < grr_sql_count($sql_periode)) {
            $periods_name[$i] = grr_sql_query1("select nom_periode FROM grr_area_periodes where id_area='".$id_area."' and num_periode= '".$i."'");
            $i++;
        }
        $enable_periods = "y";
        $weekstarts = $row_[5];
        $twentyfourhour_format = $row_[6];
    // Créneaux basés sur le temps
    } else {
        if ($row_[0] != 'y') {
            $resolution = $row_[1];
            $morningstarts = $row_[2];
            $eveningends = $row_[3];
            $eveningends_minutes = $row_[4];
            $enable_periods = "n";
            $weekstarts = $row_[5];
            $twentyfourhour_format = $row_[6];
        }
    }
}

// Dans le cas ou $unicode_encoding = 1 (UTF-8) cette fonction encode les chaînes présentes dans
// le code "en dur", en UTF-8 avant affichage
function encode_message_utf8($tag)
{
  global $charset_html, $unicode_encoding;

  if ($unicode_encoding)
  {
    return iconv($charset_html,"utf-8",$tag);
  }
  else
  {
    return $tag;
  }
}

function removeMailUnicode($string)
{
    global $unicode_encoding, $charset_html;
    //
    if ($unicode_encoding)
    {
        return @iconv("utf-8", $charset_html, $string);
    }
    else
    {
        return $string;
    }
}

// Cette fonction vérifie une fois par jour si le délai de confirmation des réservations est dépassé
// Si oui, les réservations concernées sont supprimées et un mail automatique est envoyé.
function verify_confirm_reservation() {
    global $dformat;
    $day   = date("d");
    $month = date("m");
    $year  = date("Y");
    $date_now = mktime(0,0,0,$month,$day,$year);
    if ((getSettingValue("date_verify_reservation") == "") or (getSettingValue("date_verify_reservation") < $date_now )) {
        $res = grr_sql_query("select id from grr_room where delais_option_reservation > 0");
        if (! $res) {
            //    fatal_error(0, grr_sql_error());
            include "trailer.inc.php";
            exit;
        } else {
            for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
                $res2 = grr_sql_query("select id from grr_entry where option_reservation < '".$date_now."' and option_reservation != '-1' and room_id='".$row[0]."'");
                if (! $res2) {
                    //    fatal_error(0, grr_sql_error());
                    include "trailer.inc.php";
                    exit;
                } else {
                    for ($j = 0; ($row2 = grr_sql_row($res2, $j)); $j++) {
                        if (getSettingValue("automatic_mail") == 'yes') echo send_mail($row2[0],4,$dformat);
                        // On efface la réservation
                        grr_sql_command("DELETE FROM grr_entry WHERE id=" . $row2[0]);

                     }
                }
            }
        }
        if (!saveSetting("date_verify_reservation", $date_now)) {
            echo "Erreur lors de l'enregistrement de date_verify_reservation !<br>";
            die();
        }
    }
}

function est_hors_reservation($time) {
    $test = grr_sql_query1("select DAY from grr_calendar where DAY = '".$time."'");
    if ($test != -1)
        return TRUE;
    else
        return FALSE;

}

function resa_est_hors_reservation($start_time,$end_time) {
    $test = grr_sql_query1("select DAY from grr_calendar where DAY >= '".$start_time."' and DAY <= '".$end_time."'");
    if ($test != -1)
        return TRUE;
    else
        return FALSE;

}
// Les lignes suivantes permettent la compatibilité de GRR avec la variables register_global à off
unset($day);
if (isset($_GET["day"])) {
    $day = $_GET["day"];
    settype($day,"integer");
    if ($day < 1) $day = 1;
    if ($day > 31) $day = 31;
}
unset($month);
if (isset($_GET["month"])) {
    $month = $_GET["month"];
    settype($month,"integer");
    if ($month < 1) $month = 1;
    if ($month > 12) $month = 12;
}
unset($year);
if (isset($_GET["year"])) {
    $year = $_GET["year"];
    settype($year,"integer");
    if ($year < 1900) $year = 1900;
    if ($year > 2100) $year = 2100;
}

unset($room);
$room = isset($_GET["room"]) ? $_GET["room"] : NULL;
settype($room,"integer");

unset($area);
$area = isset($_GET["area"]) ? $_GET["area"] : NULL;
settype($area,"integer");

function get_extension($filename)
{
   $parts = explode('.',$filename);
   $last = count($parts) - 1;
   $ext = $parts[$last];
   return $ext;
}

// Fonction verif_group
//permet de vérifier si l'utilisateur appartient à un group
function verif_group($user, $group) {
  
  
  //on recherche si l'utilisateur fait partie du bon groupe $group
$sql = " SELECT group FROM grr_entry WHERE login = '".protect_data_sql($user)."'";
$result = grr_sql_query($sql);
if ((mysqli_num_rows($result) != 0) and (mysqli_num_rows($result) == $group)){
      return true;
	  die();
          } else {
     return false;
	 die();
    }
}
?>