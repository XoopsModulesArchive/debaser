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

define('_MI_DEBASER_DESC','Multimedia-Player für Xoops 2.4x');
define('_MI_DEBASER_SUBMITFILE','Datei einschicken');
define('_MI_DEBASER_SUBMITLINK','Link einschicken');
define('_MI_DEBASER_SUBMITLINKDSC','Folgenden Gruppen die Einsendung von Links erlauben');
define('_MI_DEBASER_LATEST','Aktuelle Dateien');
define('_MI_DEBASER_LATEST_DESC','Zeigt die neuesten Dateien an');
define('_MI_DEBASER_RATED','Top bewertete Dateien');
define('_MI_DEBASER_RATED_DESC','Zeigt die am höchsten bewerteten Dateien an');
define('_MI_DEBASER_HITS','Top Downloads');
define('_MI_DEBASER_HITS_DESC','Zeigt die am am meisten heruntergeladenen Dateien an');
define('_MI_DEBASER_VIEWS','Top Besuche');
define('_MI_DEBASER_VIEWS_DESC','Zeigt die am am meisten aufgerufenen Dateien an');
define('_MI_DEBASER_DISPLATEST','Neueste Datei abspielen');
define('_MI_DEBASER_DISPRATED','Best bewertete Datei abspielen');
define('_MI_DEBASER_DISPFEATURED','Feature-Datei abspielen');
define('_MI_DEBASER_DISPDOWN','Top-Download-Datei abspielen');
define('_MI_DEBASER_DISPVIEWED','Meist aufgerufene Datei abspielen');

// preferences constants
define('_MI_DEBASER_VIEWLIMIT','Dateien pro Seite im Backend');
define('_MI_DEBASER_VIEWLIMITDESC','Dateien die pro Seite im Backend angezeigt werden sollen');
define('_MI_DEBASER_VIEWLIMITFRONT','Dateien pro Seite im Frontend');
define('_MI_DEBASER_VIEWLIMITFRONTDESC','Dateien die pro Seite im Frontend angezeigt werden sollen');
define('_MI_DEBASER_MAXSIZE','Maximale Einzeluploadgröße in Bytes');
define('_MI_DEBASER_MAXSIZEDSC','Der voreingestellte Wert wird aus der php.ini ausgelesen');
define('_MI_DEBASER_GUESTVOTE','Gast-Bewertung');
define('_MI_DEBASER_GUESTVOTEDSC','Hier wird festgelegt ob Gäste Dateien bewerten dürfen');
define('_MI_DEBASER_GUESTDOWNLOAD','Gast-Download');
define('_MI_DEBASER_GUESTDOWNLOADDSC','Hier wird festgelegt, ob Gäste Dateien herunterladen dürfen');
define('_MI_DEBASER_SHOTWIDTH', 'Maximale Breite der Kategoriebilder');
define('_MI_DEBASER_SHOTHEIGHT', 'Maximale Höhe der Kategoriebilder');
define('_MI_DEBASER_CATIMAGEFSIZE','Maximale Dateigröße der Kategoriebilder');
define('_MI_DEBASER_SORTBY', 'Dateien sortieren nach:');
define('_MI_DEBASER_SORTBY_DSC', 'Hier legen Sie fest, nach welchen Kriterien die Dateien auf der Userseite sortiert sein sollen');
define('_MI_DEBASER_ORDERBY','Reihenfolge der Dateien:');
define('_MI_DEBASER_ORDERBY_DSC','Hier legen Sie fest, in welcher Reihenfolge die Dateien angezeigt werden sollen');
define('_MI_DEBASER_CATSORTBY', 'Kategorien sortieren nach:');
define('_MI_DEBASER_CATSORTBY_DSC','Hier legen Sie fest, nach welchen Kriterien die Kategorien auf der Userseite sortiert sein sollen');
define('_MI_DEBASER_CATORDERBY','Reihenfolge der Kategorien:');
define('_MI_DEBASER_CATORDERBY_DSC','Hier legen Sie fest, in welcher Reihenfolge die Kategorien angezeigt werden sollen');
define('_MI_DEBASER_ID','ID');
define('_MI_DEBASER_ARTIST','Künstler');
define('_MI_DEBASER_TITLE','Titel');
define('_MI_DEBASER_WEIGHT','Gewichtung');
define('_MI_DEBASER_CATEGORY','Kategoriename');

