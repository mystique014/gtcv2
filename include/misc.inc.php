<?php
#########################################################################
#                           misc.inc.php                                #
#                                                                       #
#                       fichier de variables diverses                   #
#                                                                       #
#                  Dernière modification : 10/07/2006                   #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau
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

################################
# Development information
#################################
$grr_devel_email = "";
$grr_devel_url = "";
// Numéro de version actuel
$version_grr = "2.0";
// Numéro de sous-version actuel (a, b, ...)
// Utilisez cette variable pour des versions qui corrigent la la version finale sans toucher à la base.
$sous_version_grr = "";
// Numéro de la release candidate (doit être strictement inférieure à 9). Laisser vide s'il s'agit de la version stable.
$version_grr_RC = "";

# Liste des tables
$liste_tables = array(
"_abt",
"_annonces",
"_area",
"_area_periodes",
"_calendar",
"_categorie_compta",
"_compta",
"_compte_tresorerie",
"_entry",
"_group",
"_group_calendar",
"_group_repeat",
"_j_mailuser_room",
"_j_site_area",
"_j_type_area",
"_j_useradmin_area",
"_j_useradmin_site",
"_j_user_area",
"_j_user_room",
"_log",
"_overload",
"_repeat",
"_room",
"_site",
"_setting",
"_type_area",
"_utilisateurs"
);

# Liste des feuilles de style
$liste_themes = array(
"default",
"forestier",
"or",
"orange",
"argent",
"volcan"
);

# Liste des noms des styles
$liste_name_themes = array(
"Grand bleu",
"Forestier",
"Doré",
"Orange",
"Argent",
"Volcan"
);

# Liste des langues
$liste_language = array(
"fr",
"de",
"en",
"it",
"es"
);

# Liste des noms des langues
$liste_name_language = array(
"Français",
"Deutch",
"English",
"Italiano",
"Spanish"
);
?>