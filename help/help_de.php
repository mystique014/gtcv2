<?php
#########################################################################
#                            help_de.php                                #
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

?> 
<p><B><A name=top>De echtverklaring</A></B> </p>
<MENU><A 
  href="#authenticate"><U>Hoe doe ik login?</U></A><BR>
<A 
  href="#meeting_delete"><U>Waarom ik niet schrapt/verandert een meeting?</U></A><BR>
</MENU><B>Maken/veranderen Meetings</B> <MENU><A 
  href="#repeating"><U>Hoe maak ik een terugkerende meeting?</U></A><BR>
<A 
  href="#repeating_delete"><U>Hoe schrap ik een geval van een terugkerende meeting?</U></A><BR>
<A 
  href="#multiple_sites"><U>Hoe doe, plan ik kamers aan verschillende plaatsen</U></A><BR>
<A 
  href="#too_many"><U>Mijn meeting verzuimde gecreëerd te worden wegens "<I>te 
vele ingangen "!</I></U></A><BR>
<A 
  href="#multiple_users"><U>Wat gebeurt of veelvoudige mensen de zelfde meeting</U> 
plannen?</A><BR>
</MENU><B>Verscheiden</B> <MENU><A 
  href="#internal_external"><U>Wat het verschil tussen Interne en "Uiterlijke</U></A> 
" is</MENU>
<HR>
<P><A name=authenticate><B>Hoe doe ik login?</B></A> <MENU>Het systeem kan gevormd 
worden een van enkele methoden van echtverklaring te gebruiken, inclusief LDAP, 
Netwerksoftware en SMB. Zie uw systeemadministrateur indien u moeilijkheid laat 
hakken hout in. Sommige functie zijn naar zekere gebruikers beperkt en andere 
gebruikers zullen het bericht krijgen <I>dat U toegangsrechten niet hebt om dit 
onderdeel</I> te wijzigen. Zie uw systeemadministrateur indien dit correct voor 
u werkt niet. Indien het systeem gevormd wordt de LDAP echtverklaring, dit te 
gebruiken betekent dat u login met de zelfde username en wachtwoord als u voor 
het krijgen van e-mail d.w.z. "Teken Belanger" "Mypassword" gebruik. </MENU><A 
href="#top">De top</A> 
<HR>
<P></P>
<P><A name=meeting_delete><B>Waarom ik niet schrapt/verandert een meeting?</B></A> 
<MENU>Om te een meeting, u moet gehakt worden hout in als de zelfde persoon schrappen 
of te veranderen die de meeting maakte. Contacteer een van de meeting kamer administrateurs 
of de persoon die eerst de meeting hem maakte schrappen te laten of veranderd. 
</MENU><A 
href="#top">De top</A> 
<HR>
<P></P>
<P><A name=repeating><B>Hoe maak ik een terugkerende meeting?</B></A> <MENU>Klikkend 
op de gewenste keer brengt u in het boeking scherm. Selecteer het geschikte <B>Herhaling 
Type.</B> De kamer zal door het <B>Herhaling Type</B> tegelijkertijd, tot de <B>Herhaling 
Einde Datum</B>, op de dagen vastberaden gepland worden. 
<P>Een <I>Dagelijkse</I> herhaling plant iedere dag. Een <I>Wekelijkse</I> herhaling 
  plant die dagen van de week dat u onder <B>Herhaling Dag</B> controleer. Bijvoorbeeld 
  zal gebruik Wekelijkse herhaling de kamer ieder maandag, dinsdag plannen en 
  donderdag; controleer die dagen onder Herhaling Dag. Indien u geen dagen onder 
  Herhaling Dag controleer, zal het schema op de zelfde dag van elk week als de 
  eerste geplande dag herhalen. Een <I>Maandelijkse</I> herhaling plant de zelfde 
  dag van elk maand, bijvoorbeeld de 15de van de maand. Een <I>Jaarlijkse</I> 
  herhaling plant de zelfde maand en dag van de maand, bijvoorbeeld ieder 15 maart. 
  Ten slotte een <I>Maandelijks, dag</I> herhaling overeenkomend plant een dag 
  elk maand, de zelfde weekdag en rangpositie binnen de maand. Gebruik dit herhaling 
  type om de eerste maandag te plannen, tweede dinsdag of vierde vrijdag van elk 
  maand, bijvoorbeeld. Gebruik niet dit herhaling type na de 28ste dag van de 
  maand. </P>
</MENU><A 
href="#top">De top</A> 
<HR>
<P></P>
<P><A name=repeating_delete><B>Hoe schrap ik een geval dat meeting</B></A> terugkeert? 
<MENU>Selecteer de dag/kamer/tijd dat u wil schrappen en selecteren <B>Schrap 
Ingang.</B> </MENU><A 
href="#top">De top</A> 
<HR>
<P></P>
<P><A name=multiple_sites><B>Hoe plan ik kamers aan verschillende plaatsen?</B></A> 
<MENU>U doe niet. Thans kan het systeem niet boek 2 verschillende kamers gelijktijdig. 
U moet elk een afzonderlijk plannen. Vergewis u ervan dat de tijd u nood verkrijgbaar 
aan beide plaatsen voor het maken van een boeking is. </MENU><A 
href="#top">De top</A> 
<HR>
<P></P>
<P><A name=too_many><B>Mijn meeting verzuimde gecreëerd te worden wegens "<I>te 
  vele ingangen "!</I></B></A> <MENU>Enig meeting kan creëert niet meer dan 365 
ingangen. Er moet sommige limieten op het nummer van meetings gecreëerd zijn. 
Dit nummer kan zo nodig toegenomen worden. </MENU><A 
href="#top">De top</A> 
<HR>
<P></P>
<P><A name=multiple_users><B>Wat gebeurt of veelvoudige mensen de zelfde meeting</B></A> 
  plannen? <MENU>Het korte antwoord is: De eerste persoon om op het te klikken 
<B>Legt</B> aan knoop overwinningen Voor.<BR>
Achter de scènes gebruikt het systeem een gepast multi user, multi-geregen relationele 
databank dan vele duizenden gelijktijdige gebruikers kan behandelen. </MENU><A 
href="#top">De top</A> 
<HR>
<P></P>
<P><A name=internal_external><B>Wat het verschil tussen Interne en "Uiterlijke</B></A> 
  " is <MENU>Door standaardwaarde definieert MRBS twee meeting typen. "<B>Interne</B> 
" middelen dat de meeting enkel door werknemers zal bijgewoond worden. Een " <B>Uiterlijke</B> 
" meeting zou ook door klanten kunnen bijgewoond worden, leveranciers, investeerders, 
enz. Uw plaats kan op naar een totaal van 10 meeting typen definiëren, volgens 
uw noden. Meetings zijn naar voren in het hoofdkalender overzicht met een kleur 
gehaald die aan hun type en een kleur sleutel van alle gedefinieerde typen beantwoordt, 
is geschrokken aan de bodem van het hoofdkalender overzicht. </MENU><A 
href="#top">De top</A> 
<HR>
<P></P>
<p> </p>
