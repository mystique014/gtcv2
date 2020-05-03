function clicMenu(num)
{
  var fermer; var ouvrir; var menu;
  //Booléen reconnaissant le navigateur
  isIE = (document.all)
  isNN6 = (!isIE) && (document.getElementById)
  //Compatibilité : l'objet menu est détecté selon le navigateur

  if (isIE) {
        menu = document.all['menu' + num];
        if (document.all['fermer']) fermer = document.all['fermer'];
        if (document.all['ouvrir']) ouvrir = document.all['ouvrir'];
  }else if (isNN6) {
         menu = document.getElementById('menu' + num);
        if (document.getElementById('fermer')) fermer = document.getElementById('fermer');
        if (document.getElementById('ouvrir')) ouvrir = document.getElementById('ouvrir');
  }

  // On ouvre ou ferme
  if (menu.style.display == "none") {
    // Cas ou le tableau est caché
        //menu.style.display = "";
        if (fermer) fermer.style.display = "";
        if (ouvrir) ouvrir.style.display = "none";
        menu.style.display = "";
  } else {
    // On le cache
        menu.style.display = "none";
        if (fermer) fermer.style.display = "none";
        if (ouvrir) ouvrir.style.display = "";
  }

}


function centrerpopup(page,largeur,hauteur,options)
{
// les options :
//    * left=100 : Position de la fenêtre par rapport au bord gauche de l'écran.
//    * top=50 : Position de la fenêtre par rapport au haut de l'écran.
//    * resizable=x : Indique si la fenêtre est redimensionnable.
//    * scrollbars=x : Indique si les barres de navigations sont visibles.
//    * menubar=x : Indique si la barre des menus est visible.
//    * toolbar=x : Indique si la barre d'outils est visible.
//    * directories=x : Indique si la barre d'outils personnelle est visible.
//    * location=x : Indique si la barre d'adresse est visible.
//    * status=x : Indique si la barre des status est visible.
//
// x = yes ou 1 si l'affirmation est vrai ; no ou 0 si elle est fausse.

var top=(screen.height-hauteur)/2;
var left=(screen.width-largeur)/2;
window.open(page,"","top="+top+",left="+left+",width="+largeur+",height="+hauteur+",directories=no,toolbar=no,menubar=no,location=no,"+options);
}
/**
 * Displays an confirmation box beforme to submit a query
 * This function is called while clicking links
 *
 * @param   object   the link
 * @param   object   the sql query to submit
 * @param   object   the message to display
 *
 * @return  boolean  whether to run the query or not
 */
function confirmlink(theLink, theSqlQuery, themessage)
{

    var is_confirmed = confirm(themessage + ' :\n' + theSqlQuery);
    if (is_confirmed) {
        theLink.href += '&js_confirmed=1';
    }
    return is_confirmed;
} // end of the 'confirmLink()' function

/**
 * Checks/unchecks les boites à cocher
 *
 * the_form   string   the form name
 * do_check   boolean  whether to check or to uncheck the element
 * day la valaur de de la boite à cocher ou à décocher
 * return  boolean  always true
 */
function setCheckboxesGrr(the_form, do_check, day)
{
    var elts = document.forms[the_form];
    for (i=0;i<elts.elements.length;i++)
    {
        type = elts.elements[i].type;
        if (type="checkbox")
        {
            if ((elts.elements[i].value== day) || (day=='all'))
            {
                elts.elements[i].checked = do_check;
            }
        }
    }

    return true;
} // end of the 'setCheckboxes()' function