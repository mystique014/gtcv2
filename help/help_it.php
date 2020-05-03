<?php
#########################################################################
#                            help_it.php                                #
#                                                                       #
#                     Aide en allemand sur GRR                          #
#                Dernière modification : 27/07/2005                     #
#                                                                       #
#########################################################################
/*
 * Copyright 2003-2005 Laurent Delineau - Antony AUDOUARD
 * D'après http://mrbs.sourceforge.net/
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


//include "config.inc.php";
//include "misc.inc.php";
echo encode_message_utf8("</menu>
<p><B><A name=top>Autenticazione</A></B> </p>
<MENU><a href=\"#authenticate\"><U>Come accedo?</U></A><BR>
<a href=\"#meeting_delete\"><U>Non perché io il cancella/altera una riunione?</U></A><BR>
</MENU><B>Riunioni di</B> fabbricazione alterare <MENU><A
  href=\"#repeating\"><U>Come faccio un ricorrere incontrando?</U></A><BR>
<A
  href=\"#repeating_delete\"><U>Come cancello un caso di un ricorrere incontrando?</U></A><BR>
<A
  href=\"#multiple_sites\"><U>Come fa pianifico delle stanze ai luoghi</U> diversi</A><BR>
<A
  href=\"#too_many\"><U>La mia riunione ha fallito essere creato a causa di \"<I>troppe
entrate \"!</I></U></A><BR>
<A
  href=\"#multiple_users\"><U>Che succede del se le persone multiple pianificano
la stessa riunione?</U></A><BR>
</MENU><B>Miscellaneo</B> <MENU><A
  href=\"#internal_external\"><U>Che è la differenza tra \"Interno\" ed \"Esterno\"</U></A><BR>
<HR>
<P><A name=authenticate><B>Come accedo?</B></A> <MENU>Il sistema può essere configurato
per usare uno di parecchi metodi di autenticazione, includendo LDAP, il Netware,
e SMB. Vedere il suo amministratore di sistema se lei ha abbattere di difficoltà
in. Alcuni funziona sono limitato ai certi operatori, e gli altri operatori prenderanno
il messaggio <I>Lei non ha accesso che i diritti a modificare quest'articolo.</I>
Vedere il suo amministratore di sistema se questo non lavora correttamente per
lei. Se il sistema è configurato per usare l'autenticazione di LDAP, questo significa
che lei accede con lo stessi nome utente e con la parola d'ordine come lei usa
per l'email di prendere cioè il Marchio\" \"Belanger \"MyPassword\". </MENU><A
href=\"#top\">Cima</A>
<HR>
<P></P>
<P><A name=meeting_delete><B>Non perché io il cancella/altera una riunione?</B></A>
<MENU>Per cancellare o alterare una riunione, lei deve essere abbattuto in come
la stessa persona che ha fatto la riunione. Contattare uno degli amministratori
di stanza di riunione o la persona che hanno fatto inizialmente la riunione averlo
ha cancellato o cambiato. </MENU><A
href=\"#top\">Cima</A>
<HR>
<P></P>
<P><A name=repeating><B>Come faccio un ricorrere incontrando?</B></A> <MENU>Scattare
sul tempo desiderato la porta nello schermo di prenotazione. Scegliere l'appropriato
<B>per Ripetere il Tipo.</B> La stanza sarà pianificata allo stesso tempo, finché
il <B>Ripete la Data</B> di Fine, sui giorni determinati dal <B>Ripete il Tipo.</B>
<P>Un ripete <I>Quotidianamente</I> i programme ogni giorno. Un ripete <I>Settimanalmente</I>
  i programme quei giorni della settimana che lei controlla sotto <B>Ripete il
  Giorno.</B> Per esempio, l'uso ripete Settimanalmente per pianificare la stanza
  ogni lunedì, martedì, e giovedì; controlla quei giorni sotto Ripete il Giorno.
  Se lei controlla nessuni giorni sotto Ripete il Giorno, il programma ripeterà
  sullo stesso giorno di ogni settimana come il giorno dapprima pianificato. Un
  ripete <I>Mensilmente</I> i programme lo stesso giorno di ogni mese, per esempio
  il 15 del mese. Un <I>Annuale</I> ripete dei programme lo stesso mese ed il
  giorno del mese, per esempio ogni marzo 15. Finalmente, un <I>Mensile, corrispondendo
  il giorno</I> ripete i programme un giorno ogni mese, lo stesso giorno feriale
  e la posizione ordinale entro il mese. Usare questo ripete il tipo per pianificare
  il primo lunedì, secondo martedì, o quarto venerdì di ogni mese, per esempio.
  Non usare questo ripete il tipo dopo il giorno 28 del mese. </P>
</MENU><A
href=\"#top\">Cima</A>
<HR>
<P></P>
<P><A name=repeating_delete><B>Come cancello un caso ricorrendo la riunione?</B></A>
<MENU>Scegliere il giorno/la stanza/il tempo che lei vuole cancellare e scegliere
<B>Cancellare l'Entrata.</B> </MENU><A
href=\"#top\">Cima</A>
<HR>
<P></P>
<P><A name=multiple_sites><B>Di come pianifico le stanze ai luoghi</B></A> diversi?
<MENU>Lei fa non. Attualmente il sistema non può riservare 2 stanze diverse simultaneamente.
Lei deve pianificare ogni un separatamente. Assicurarsi che il tempo che lei vuole
è disponibile a entrambi i luoghi prima di fare una prenotazione. </MENU><A
href=\"#top\">Cima</A>
<HR>
<P></P>
<P><A name=too_many><B>La mia riunione ha fallito essere creato a causa di \"<I>troppe
  entrate \"!</I></B></A> <MENU>Qualunque riunione non può creare più di 365 entrate.
Lí i bisogni di essere del limite sul numero di riunioni create. Questo numero
può essere aumentato se necessario. </MENU><A
href=\"#top\">Cima</A>
<HR>
<P></P>
<P><A name=multiple_users><B>Che succede del se le persone multiple pianificano
  la stessa riunione?</B></A> <MENU>La risposta breve è: La prima persona scattare
sul <B>Presenta</B> il bottone vince.<BR>
Dietro le quinte, il sistema usa un multi-operatore proprio, un multi-infilare
una base di dati relazionale di può maneggiare molte migliaia di operatori simultanei.
</MENU><A
href=\"#top\">Cima</A>
<HR>
<P></P>
<P><A name=internal_external><B>Che è la differenza tra \"Interno\" ed \"Esterno\"</B></A>
<MENU>Da predefinito, MRBS definisce due tipi di riunione. I mezzi \"<B>interni</B>
\" che la riunione soltanto sarà assistita a dagli impiegati. Una riunione \" <B>Esterna</B>
\" anche potrebbe essere assistita a dai clienti, i venditori, gli investitori,
ecc. Il suo luogo può definire fino a un totale di 10 tipi di riunione, secondo
i suoi bisogni. Le riunioni sono evidenziate nella veduta di calendario principale
con un colore corrispondente al loro tipo, ed una chiave di colore di tutti i
tipi definiti è mostrata al fondo della veduta di calendario principale. </MENU><A
href=\"#top\">Cima</A>
<HR>
<br>
<p><br>
</p>");