//defines for flyout menu
define('_MI_DEBASER_ADMIN','Administration');
define('_MI_DEBASER_EDITGENRES','Kategorien bearbeiten');
define('_MI_DEBASER_EDITPLAYERS','Player bearbeiten');
define('_MI_DEBASER_MAPPROVE','Dateien freigeben');

//defines for notifications
define('_MI_DEBASER_GLOBAL_NOTIFY', 'Allgemein');
define('_MI_DEBASER_GLOBAL_NOTIFYDSC', 'Allgemeine Benachrichtigungsoptionen.');

define('_MI_DEBASER_GENRE_NOTIFY', 'Genre');
define('_MI_DEBASER_GENRE_NOTIFYDSC', 'Benachrichtigungsoptionen die Kategorien betreffen.');

define ('_MI_DEBASER_GENRE_NEWGENRE_NOTIFY', 'Neue Kategorie');
define ('_MI_DEBASER_GENRE_NEWGENRE_NOTIFYCAP', 'Benachrichtigen bei neuen Kategorien.');
define ('_MI_DEBASER_GENRE_NEWGENRE_NOTIFYDSC', 'Benachrichtigung bei neuen Kategorien.');
define ('_MI_DEBASER_GENRE_NEWGENRE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neue Kategorie');

define ('_MI_DEBASER_SONG_NOTIFY', 'Dateien');
define ('_MI_DEBASER_SONG_NOTIFYDSC', 'Benachrichtigungsoptionen die Dateien betreffen.');

define ('_MI_DEBASER_SONG_NEWSONG_NOTIFY', 'Neue Datei');
define ('_MI_DEBASER_SONG_NEWSONG_NOTIFYCAP', 'Benachrichtigen bei neuen Dateien.');
define ('_MI_DEBASER_SONG_NEWSONG_NOTIFYDSC', 'Benachrichtigung bei neuen Dateien.');
define ('_MI_DEBASER_SONG_NEWSONG_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neue Datei');

define ('_MI_DEBASER_SUBMIT_NOTIFY', 'Dateien');
define ('_MI_DEBASER_SUBMIT_NOTIFYDSC', 'Benachrichtigungsoptionen die Uploads betreffen.');

define ('_MI_DEBASER_NEWSUBMIT_NOTIFY', 'Neuer Upload');
define ('_MI_DEBASER_NEWSUBMIT_NOTIFYCAP', 'Benachrichtigen bei neuen Uploads.');
define ('_MI_DEBASER_NEWSUBMIT_NOTIFYDSC', 'Benachrichtigung bei neuen Uploads.');
define ('_MI_DEBASER_NEWSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neuer Upload');

define ('_MI_DEBASER_MIMETYPESUBMIT_NOTIFY', 'Unbekannter Mimetyp');
define ('_MI_DEBASER_MIMETYPESUBMIT_NOTIFYCAP', 'Benachrichtigen bei unbekannten Mimetypen.');
define ('_MI_DEBASER_MIMETYPESUBMIT_NOTIFYDSC', 'Benachrichtigung bei unbekannten Mimetypen.');
define ('_MI_DEBASER_MIMETYPESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Unbekannter Mimetyp');

define ('_MI_DEBASER_EMPTYMIMETYPE_NOTIFY', 'Leerer Mimetyp');
define ('_MI_DEBASER_EMPTYMIMETYPE_NOTIFYCAP', 'Benachrichtigen bei leeren Mimetypen.');
define ('_MI_DEBASER_EMPTYMIMETYPE_NOTIFYDSC', 'Benachrichtigung bei leeren Mimetypen.');
define ('_MI_DEBASER_EMPTYMIMETYPE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Leerer Mimetyp');

