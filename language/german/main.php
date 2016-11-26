<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

define('_MD_DEBASER_ALBUM','Album:');
define('_MD_DEBASER_YEAR','Jahr:');
define('_MD_DEBASER_COMMENT','Beschreibung:');
define('_MD_DEBASER_TRACK','Track:');
define('_MD_DEBASER_GENRE','Kategorie:');
define('_MD_DEBASER_LENGTH','Länge:');
define('_MD_DEBASER_BITRATE','Bitrate:');
define('_MD_DEBASER_FREQUENCY','Frequenz:');
define('_MD_DEBASER_DOWNLOAD','Download');
define('_MD_DEBASER_TITLE','Titel:');
define('_MD_DEBASER_ARTIST','Künstler:');
define('_MD_DEBASER_SECONDS','Minuten');
define('_MD_DEBASER_KBITS','kBit/s');
define('_MD_DEBASER_HERTZ','Hz');
define('_MD_DEBASER_INDEX','Index');
define('_MD_DEBASER_NOFILES','Für diese Kategorie liegen keine Dateien vor');
define('_MD_DEBASER_MAXUPSIZE','Max. Uploadgröße = ');
define('_MD_DEBASER_MAXBYTES','Mb');
define('_MD_DEBASER_FILENOTFOUND','Die Datei konnte nicht gefunden werden!');
define('_MD_DEBASER_READMORE','Weitere Informationen...');
define('_MD_DEBASER_RATETHIS', '< Bewerten Sie diese Datei!');
define('_MD_DEBASER_VOTES', 'Stimmen');
define('_MD_DEBASER_NOTRATED', 'Diese Datei wurde noch nicht bewertet');
define('_MD_DEBASER_VIEWS','Aufrufe');
define('_MD_DEBASER_HITS','Downloads');
define('_MD_DEBASER_EDIT','Bearbeiten');
define('_MD_DEBASER_NORATING', 'Sie haben keine Bewertung für diese Datei gewählt!');
define('_MD_DEBASER_VOTEONCE', 'Bitte stimmen Sie für eine Datei nur einmal ab.');
define('_MD_DEBASER_VOTEAPPRE', 'Ihre Stimme wissen wir zu schätzen.');
define('_MD_DEBASER_THANKYOU', 'Danke, dass Sie sich Zeit genommen haben hier auf %s abzustimmen');
define('_MD_DEBASER_UNKNOWNERROR', 'FEHLER. Sie werden zurück gebracht!');
define('_MD_DEBASER_DBUPDATED','Datenbank wurde aktualisiert!');
define('_MD_DEBASER_DBNOTUPDATED','Datenbank wurde nicht aktualisiert!');
define('_MD_DEBASER_NOFLASHUPLOAD','Flashupload');
define('_MD_DEBASER_PUBLICPLAYLIST','Öffentliche Playlist');
define('_MD_DEBASER_ADDMPEG','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Datei hinzufügen</div>');
define('_MD_DEBASER_ADDLINK','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Link einschicken</div>');
define('_MD_DEBASER_EDITMPEG','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Datei bearbeiten</div>');
define('_MD_DEBASER_TOOBIG','Es steht kein Speicherplatz mehr zur Verfügung. Sie müssen einige Dateien löschen um etwas hochladen zu können.');
define('_MD_DEBASER_REMAIN','Zur Verfügung stehender Speicherplatz (in Mb): ');
define('_MD_DEBASER_FILEADMIN','Datei-Administration');
define('_MD_DEBASER_SUREDELETEFILE','Sind Sie sicher Sie wollen diese Datei löschen?');
define('_MD_DEBASER_NOTDELETED',' nicht gelöscht');
define('_MD_DEBASER_DELETED',' gelöscht');
define('_MD_DEBASER_FILEUPLOAD','Dateiupload');
define('_MD_DEBASER_OTHERSONGS','Andere Dateien des Users');
define('_MD_DEBASER_MYPLAYLIST','Meine Playlist');
define('_MD_DEBASER_NOCATEGORIES','Es liegen noch keine Kategorien vor!');
define('_MD_DEBASER_TOPLAYLIST','Zur Playlist');
define('_MD_DEBASER_WASADDED','<span style="font-weight:bold; color:#ff0000;">Datei wurde zur Playlist hinzugefügt!</span>');
define('_MD_DEBASER_MYSETTINGS','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Meine Einstellungen</div>');
define('_MD_DEBASER_OWNER','Eigentümer');
define('_MD_DEBASER_NORADIOPARAM','Es liegen keine Parameter für Radiosender oder Player vor!');
// defines for flash uploader
define('_MD_DEBASER_ALRUPLOADED','Datei(en) hochgeladen');
define('_MD_DEBASER_FLASHWARN','<noscript style="color: #000; background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px;">We\'re sorry.  SWFUpload could not load.  You must have JavaScript enabled to enjoy SWFUpload.</noscript><div id="divLoadingContent" style="color: #000; background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">SWFUpload is loading. Please wait a moment...</div><div id="divLongLoading" style="color: #000; background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">SWFUpload is taking a long time to load or the load has failed.  Please make sure that the Flash Plugin is enabled and that a working version of the Adobe Flash Player is installed.</div><div id="divAlternateContent" style="color: #000;background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">We\'re sorry.  SWFUpload could not load.  You may need to install or upgrade Flash Player. Visit the <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a> to get the Flash Player.</div>');
define('_MD_DEBASER_APPROVE','Freigeben');
define('_MD_DEBASER_LANGSELECT','Sprachauswahl');
define('_MD_DEBASER_NOSETTINGAVAIL','<span style="color:#ff0000; font-weight:bold;">Es stehen momentan keine Usersettings zur Verfügung!</span>');
define('_MD_DEBASER_PLATFORM','Online-Plattform wie z. B. YouTube');
define('_MD_DEBASER_TYPEOFLINK','Link');
define('_MD_DEBASER_LINKADD','Link wurde in die Datenbank eingefügt');
define('_MD_DEBASER_PLUGINS','Installierte Plugins');
define('_MD_DEBASER_DESCLANGUAGE','Beschreibung in Sprache:<br />');
define('_MD_DEBASER_CLOSEWIN','Fenster schließen');
define('_MD_DEBASER_REPORTBROKEN','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Defekte Datei melden</div>');
define('_MD_DEBASER_BROKENREASON','Grund des Defekts');
define('_MD_DEBASER_BROKCOMMENT','Weitere Infos');
define('_MD_DEBASER_TELLAFRIEND','Einen Freund informieren');
define('_MD_DEBASER_GETCODE','Player-Code auswählen, ins Textfeld klicken, Code kopieren und auf Ihrer Website einfügen.');
define('_MD_DEBASER_SELECTCODE','Player-Code auswählen');
define('_MD_DEBASER_EMBED','Datei einbetten');
define('_MD_FAILUPLOADING','Upload fehlgeschlagen!');
define('_MD_DEBASER_NOOPENHT', '.htaccess-Datei konnte nicht zum Schreiben geöffnet werden!');
define('_MD_DEBASER_NOWRITEHT', 'Schreiben der .htaccess-Datei fehlgeschlagen!');
define('_MD_DEBASER_NOVALDATA','Es wurden leider keine gültigen Daten eingeschickt oder die Datei war zu groß!');
define('_MD_DEBASER_UPSUCCESS','Upload erfolgt!');
define('_MD_DEBASER_UPFAIL','Upload fehlgeschlagen!');
define('_MD_DEBASER_NOSCRIPT','<span style="font-weight:bold; color:#ff0000">Javascript muss eingeschaltet sein, um dieses Modul nutzen zu können!</span>');
define('_MD_DEBASER_SEARCH','Suche: ');
define('_MD_DEBASER_SEARCHRES','Suchergebnisse:');
define('_MD_DEBASER_MAIN','Index');
define('_MD_DEBASER_YOUAREHERE','Sie befinden sich hier');
define('_MD_DEBASER_TAFSENT','Empfehlung wurde verschickt!');
define('_MD_DEBASER_TAFNOTSENT','E-Mail-Versand fehlgeschlagen!');
define('_MD_DEBASER_RECTHIS','<b>Weiterempfehlen an:</b>');
define('_MD_DEBASER_HUMAN','<small>Sie sind menschlich? Dann klicken Sie auf<\/small>');
define('_MD_DEBASER_INTEREST','Dieser Link könnte Sie interessieren');
define('_MD_DEBASER_WRONGCAPTCHA','Sie haben auf das falsche Bild geklickt!');
define('_MD_DEBASER_SUSP','Formularverarbeitung angehalten aufgrund einer verdächtigen Aktion');
define('_MD_DEBASER_ELAPSE','Zeitüberschreitung');
define('_MD_DEBASER_GETNOMIME','Der Mimetyp der eingesandten Datei konnte nicht ermittelt werden. Treten Sie mit dem Webmaster dieser Website in Verbindung.');
define('_MD_DEBASER_CAPT_HOUSE','haus');
define('_MD_DEBASER_CAPT_KEY','schlüssel');
define('_MD_DEBASER_CAPT_FLAG','flagge');
define('_MD_DEBASER_CAPT_CLOCK','uhr');
define('_MD_DEBASER_CAPT_BUG','käfer');
define('_MD_DEBASER_CAPT_PEN','stift');
define('_MD_DEBASER_CAPT_LIGHTBULB','glühbirne');
define('_MD_DEBASER_CAPT_NOTE','note');
define('_MD_DEBASER_CAPT_HEART','herz');
define('_MD_DEBASER_CAPT_WORLD','globus');
define('_MD_DEBASER_SIMILAR','Ähnliche Dateien');
define('_MD_DEBASER_NOSIMATM','<b>Es gibt momentan keine ähnlichen Dateien!</b>');
?>