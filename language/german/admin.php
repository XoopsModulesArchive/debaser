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
// Name für dieses Modul
define('_AM_DEBASER_MODNAME','debaser');

define('_AM_DEBASER_NEWPLAYER','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Neuer Player</div>');
define('_AM_DEBASER_ADDPLAYER','Player hinzufügen');
define('_AM_DEBASER_NAME','Name:');
define('_AM_DEBASER_CODE','Code:');
define('_AM_DEBASER_DELETED',' gelöscht');
define('_AM_DEBASER_NOTDELETED',' nicht gelöscht');
define('_AM_DEBASER_APPROVE','Dateien freigeben');
define('_AM_DEBASER_SHOWSORT','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Dateien nach Kategorien anzeigen</div>');
define('_AM_DEBASER_TOAPPROVE','Noch freizugebende Dateien:');
define('_AM_DEBASER_ID','ID:');
define('_AM_DEBASER_FILEADMIN','Datei-Administration');
define('_AM_DEBASER_SUREDELETEFILE','Sind Sie sicher Sie wollen diese Datei löschen?');
define('_AM_DEBASER_ARTIST','Künstler:');
define('_AM_DEBASER_TITLE','Titel:');
define('_AM_DEBASER_ALBUM','Album:');
define('_AM_DEBASER_TYPEOFLINK','Link:');
define('_AM_DEBASER_YEAR','Jahr:');
define('_AM_DEBASER_COMMENT','Beschreibung:');
define('_AM_DEBASER_TRACK','Track:');
define('_AM_DEBASER_GENRE','Kategorie:');
define('_AM_DEBASER_LENGTH','Länge:');
define('_AM_DEBASER_NEWPLAYADD','Neuer Player hinzugefügt');
define('_AM_DEBASER_SUREDELETEPLAYER','Sind Sie sicher, dass Sie diesen Player löschen wollen?');
define('_AM_DEBASER_PLAYERADMIN','Player');
define('_AM_DEBASER_HEIGHT','Player-Höhe:');
define('_AM_DEBASER_WIDTH','Player-Breite:');
define('_AM_DEBASER_AUTOSTART','Autostart:');
define('_AM_DEBASER_DBUPDATE','Datenbank aktualisiert');
define('_AM_DEBASER_APPROVE2','Freigabe:');
define('_AM_DEBASER_NOAPPROVE','Es liegen keine Dateien zur Freigabe vor');
define('_AM_DEBASER_ADDNEWGENRE','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Neue Kategorie hinzufügen</div>');
define('_AM_DEBASER_WEIGHT','Position');
define('_AM_DEBASER_BITRATE','Bitrate:');
define('_AM_DEBASERRAD_EDIT','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Radiosender ändern</div>');
define('_AM_DEBASERRAD_NAME','Name des Radiosenders');
define('_AM_DEBASERRAD_NEW','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Neuen Radiosender hinzufügen</div>');
define('_AM_DEBASERRAD_NOST','Es liegen noch keine Radiosender vor');
define('_AM_DEBASERTV_NOST','Es liegen noch keine TV-Sender vor');
define('_AM_DEBASERRAD_PICT','Bild des Radiosenders');
define('_AM_DEBASERTV_PICT','Bild des TV-Senders');
define('_AM_DEBASERRAD_PROG','Verfügbare Radiosender');
define('_AM_DEBASERTV_PROG','Verfügbare TV-Sender');
define('_AM_DEBASERRAD_STREAM','URL des Streams');
define('_AM_DEBASERRAD_URL','URL der Website');
define('_AM_DEBASER_EDITPLAYER','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Player bearbeiten</div>');
define('_AM_DEBASER_EDITPLAYERMEN','Player bearbeiten');
define('_AM_DEBASER_EDITMPEG','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Datei bearbeiten</div>');
define('_AM_DEBASER_NOSONGAVAIL','Es liegen für diese Kategorie keine Dateien vor!');
define('_AM_DEBASER_GENREMOVE','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Dateien verschieben</div>');
define('_AM_DEBASER_GENREFROM','Von Kategorie: ');
define('_AM_DEBASER_GENRETO',' nach Kategorie: ');
define('_AM_DEBASER_MOVED','Datei(en) wurde(n) verschoben!');
define('_AM_DEBASER_MIME_CREATEF','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Mimetyp erstellen</div>');
define('_AM_DEBASER_MIME_MODIFYF','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Mimetyp bearbeiten</div>');
define('_AM_DEBASER_MIME_EXTF','Dateierweiterung: <a href="http://www.file-extensions.org" target="_blank"><img src="'.XOOPS_URL.'/modules/debaser/images/question.png" alt="Dateierweiterung suchen" title="Dateierweiterung suchen" /></a>');
define('_AM_DEBASER_MIME_NAMEF','Anwendung Typ/Name:<br /><span style="font-weight: normal;">Geben Sie eine Anwendung an die mit der Dateierweiterung verknüpft werden soll.</span>');
define('_AM_DEBASER_MIME_TYPEF','Mimetypen:<br /><span style="font-weight: normal;">Geben Sie die Mimetypen an die mit der Dateierweiterung verknüpft werden sollen. Jeder Mimetyp muss mit einem Leerzeichen abgetrennt sein.</span>');
define('_AM_DEBASER_MIME_ADMINF','Erlaubte Admin-Mimetypen');
define('_AM_DEBASER_MIME_USERF','Erlaubte User-Mimetypen');
define('_AM_DEBASER_MIME_CREATE','Erstellen');
define('_AM_DEBASER_MIME_CLEAR','Zurücksetzen');
define('_AM_DEBASER_MIME_MODIFY','Verändern');
define('_AM_DEBASER_MIME_EXTFIND','Dateierweiterung suchen');
define('_AM_DEBASER_MIME_CREATED','Mimetyp-Information erstellt');
define('_AM_DEBASER_MIME_MODIFIED','Mimetyp-Information verändert');
define('_AM_DEBASER_MIME_MIMEDELETED','Mimetyp %s wurde gelöscht');
define('_AM_DEBASER_MIME_DELETETHIS','Ausgewählten Mimetyp löschen?');
define('_AM_DEBASER_MMIMETYPES','Dateitypen');
define('_AM_DEBASER_MIME_INFOTEXT','<ul><li>Neue Mimetypen können über dieses Formular erstellt, bearbeitet oder gelöscht werden.</li><li>Suche nach neuen Mimetypen über eine externe Website.</li><li>Anzeige der Mimetypen für Admin- oder User-Uploads.</li><li>Ändern des Mimetyp-Upload-Status.</li></ul>');
define('_AM_DEBASER_MIME_ADMINFINFO','<strong>Für Admin-Uploads verfügbare Mimetypen:</strong>');
define('_AM_DEBASER_MIME_NOMIMEINFO','Keine Mimetypen ausgewählt.');
define('_AM_DEBASER_MIME_USERFINFO','<strong>Für User-Uploads verfügbare Mimetypen:</strong>');
define('_AM_DEBASER_MIME_ID','ID');
define('_AM_DEBASER_MIME_NAME','Anwendung');
define('_AM_DEBASER_MIME_EXT','EXT');
define('_AM_DEBASER_MIME_ADMIN','Admin');
define('_AM_DEBASER_MIME_USER','User');
define('_AM_DEBASER_MINDEX_ACTION','Aktion');
define('_AM_DEBASER_MINDEX_PAGE','<strong>Seite:<strong> ');
define('_AM_DEBASER_ONLINE','Online');
define('_AM_DEBASER_OFFLINE','Offline');
define('_AM_DEBASER_FCATEGORY_CIMAGE', 'Kategoriebild auswählen:');
define('_AM_DEBASER_MCATEGORY', 'Kategorien bearbeiten');
define('_AM_DEBASER_DBERROR', 'Datenbank konnte nicht aktualisiert werden!');
define('_AM_DEBASER_MODCAT','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Kategorie bearbeiten</div>');
define('_AM_DEBASER_MODIFY','Bearbeiten');
define('_AM_DEBASER_CATDELETED','Kategorie und alle damit verbundenen Daten wurde gelöscht!');
define('_AM_DEBASER_DELCCONF','Wollen Sie die Kategorie wirklich löschen?');
define('_AM_DEBASER_DELCAT','Kategorie löschen');
define('_AM_DEBASER_CATADDED','Kategorie wurde hinzugefügt');
define('_AM_DEBASER_SUBCAT','Unterkategorie von');
define('_AM_DEBASER_PERM_CPERMISSIONS','Kategorie-Berechtigungen');
define('_AM_DEBASER_PERM_CSELECTPERMISSIONS','Auswahl der Kategorien die jede Gruppe sehen darf');
define('_AM_DEBASER_PERM_MANAGEMENT','Berechtigungs-Management');
define('_AM_DEBASER_TITLANGUAGE','Titel in Sprache: ');
define('_AM_DEBASER_DESCLANGUAGE','Beschreibung in Sprache:<br />');
define('_AM_DEBASER_CATDESCRIPTION','Beschreibung:');
define('_AM_DEBASER_XSPF','Playlist-Unterstützung');
define('_AM_DEBASER_TOFIX','Defekte Dateien/Links:');
define('_AM_DEBASER_MAINT','Wartung');
define('_AM_DEBASER_REPDEF','Als defekt gemeldete Dateien/Links');
define('_AM_DEBASER_NODEF','Es liegen keine defekten Dateien/Links vor!');
define('_AM_DEBASER_FREQUENCY','Frequenz:');
define('_AM_DEBASER_OWNER','Einsender');
define('_AM_DEBASER_FILETYPE','Dateityp:');
define('_AM_DEBASER_URLTOSCRIPT','Ordnername des Players');
define('_AM_DEBASER_PLAYERACTIVE','Player anzeigen?');
define('_AM_DEBASER_LANGSELECT','Sprachauswahl');
define('_AM_DEBASER_PUREJS','Reiner Javascript-Player?');
define('_AM_DEBASER_SELPLAYICON','Player-Icon auswählen');
define('_AM_DEBASER_MODSEC','mod_security läuft auf Ihrem Server und Sie haben Flash-Upload erlaubt. Leider ist dadurch kein Flash-Upload möglich. Mit einer htaccess-Direktive kann man dieses Problem aber beheben. Dazu muss die Datei no.htaccess in .htaccess umbenannt werden. mod_security wird nur für die Datei upload.php deaktiviert. Das Deaktivieren von mod_security und die damit entstehenden möglichen Risiken liegen in Ihrem eigenen Ermessen. Bitte informieren Sie sich über die Risiken, bevor Sie mod_security für upload.php ausschalten.<br /><br />');
define('_AM_DEBASER_PERM_CNOCATEGORY','Es liegen noch keine Kategorien vor!');
define('_AM_DEBASERRAD_NOPLAY','<span style="color:#ff0000;">Es sind den Mediaplayer noch keine Mimetypen zugeordnet worden!</span>');
define('_AM_DEBASER_PLATFORM','Online-Plattform wie z. B. YouTube');
define('_AM_DEBASER_SUBMITLINK','Links einschicken');
define('_AM_DEBASER_EQUALIZER','Equalizer im Template anzeigen?<br /><span style="color:#ff0000;">Nur für Flash-basierte Player</span>');
define('_AM_DEBASER_NOCAT2EDIT','<b>Es liegen keine Kategorien zum Bearbeiten vor!</b>');
define('_AM_DEBASER_MADMIN','Administration');
define('_AM_DEBASER_MCATS','Kategorien');
define('_AM_DEBASER_MPERM','Berechtigungen');
define('_AM_DEBASER_GO2MOD','Zum Modul');
define('_AM_DEBASER_PREFS','Voreinstellungen');
define('_AM_DEBASER_NEWCAT','Kategorie anlegen');
define('_AM_DEBASER_EDITCAT','Kategorie bearbeiten');
define('_AM_DEBASER_EDITPLAYER','Player bearbeiten');
define('_AM_DEBASER_NEWMIME','Dateityp anlegen');
define('_AM_DEBASER_EDITMIME','Dateityp bearbeiten');
define('_AM_DEBASER_NEWRADIO','Radio anlegen');
define('_AM_DEBASER_EDITRADIO','Radio bearbeiten');
define('_AM_DEBASER_NEWTV','TV-Sender anlegen');
define('_AM_DEBASER_EDITTV','TV-Sender bearbeiten');
define('_AM_DEBASER_LISTBROKEN','Defekte Dateien/Links anzeigen');
define('_AM_DEBASER_EMBEDDING','Anzeige Code zum Einbetten?');
define('_AM_DEBASER_LOFIOK','LoFi-Version bereits vorhanden');
define('_AM_DEBASER_MAKELOFI','LoFi-Version (erneut) schreiben');
define('_AM_DEBASER_JEROEN','<b><small>Der FLVPlayer im Ordner "jeroen" darf nur für nicht-kommerzielle Zwecke eingesetzt werden. Anderenfalls muss eine Lizenz unter <a href="https://www.longtailvideo.com/players/order" target="_blank">dieser URL</a> gekauft werden. Um diese Nachricht zu entfernen, einfach die Datei noncommercial.txt im Ordner "jeroen" löschen.</small></b>');
define('_AM_DEBASER_CHECKALL','Alle auswählen');
define('_AM_DEBASER_REWRITEWARN1','Es gibt folgende Warnungen beim Neuschreiben der ID3-Tags:<br />');
define('_AM_DEBASER_REWRITEWARN2','Fehler beim Neuschreiben der ID3-Tags bei der neu erzeugten Datei. Die neue Datei bleibt trotzdem erhalten.');
define('_AM_DEBASER_REWRITEWARN3','FEHLER: die temporäre Datei konnte nicht verschoben werden.');
define('_AM_DEBASERTV_NEW','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Neuen TV-Sender hinzufügen</div>');
define('_AM_DEBASERTV_NAME','Name des TV-Senders');
define('_AM_DEBASER_MIMENOUPUS','Update der Userinformation nicht möglich:');
define('_AM_DEBASER_MIMENOUPMIM','Update der Mimetypeinformation nicht möglich:');
define('_AM_DEBASER_ALLBATCHED','Batchverarbeitung beendet, fehlerhafte Dateien müssen manuell freigegeben werden. Sie können den Ordner batchload jetzt leeren.');
define('_AM_DEBASER_BATCHFORMMEN','Batchverarbeitung');
define('_AM_DEBASER_BATCHFORM','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Batchverarbeitung</div>');
define('_AM_DEBASER_USELAME','Reencoding mit Lame');
define('_AM_DEBASER_MOREINFO','Weitere Dateiinfos');
// defines for more info
define('_AM_DEBASER_BDIRSCAN','<p>Verzeichnis gescannt in %01.2f Sekunden.</p>');
define('_AM_DEBASER_BFILENAME','Dateiname');
define('_AM_DEBASER_BFILESCAN','Datei geparst in %01.2f Sekunden.<br />');
define('_AM_DEBASER_BAVERAGE','Durchschnitt:');
define('_AM_DEBASER_BIDENTFILES','Erkannte Dateien:');
define('_AM_DEBASER_BTOTAL','Insgesamt:');
define('_AM_DEBASER_BWARNING','Warnung');
define('_AM_DEBASER_BERROR','Fehler');
define('_AM_DEBASER_BSUCCDEL','%s erfolgreich gelöscht');
define('_AM_DEBASER_BFAILDEL1','Löschen von %s fehlgeschlagen - Fehler beim Löschen der Datei');
define('_AM_DEBASER_BFAILDEL2','Löschen von %s fehlgeschlagen - Datei existiert nicht');
define('_AM_DEBASER_BFILESIZE','Dateigröße');
define('_AM_DEBASER_BFORMAT','Typ');
define('_AM_DEBASER_BPLAYTIME','Laufzeit');
define('_AM_DEBASER_BBROWSE','Browse: ');
define('_AM_DEBASER_BNOTEXIST','%s existiert nicht');
define('_AM_DEBASER_BNOREMOTE','<i>Remote-Dateisystem können nicht durchsucht werden</i><br />');
define('_AM_DEBASER_BPARDIR','Übergeordnetes Verzeichnis: ');
define('_AM_DEBASER_BBITRATE','Bitrate');
define('_AM_DEBASER_BARTIST','Künstler');
define('_AM_DEBASER_BTITLE','Titel');
define('_AM_DEBASER_BFILEFILE','Datei (Datei)');
define('_AM_DEBASER_BDISABLE','deaktivieren');
define('_AM_DEBASER_BENABLE','aktivieren');
define('_AM_DEBASER_BERRWARN','Fehler/Warnungen');
define('_AM_DEBASER_BVIEWDETAIL','Detaillierte Analyse anzeigen');
define('_AM_DEBASER_WTRACKOF',' von ');
define('_AM_DEBASER_WGENRE','Genre:');
define('_AM_DEBASER_WOTHERGENRE','Anderes Genre');
define('_AM_DEBASER_WWRITETAGS','Tags schreiben:');
define('_AM_DEBASER_REMOVETAGS','Nicht ausgewählte Tag-Formate beim Schreiben der neuen Tags entfernen');
define('_AM_DEBASER_WSTARTWRITE','Tags werden geschrieben<br />');
define('_AM_DEBASER_WINVALIDIMAGE','<b>Ungültiges Bildformat (nur GIF, JPEG, PNG)</b><br />');
define('_AM_DEBASER_WNOTOPEN','<b>kann nicht geöffnet werden %s</b><br />');
define('_AM_DEBASER_WNOUPLOAD','<b>Es ist kein Upload erfolgt</b><br />');
define('_AM_DEBASER_WEMBEDIMAGE','<b>WARNUNG:</b> Es können nur Bilder für ID3v2 eingebettet werden<br />');
define('_AM_DEBASER_WSUCCWROTE','Tags wurden erfolgreich geschrieben<br />');
define('_AM_DEBASER_WBROWSECURR','Aktuelles Verzeichnis anzeigen');
define('_AM_DEBASER_WPICTURE','Bild<br />(nur ID3v2)');
define('_AM_DEBASER_WPICTURETYPE','Bildtyp');
define('_AM_DEBASER_BUNKNOWNFILES','Unbekannte Dateien:');
define('_AM_DEBASER_BERRORS','Fehler:');
define('_AM_DEBASER_BWARNINGS','Warnungen:');
define('_AM_DEBASER_WEDITWRITE','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Tag editor/writer</div>');
define('_AM_DEBASER_MIME_INLIST','Dateityp für Playlist<br /><span style="font-weight:normal">Auswählen ob dieser Dateityp einer Playlist hinzugefügt werden darf</span>');
define('_AM_DEBASER_BATCHFORIMGMEN','Batch Thumbnails');
define('_AM_DEBASER_MAKETHUMB','Thumbnails erzeugen');
define('_AM_DEBASER_IMGWRITTEN','Thumbnails wurden erzeugt!');
?>