define ('_MI_DEBASER_REPORTBROKEN_NOTIFY', 'Defekte Datei/Link');
define ('_MI_DEBASER_REPORTBROKEN_NOTIFYCAP', 'Benachrichtigen bei defekten Dateien/Links.');
define ('_MI_DEBASER_REPORTBROKEN_NOTIFYDSC', 'Benachrichtigung bei defekten Dateien/Links.');
define ('_MI_DEBASER_REPORTBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Defekte Datei Link');

define('_MI_DEBASERRAD_ADMIN','Radiosender bearbeiten');
define('_MI_DEBASERTV_ADMIN','TV-Sender bearbeiten');
define('_MI_DEBASERRAD_TITLE','Internetradio');
define('_MI_DEBASERTV_TITLE','Internet-TV');

// admin/menu.php
define('_MI_DEBASER_FILETYPES', 'Dateityp bearbeiten');

define('_MI_DEBASER_DISKQUOTA','Maximale Größe des Userverzeichnisses in Bytes');
define('_MI_DEBASER_FORM_OPTIONS','Editor-Auswahl');
define('_MI_DEBASER_FORM_OPTIONS_DESC','Wählen Sie den zu benutzenden Editor.');
define('_MI_DEBASER_FORM_COMPACT','Compact');
define('_MI_DEBASER_FORM_DHTML','DHTML');
define('_MI_DEBASER_FORM_SPAW','Spaw-Editor');
define('_MI_DEBASER_FORM_HTMLAREA','HtmlArea-Editor');
define('_MI_DEBASER_FORM_FCK','FCK-Editor');
define('_MI_DEBASER_FORM_KOIVI','Koivi');
define('_MI_DEBASER_FORM_TINYEDITOR','TinyEditor');
define('_MI_DEBASER_FORM_TINYMCE','TinyMCE');

