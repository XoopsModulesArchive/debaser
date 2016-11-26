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

define('_MI_DEBASER_DESC','Multimedia player for Xoops 2.x');
define('_MI_DEBASER_SUBMITFILE','Submit file');
define('_MI_DEBASER_SUBMITLINK','Submit link');
define('_MI_DEBASER_SUBMITLINKDSC','The groups allowed to submit links');
define('_MI_DEBASER_LATEST','Current files');
define('_MI_DEBASER_LATEST_DESC','Shows the newest files');
define('_MI_DEBASER_RATED','Top rated files');
define('_MI_DEBASER_RATED_DESC','Shows the top rated files');
define('_MI_DEBASER_HITS','Top downloads');
define('_MI_DEBASER_HITS_DESC','Shows the top downloaded files');
define('_MI_DEBASER_VIEWS','Top views');
define('_MI_DEBASER_VIEWS_DESC','Shows the most viewed files');
define('_MI_DEBASER_DISPLATEST','Play newest file');
define('_MI_DEBASER_DISPRATED','Play best rated file');
define('_MI_DEBASER_DISPFEATURED','Play featured file');
define('_MI_DEBASER_DISPDOWN','Play top download file');
define('_MI_DEBASER_DISPVIEWED','Play the most viewed file');

// preferences constants
define('_MI_DEBASER_VIEWLIMIT','Files to be shown on the backend');
define('_MI_DEBASER_VIEWLIMITDESC','Files to be shown per page on the backend');
define('_MI_DEBASER_VIEWLIMITFRONT','Files to be shown on the frontend');
define('_MI_DEBASER_VIEWLIMITFRONTDESC','Files to be shown per page on the frontend');
define('_MI_DEBASER_MAXSIZE','Maximum upload size in bytes');
define('_MI_DEBASER_MAXSIZEDSC','The preset value will be read from php.ini');
define('_MI_DEBASER_GUESTVOTE','Guest rating');
define('_MI_DEBASER_GUESTVOTEDSC','Set if guests are allowed to rate files');
define('_MI_DEBASER_GUESTDOWNLOAD','Guest download');
define('_MI_DEBASER_GUESTDOWNLOADDSC','Set if guests are allowed to download files');
define('_MI_DEBASER_SHOTWIDTH', 'Maximum width of category images');
define('_MI_DEBASER_SHOTHEIGHT', 'Maximum height of category images');
define('_MI_DEBASER_CATIMAGEFSIZE','Maximum filesize of category images');
define('_MI_DEBASER_SORTBY', 'Sort files by:');
define('_MI_DEBASER_SORTBY_DSC', 'Determine how the files should be sorted by on the frontend');
define('_MI_DEBASER_ORDERBY','Order of files:');
define('_MI_DEBASER_ORDERBY_DSC','Determine the order of the displayed files');
define('_MI_DEBASER_CATSORTBY', 'Sort categories by:');
define('_MI_DEBASER_CATSORTBY_DSC','Determine how the categories should be sorted on the frontend');
define('_MI_DEBASER_CATORDERBY','Order of categories:');
define('_MI_DEBASER_CATORDERBY_DSC','Determine the order of the displayed categories');
define('_MI_DEBASER_ID','ID');
define('_MI_DEBASER_ARTIST','Artist');
define('_MI_DEBASER_TITLE','Title');
define('_MI_DEBASER_WEIGHT','Weight');
define('_MI_DEBASER_CATEGORY','Category name');

//defines for flyout menu
define('_MI_DEBASER_ADMIN','Administration');
define('_MI_DEBASER_EDITGENRES','Edit categories');
define('_MI_DEBASER_EDITPLAYERS','Edit players');
define('_MI_DEBASER_MAPPROVE','Approve files');

//defines for notifications
define('_MI_DEBASER_GLOBAL_NOTIFY', 'General');
define('_MI_DEBASER_GLOBAL_NOTIFYDSC', 'General notification options.');

define('_MI_DEBASER_GENRE_NOTIFY', 'Category');
define('_MI_DEBASER_GENRE_NOTIFYDSC', 'Notification options concerning categories.');

define ('_MI_DEBASER_GENRE_NEWGENRE_NOTIFY', 'New category');
define ('_MI_DEBASER_GENRE_NEWGENRE_NOTIFYCAP', 'Notify on new categories.');
define ('_MI_DEBASER_GENRE_NEWGENRE_NOTIFYDSC', 'Notification on new categories.');
define ('_MI_DEBASER_GENRE_NEWGENRE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic notification: New category');

define ('_MI_DEBASER_SONG_NOTIFY', 'Files');
define ('_MI_DEBASER_SONG_NOTIFYDSC', 'Notification options concerning files/links.');

define ('_MI_DEBASER_SONG_NEWSONG_NOTIFY', 'New file/link');
define ('_MI_DEBASER_SONG_NEWSONG_NOTIFYCAP', 'Notify on new files/links.');
define ('_MI_DEBASER_SONG_NEWSONG_NOTIFYDSC', 'Notification on new files/links.');
define ('_MI_DEBASER_SONG_NEWSONG_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic notification: New file/link');

define ('_MI_DEBASER_SUBMIT_NOTIFY', 'Files');
define ('_MI_DEBASER_SUBMIT_NOTIFYDSC', 'Notification options concerning uploads.');

define ('_MI_DEBASER_NEWSUBMIT_NOTIFY', 'New upload');
define ('_MI_DEBASER_NEWSUBMIT_NOTIFYCAP', 'Notify on new uploads.');
define ('_MI_DEBASER_NEWSUBMIT_NOTIFYDSC', 'Notification on new uploads.');
define ('_MI_DEBASER_NEWSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic notification: New upload');

define ('_MI_DEBASER_MIMETYPESUBMIT_NOTIFY', 'Unknown mimetype');
define ('_MI_DEBASER_MIMETYPESUBMIT_NOTIFYCAP', 'Notify on unknown mimetypes.');
define ('_MI_DEBASER_MIMETYPESUBMIT_NOTIFYDSC', 'Notification on unknown mimetypes.');
define ('_MI_DEBASER_MIMETYPESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic notification: Unknown mimetype');

define ('_MI_DEBASER_EMPTYMIMETYPE_NOTIFY', 'Empty mimetype');
define ('_MI_DEBASER_EMPTYMIMETYPE_NOTIFYCAP', 'Notify on empty mimetypes.');
define ('_MI_DEBASER_EMPTYMIMETYPE_NOTIFYDSC', 'Notification on empty mimetypes.');
define ('_MI_DEBASER_EMPTYMIMETYPE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic notification: Empty mimetype');

define ('_MI_DEBASER_REPORTBROKEN_NOTIFY', 'Broken file/link');
define ('_MI_DEBASER_REPORTBROKEN_NOTIFYCAP', 'Notify on broken files/links.');
define ('_MI_DEBASER_REPORTBROKEN_NOTIFYDSC', 'Notification on broken files/links.');
define ('_MI_DEBASER_REPORTBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic notification: Broken file/link');

define('_MI_DEBASERRAD_ADMIN','Edit radio station');
define('_MI_DEBASERTV_ADMIN','Edit TV station');
define('_MI_DEBASERRAD_TITLE','Internetradio');
define('_MI_DEBASERTV_TITLE','Internet-TV');

// admin/menu.php
define('_MI_DEBASER_FILETYPES', 'Edit mimetype');

define('_MI_DEBASER_DISKQUOTA','Maximum size of user directories in bytes');
define('_MI_DEBASER_FORM_OPTIONS','Editor selection');
define('_MI_DEBASER_FORM_OPTIONS_DESC','Select the editor you want to be used.');
define('_MI_DEBASER_FORM_COMPACT','Compact');
define('_MI_DEBASER_FORM_DHTML','DHTML');
define('_MI_DEBASER_FORM_SPAW','Spaw-Editor');
define('_MI_DEBASER_FORM_HTMLAREA','HtmlArea-Editor');
define('_MI_DEBASER_FORM_FCK','FCK-Editor');
define('_MI_DEBASER_FORM_KOIVI','Koivi');
define('_MI_DEBASER_FORM_TINYEDITOR','TinyEditor');
define('_MI_DEBASER_FORM_TINYMCE','TinyMCE');