define('_MI_DEBASER_MULTILANG','Mehrsprachlichkeit benutzen');
define('_MI_DEBASER_MULTILANGDESC','Sollte Ihr XOOPS für Mehrsprachlichkeit vorbereitet sein, kann dieses Feature genutzt werden, um für jede Sprache Texte in die Datenbank zu setzen ohne den Text mit Markup zu versehen.');
define('_MI_DEBASER_MYSETTINGS','User-Einstellungen');
define('_MI_DEBASER_MYPLAYLIST','Playlist');
define('_MI_DEBASER_AUTOAPPROVE','Dateien automatisch freigeben?');
define('_MI_DEBASER_BATCHAPPROVE','Batchdateien automatisch freigeben?');
define('_MI_DEBASER_FLASHBATCH','Mehrfach-Upload mit Flash');
define('_MI_DEBASER_FLASHBATCHDSC','Hier die Anzahl der gleichzeitigen Uploads eintragen, falls in den Berechtigungen der Flash-Upload erlaubt worden ist.');
define('_MI_DEBASER_PERMISSIONS','Berechtigungen');
define('_MI_DEBASER_MAINTENANCE','Wartung');
define('_MI_DEBASER_GOTOMOD','Zum Modul');
define('_MI_DEBASER_PLAYLISTBLOCK','Public Playlist');
define('_MI_DEBASER_PLAYLISTBLOCK_DESC','Block für eine Public Playlist');
define('_MI_DEBASER_OWNDIR','User der Gruppen die eigene Verzeichnisse für den Upload haben dürfen.');
define('_MI_DEBASER_INNERDISPLAY','Player innerhalb der Webseite anzeigen.');
define('_MI_DEBASER_USELAME','Lame benutzen?');
define('_MI_DEBASER_PATHTOLAME','Pfad zu Lame');
define('_MI_DEBASER_RESAMPLETO','Runtersamplen auf folgende Bitrate');
define('_MI_DEBASER_REMOTEREADER','Die Methode zum Antesten von Links um Dateiinformationen zu erhalten. Hier sollte nichts geändert werden. Sollte cURL installiert, hätte dies immer Vorrang vor fopen.');
define('_MI_DEBASER_EQUALIZERBLOCK','Flash-Equalizer');
define('_MI_DEBASER_EQUALIZERBLOCK_DESC','Zeigt einen Flash-Equalizer im Block an');
define('_MI_DEBASER_ALLOWUPLOAD','Uploads erlauben');
define('_MI_DEBASER_ALLOWUPLOADDSC','Folgenden Gruppen Uploads erlauben');
define('_MI_DEBASER_ALLOWFLASHUPLOAD','Flash-Uploader');
define('_MI_DEBASER_ALLOWFLASHUPLOADDSC','Folgende Gruppen die Benutzung des Flash-Uploaders erlauben');
define('_MI_DEBASER_ALLOWPLAYLIST','Playlists erlauben');
define('_MI_DEBASER_ALLOWPLAYLISTDSC','Folgenden Gruppen das Anlegen von Playlists erlauben');
define('_MI_DEBASER_USEQUOTA','Disk-Quota');
define('_MI_DEBASER_USEQUOTADSC','Folgende Gruppen soll eine Disk-Quota haben');
define('_MI_DEBASER_ALLOWDOWNLOAD','Download erlauben');
define('_MI_DEBASER_ALLOWDOWNLOADDSC','Folgenden Gruppen den Download von Dateien erlauben');
define('_MI_DEBASER_CANDELETE','Eigene Dateien löschen');
define('_MI_DEBASER_CANDELETEDSC','Folgende Gruppen dürfen ihre eigenen Dateien löschen');
define('_MI_DEBASER_CANEDIT','Eigene Dateien bearbeiten');
define('_MI_DEBASER_CANEDITDSC','Folgende Gruppen dürfen ihre eigenen Dateien bearbeiten');
define('_MI_DEBASER_BROKENREASON','Gründe für defekte Dateien');
define('_MI_DEBASER_BROKENREASONDSC','');
// Start Gruende fuer defekte Dateien
define('_MI_DEBASER_BROK1','URL stimmt nicht mehr');
define('_MI_DEBASER_BROK2','Datei lässt sich nicht abspielen');
// Ende Gruende fuer defekte Dateien
define('_MI_DEBASER_ALLOWEMBED','Code-Einbettung anzeigen?');
define('_MI_DEBASER_ALLOWEMBEDDSC','Soll HTML-Code zum Einbetten der Dateien auf fremden Webseiten angezeigt werden? Diese Funktion ist ajax-basiert');
define('_MI_DEBASER_MASTERLANG','Standard-Sprache');
define('_MI_DEBASER_MASTERLANGDESC','Sollte die Multilingualität nicht genutzt werden, aber es wird trotzdem ein Sprachen-Hack eingesetzt, muss hier die Standardsprache eingetragen werden');
define('_MI_DEBASER_COND','Bedingung für LoFi-Version');
define('_MI_DEBASER_CONDDSC','Bedingung damit der User die LoFi-Version angezeigt bekommt. Bei "Gruppe" die Gruppen-IDs mit Leerzeichen voneinander getrennt eintragen. Bei "Rang" den Rang (ID) mit Leerzeichen voneinander getrennt eintragen. Bei "Beiträgen" die Obergrenze an Beiträgen eintragen bis zu dem (einschließlich) LoFi-Versionen angezeigt werden sollen. <b>Existiert keine LoFi-Version, wird die HiFi-Version abgespielt!</b>');
define('_MI_DEBASER_CONDGROUP','Gruppe');
define('_MI_DEBASER_CONDRANK','Rang');
define('_MI_DEBASER_CONDPOSTS','Beiträge');
define('_MI_DEBASER_CONDCODE','Bedingungswert für LoFi-Version');
define('_MI_DEBASER_NOHOTLINK', 'Anti-Hotlinking aktivieren?');
define('_MI_NOHOTLINKDSC', 'Falls aktiviert nur Ihre Website und erlaubte externe Webseiten können auf Ihre Dateien verlinken. Es können bei dieser Einstellung Konflikte auftreten, wenn in den Player Codeeinbettung aktiviert worden ist.');
define('_MI_DEBASER_ALLOWHOTLINK', 'Erlaubte Webseiten für Hotlinking');
define('_MI_DEBASER_ALLOWHOTLINKDSC', 'Falls Anti-Hotlinking aktiviert worden ist, können hier die erlaubten Webseiten eingetragen werden. Zum Beispiel: "http://www.xoops.org". An das Ende der Adresse keinen Slash / anhängen. Weitere Webseiten müssen mit einer Pipe "|" separiert werden.');
define('_MI_DEBASER_USELAMESING','Lame benutzen bei Einzeluploads');
define('_MI_DEBASER_USELAMESINGDSC','Re-Encoding der MP3-Dateien beim Einzelupload. Kann beim Flashupload zu Timeouts führen.');
define('_MI_DEBASER_ADDGENRES','Kategorie erstellen');
define('_MI_DEBASER_ADDPLAYERS','Player erstellen');
define('_MI_DEBASER_ADDFILETYPES','Dateityp anlegen');
define('_MI_DEBASERRAD_ADD','Radiosender anlegen');
define('_MI_DEBASERTV_ADD','TV-Sender anlegen');
define('_MI_DEBASER_BATCH','Batchverarbeitung');
define('_MI_DEBASER_SAMEAUTHOR','Dateien vom Einsender anzeigen');
define('_MI_DEBASER_SAMEAUTHORDSC','Die Anzahl der Dateien vom selben Einsender kann auf singlefile.php begrenzt werden. 0 bedeutet: Zeige ALLE Dateien an. -1 bedeutet: Zeige KEINE Dateien an.');
define('_MI_DEBASER_SIMLIMIT','Unterste Ähnlichkeitsgrenze');
define('_MI_DEBASER_SIMLIMITDSC','Geben Sie die unterste Grenze in Prozent (nur als Zahl) ein, bei der ähnliche Dateien angezeigt werden sollen. -1 bedeutet: dieses Feature ausschalten.');
define('_MI_DEBASER_USEFFMPEG','ffmpeg benutzen?');
define('_MI_DEBASER_USEFFMPEGDSC','ffmpeg UND ImageMagick müssen installiert sein, um dieses Feature zu nutzen. Beide Programme extrahieren Thumbnails aus einem Video und verbinden es zu einem animierten GIF. Es gibt aber noch mehr Einsatzmöglichkeiten für ffmpeg. Momentan ist aber standardmäßig nur diese Einsatzmöglichkeit vorgesehen.');
define('_MI_DEBASER_USEFFMPEGSING','ffmpeg benutzen bei Einzeluploads');
define('_MI_DEBASER_USEFFMPEGSINGDSC','Bildextraktion beim Einzelupload. Kann beim Flashupload zu Timeouts führen.');
define('_MI_DEBASER_PATHTOFFMPEG','Pfad zu ffmpeg');
define('_MI_DEBASER_FFMPEGFRAMES','Framepositionen');
define('_MI_DEBASER_FFMPEGFRAMESDSC','Geben Sie die Position der zu extrahierenden Frames ein. Werte müssen in % angegeben und mit einem Leerzeichen separiert werden. Hinweis: Es sollten besser Positionen vom Anfang der Datei genommen werden, da die Skriptausführung bei großen Dateien sehr lange dauern kann.');
define('_MI_DEBASER_FFMPEGDELAY','Verzögerung zwischen den Frames (in Millisekunden)');
define('_MI_DEBASER_FFMPEGDELAYDSC','Gibt den Wert in Millisekunden an in der von einem Frame zum nächsten Frame im animierten GIF weitergeschaltet wird.');
define('_MI_DEBASER_FFMPEGTHUMBSIZE','Größe des Thumbnails');
define('_MI_DEBASER_FFMPEGTYPES','Dateitypen für die ffmpeg-Konvertierung');
define('_MI_DEBASER_FFMPEGTYPESDSC','Geben Sie die Dateierweiterungen ein, separiert durch ein Leerzeichen.');
?>