define('_MI_DEBASER_MULTILANG','Use multilingual feature');
define('_MI_DEBASER_MULTILANGDESC','If your XOOPS is prepared for multilingual content (best used with xlanguage), you can use this feature to write multilingual content without any extra markup.');
define('_MI_DEBASER_MYSETTINGS','User settings');
define('_MI_DEBASER_MYPLAYLIST','Playlist');
define('_MI_DEBASER_AUTOAPPROVE','Approve files automatically?');
define('_MI_DEBASER_BATCHAPPROVE','Approve batched files automatically?');
define('_MI_DEBASER_FLASHBATCH','Multiples upload with flash');
define('_MI_DEBASER_FLASHBATCHDSC','Enter the number of uploads at one time for the flash uploader. Works only if permissions for flash upload are set.');
define('_MI_DEBASER_PERMISSIONS','Permissions');
define('_MI_DEBASER_MAINTENANCE','Maintenance');
define('_MI_DEBASER_GOTOMOD','Go to modules');
define('_MI_DEBASER_PLAYLISTBLOCK','Public Playlist');
define('_MI_DEBASER_PLAYLISTBLOCK_DESC','Block for a public playlist');
define('_MI_DEBASER_OWNDIR','User for these groups have their own directories for uploads.');
define('_MI_DEBASER_INNERDISPLAY','Display player inside website (no popup)');
define('_MI_DEBASER_USELAME','Use lame?');
define('_MI_DEBASER_PATHTOLAME','Path to lame');
define('_MI_DEBASER_RESAMPLETO','Resample to bitrate (with options)');
define('_MI_DEBASER_REMOTEREADER','Method of testing links for information retrieval. Nothing should be changed here. If cURL is installed, cURL has priority over fopen.');
define('_MI_DEBASER_EQUALIZERBLOCK','Flash equalizer');
define('_MI_DEBASER_EQUALIZERBLOCK_DESC','Displays a flash equalizer in a block');
define('_MI_DEBASER_ALLOWUPLOAD','Allow uploads');
define('_MI_DEBASER_ALLOWUPLOADDSC','Groups to be allowed to submit files');
define('_MI_DEBASER_ALLOWFLASHUPLOAD','Flash uploader');
define('_MI_DEBASER_ALLOWFLASHUPLOADDSC','Groups to be allowed to use the flash uploader');
define('_MI_DEBASER_ALLOWPLAYLIST','Allow playlists');
define('_MI_DEBASER_ALLOWPLAYLISTDSC','Groups to be allowed to have playlists');
define('_MI_DEBASER_USEQUOTA','Disk quota');
define('_MI_DEBASER_USEQUOTADSC','Groups having a disk quota');
define('_MI_DEBASER_ALLOWDOWNLOAD','Allow downloads');
define('_MI_DEBASER_ALLOWDOWNLOADDSC','Groups to be allowed to download files');
define('_MI_DEBASER_CANDELETE','Delete own files');
define('_MI_DEBASER_CANDELETEDSC','Groups to be allowed to delete their own files');
define('_MI_DEBASER_CANEDIT','Edit own files');
define('_MI_DEBASER_CANEDITDSC','Groups to be allowed to edit their own files');
define('_MI_DEBASER_BROKENREASON','Reasons for broken files');
define('_MI_DEBASER_BROKENREASONDSC','You can add any reason you want, by editing language/yourlanguage/modinfo.php. Look for the "reasons for broken files" and add some more defines there. After doing so revisit the preferences and add the new constants here.');
// Start: reasons for broken files
define('_MI_DEBASER_BROK1','URL is wrong');
define('_MI_DEBASER_BROK2','File cannot be played');
// End: reasons for broken files
define('_MI_DEBASER_ALLOWEMBED','Show code embedding?');
define('_MI_DEBASER_ALLOWEMBEDDSC','Should the HTML code for embedding files on foreign website be displayed? This function is ajax based');
define('_MI_DEBASER_MASTERLANG','Default language');
define('_MI_DEBASER_MASTERLANGDESC','If the multilingual function is not used here, but a language hack is used on your site the default language should be entered here. Best practice: do not change anything here.');
define('_MI_DEBASER_COND','Condition for LoFi version');
define('_MI_DEBASER_CONDDSC','Condition for LoFi version to be played. For "Group", enter the group ids separated by blanks. For "Rank", enter the ranks (id) separated by blanks. For "Posts" enter the number of posts a user should have to get the LoFi version. <b>If there is no LoFi version, the HiFi version will be played!!</b>');
define('_MI_DEBASER_CONDGROUP','Group');
define('_MI_DEBASER_CONDRANK','Rank');
define('_MI_DEBASER_CONDPOSTS','Posts');
define('_MI_DEBASER_CONDCODE','Condition code for LoFi version');
define('_MI_DEBASER_NOHOTLINK', 'Enable anti-hotlink?');
define('_MI_NOHOTLINKDSC', 'When enabled, only your website and allowed external websites can link to the files. This setting could collide, when you enabled code embedding in the players.');
define('_MI_DEBASER_ALLOWHOTLINK', 'Hotlinking allowed external websites');
define('_MI_DEBASER_ALLOWHOTLINKDSC', 'When anti-hotlink is enabled, you can authorize websites to link to your video files by entering their address here.
For example: "http://www.xoops.org". Do not append an ending slash / to the address. For more than one website, use a pipe "|" to seperate them.');
define('_MI_DEBASER_USELAMESING','Use Lame on single uploads');
define('_MI_DEBASER_USELAMESINGDSC','Reencoding of MP3 files during single upload. Can lead to timeouts when using the flash uploader.');
define('_MI_DEBASER_ADDGENRES','Add category');
define('_MI_DEBASER_ADDPLAYERS','Add player');
define('_MI_DEBASER_ADDFILETYPES','Add mimetype');
define('_MI_DEBASERRAD_ADD','Add radio station');
define('_MI_DEBASERTV_ADD','Add TV station');
define('_MI_DEBASER_BATCH','Batch processing');
define('_MI_DEBASER_SAMEAUTHOR','Display files from poster');
define('_MI_DEBASER_SAMEAUTHORDSC','You can limit the files to be shown from the same poster on singlefile.php. 0 means: show ALL files. -1 means: show NO files.');
define('_MI_DEBASER_SIMLIMIT','Lower similarity limit');
define('_MI_DEBASER_SIMLIMITDSC','Enter the value (only a digit) which is the percentage of the lowest similarity files could have to be displayed. -1 means: turn this feature off.');
define('_MI_DEBASER_USEFFMPEG','Use ffmpeg?');
define('_MI_DEBASER_USEFFMPEGDSC','You have to install ffmpeg AND ImageMagick to use this feature. Both extract thumbnails from videos and join them as an animated gif. But there are many more things you could use ffmpeg for. At the moment only this feature is used in debaser.');
define('_MI_DEBASER_USEFFMPEGSING','Use ffmpeg on single uploads');
define('_MI_DEBASER_USEFFMPEGSINGDSC','Extraction of images with ffmpeg during single upload. Can lead to timeouts when using the flash uploader.');
define('_MI_DEBASER_PATHTOFFMPEG','Path to ffmpeg');
define('_MI_DEBASER_FFMPEGFRAMES','Position of frames');
define('_MI_DEBASER_FFMPEGFRAMESDSC','Enter the position of frames to be extracted. Values have to be in % and to be separated with blanks. Hint: Better take positions from the beginning of the file, otherwise the scripts takes too long on large files.');
define('_MI_DEBASER_FFMPEGDELAY','Delay between frames');
define('_MI_DEBASER_FFMPEGDELAYDSC','Gives the value in milliseconds from frame to the next frame in the animated gif.');
define('_MI_DEBASER_FFMPEGTHUMBSIZE','Size of the thumbnail');
define('_MI_DEBASER_FFMPEGTYPES','File types for ffmpeg conversion');
define('_MI_DEBASER_FFMPEGTYPESDSC','Enter the file extensions separated by a blank.');
?>