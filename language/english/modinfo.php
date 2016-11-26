<?php
// $Id: language/english/modinfo.php,v 0.10 2004/04/24 10:00:00 frankblack Exp $
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

define('_MI_DEBASER_NAME', 'debaser');

//language defines for template manager
define('_MI_DEBASER_UPLOADER', 'debaser Uploader');
define('_MI_DEBASER_MPEGPLAYER', 'Player in Popup-Window');
define('_MI_DEBASER_INDEX', 'debaser main page');
define('_MI_DEBASER_GENRES', 'Displays the categories for debaser');
define('_MI_DEBASER_AMEDITGENRE', 'ADMIN: Edit category');
define('_MI_DEBASER_AMGENREMANAGE', 'ADMIN: category manager');
define('_MI_DEBASER_AMPLAYMANAGE', 'ADMIN: player manager');
define('_MI_DEBASER_AMSHOWMPEGS', 'ADMIN: Displays files grouped by genre');
define('_MI_DEBASER_AMEDITPLAY', 'ADMIN: Edit players');
define('_MI_DEBASER_EDITMPEGS', 'ADMIN: Edit files');
define('_MI_DEBASER_APPROVE', '<strong>ADMIN:</strong> Approve files');
define('_MI_DEBASER_SINGLEFILE', 'Shows the files');

define('_MI_DEBASER_DESC', 'Mediaplayer for Xoops 2.x');

//language define for submenu
define('_MI_DEBASER_SUBMIT', 'Submit');
define('_MI_DEBASER_MYDEBASER', 'Select player');

// defines for blocks
define('_MI_DEBASER_LATEST', 'Latest files');
define('_MI_DEBASER_LATEST_DESC', 'Shows latest files');
define('_MI_DEBASER_RATED', 'Top rated files');
define('_MI_DEBASER_RATED_DESC', 'Shows top rated files');
define('_MI_DEBASER_HITS', 'Top Downloads');
define('_MI_DEBASER_HITS_DESC', 'Shows the top downloaded files');
define('_MI_DEBASER_VIEWS', 'Top Views');
define('_MI_DEBASER_VIEWS_DESC', 'Shows the top visited files');
define('_MI_DEBASER_DISPLATEST', 'Play latest file');
define('_MI_DEBASER_DISPLATEST_DESC', 'Plays the latest file inside the block');
define('_MI_DEBASER_DISPRATED', 'Play best rated file');
define('_MI_DEBASER_DISPRATED_DESC', 'Plays the best rated file inside the block');
define('_MI_DEBASER_DISPFEATURED', 'Play featured file');
define('_MI_DEBASER_DISPFEATURED_DESC', 'Plays a featured file inside the block');
define('_MI_DEBASER_DISPDOWN', 'Play top downloaded file');
define('_MI_DEBASER_DISPDOWN_DESC', 'Plays the top downloaded file inside the block');
define('_MI_DEBASER_DISPVIEWED', 'Play most viewed file');
define('_MI_DEBASER_DISPVIEWED_DESC', 'Plays the most viewed file inside the block');

// preferences constants
define('_MI_DEBASER_DOWNLOAD', '1. Download');
define('_MI_DEBASER_ALLOWDOWN', '<span style="color:#ff0000;"><em>Allow Downloads</em></span>');
define('_MI_DEBASER_PLAYLIST', '2. Playlists');
define('_MI_DEBASER_PLAYLISTDSC', '<span style="color:#ff0000;"><em>Allow playlists (NOT YET)</em></span>');
define('_MI_DEBASER_VIEWLIMIT', '3. Files per page');
define('_MI_DEBASER_VIEWLIMITDESC', '<span style="color:#ff0000;"><em>Files to be displayed per page</em></span>');
define('_MI_DEBASER_UPLOAD', '4. Upload permission');
define('_MI_DEBASER_UPLOADDESC', '<span style="color:#ff0000;"><em>Groups that are allowed to submit files</em></span>');
define('_MI_DEBASER_ANONPOST', '5. Allow anonymous to submit files');
define('_MI_DEBASER_ANONPOSTDESC', '');
define('_MI_DEBASER_AUTOFILEAPPROVE', '6. Autoapprove single file uploads?');
define('_MI_DEBASER_AUTOFILEAPPROVEDSC', '<span style="color:#ff0000;"><em>Single file uploads will approved at once</em></span>');
define('_MI_DEBASER_AUTOLINKAPPROVE', '7. Autoapprove link submissions?');
define('_MI_DEBASER_AUTOLINKAPPROVEDSC', '<span style="color:#ff0000;"><em>Link submissions will approved at once</em></span>');
define('_MI_DEBASER_AUTOBATAPPROVE', '8. Autoapprove batchloads?');
define('_MI_DEBASER_AUTOBATAPPROVEDSC', '<span style="color:#ff0000;"><em>Batchloads will approved at once</em></span>');
define('_MI_DEBASER_MAXSIZE', '9. Max. Single uploadsize in bytes');
define('_MI_DEBASER_MAXSIZEDSC', '<span style="color:#ff0000;"><em>The default value is taken from php.ini</em></span>');
define('_MI_DEBASER_UPSEL', '10. Kind of data submission');
define('_MI_DEBASER_UPSELDSC', '<span style="color:#ff0000;"><em>Here you can define if files, links to files or both can be submitted</em></span>');
define('_MI_DEBASER_UPFILE', 'Files');
define('_MI_DEBASER_UPLINK', 'Links');
define('_MI_DEBASER_UPBOTH', 'Both');
define('_MI_DEBASER_GUESTVOTE', '11. Guest Rating');
define('_MI_DEBASER_GUESTVOTEDSC', '<span style="color:#ff0000;"><em>Here you can define if guests are allowed to vote for files</em></span>');
define('_MI_DEBASER_AUTOGENRESINGLE', '12. Automatic categories for single uploads?');
define('_MI_DEBASER_AUTOGENRESINGLEDSC', '<span style="color:#ff0000;"><em>Here you can define if categories will be created from the file-information of the file or if you want to make own categories</em></span>');
define('_MI_DEBASER_AUTOGENREBATCH', '13. Automatic categories for batchloads?');
define('_MI_DEBASER_AUTOGENREBATCHDSC', '<span style="color:#ff0000;"><em>Here you can define if categories will be created from the file-information of the file or if you want to make own categories</em></span>');
define('_MI_DEBASER_CATEGORYIMG', '14. Upload directory for category images');
define('_MI_DEBASER_USETHUMBS', '15. Thumbnails:');
define('_MI_DEBASER_USETHUMBSDSC', '<span style="color:#ff0000;"><em>Supported file types: JPG, GIF, PNG.<br /><br />Downloads will use thumb nails for images. Set to \'No\' to use original image if the server does not support this option.</em></span>');
define('_MI_DEBASER_SHOTWIDTH', '16. Maximum width of screenshot/thumbnails images');
define('_MI_DEBASER_SHOTHEIGHT', '17. Maximum height of screenshot/thumbnails images');
define('_MI_DEBASER_SHOTWIDTHDSC', '');
define('_MI_DEBASER_QUALITY', '18. Thumb Nail Quality: ');
define('_MI_DEBASER_IMGUPDATE', '19. Update Thumbnails?');
define('_MI_DEBASER_IMGUPDATEDSC', '<span style="color:#ff0000;"><em>If selected Thumbnail images will be updated at each page render, otherwise the first thumbnail image will be used regardless.</em></span>');
define('_MI_DEBASER_KEEPASPECT', '20. Keep Image Aspect Ratio?');
define('_MI_DEBASER_KEEPASPECTDSC', '');
define('_MI_DEBASER_USECATPERM', '21. Use permissions for categories?');
define('_MI_DEBASER_USECATPERMDSC', '<span style="color:#ff0000;"><em>Here you can define if you want to have additional work by using a permission system for categories or not</em></span>');
define('_MI_DEBASER_USEFILEPERM', '22. Use permissions for files?');
define('_MI_DEBASER_USEFILEPERMDSC', '<span style="color:#ff0000;"><em>Here you can define if you want to have additional work by using a permission system for files or not</em></span>');
define('_MI_DEBASER_PRESELECTPLAY', '23. Player preselection');
define('_MI_DEBASER_PRESELECTPLAYDESC', "<span style='color:#ff0000;'><em>Here you can define which group uses pre-selected players. Guests will always use preselected players.</em></span>");
define('_MI_DEBASER_SORTBY', '24. Sort the files on:');
define('_MI_DEBASER_SORTBY_DSC', '<span style="color:#ff0000;"><em>Here you can define how to sort the files on the user side</em></span>');
define('_MI_DEBASER_ORDERBY', '25. Order the files on:');
define('_MI_DEBASER_ORDERBY_DSC', '<span style="color:#ff0000;"><em>Here you can define the order of the files on the user side</em></span>');
define('_MI_DEBASER_CATSORTBY', '26. Sort the categories on:');
define('_MI_DEBASER_CATSORTBY_DSC', "<span style='color:#ff0000;'><em>Here you can define how to sort the categories on the user side</em></span>");
define('_MI_DEBASER_CATORDERBY', '27. Order the categories on:');
define('_MI_DEBASER_CATORDERBY_DSC', "<span style='color:#ff0000;'><em>Here you can define the order of the categories on the user side</em></span>");
define('_MI_DEBASER_USETOOLTIPS', '28. Use tooltips:');
define('_MI_DEBASER_USETOOLTIPSDSC', "<span style='color:#ff0000;'><em>Here you can define if tooltips with additional info should be used for genre.php and blocks</em></span>");
define('_MI_DEBASER_ID', 'ID');
define('_MI_DEBASER_ARTIST', 'Artist');
define('_MI_DEBASER_TITLE', 'Title');
define('_MI_DEBASER_WEIGHT', 'Weight');
define('_MI_DEBASER_CATEGORY', 'Categoryname');

//defines for flyout menu
define('_MI_DEBASER_ADMIN', 'Administration');
define('_MI_DEBASER_EDITGENRES', 'Edit categories');
define('_MI_DEBASER_EDITPLAYERS', 'Edit Players');
define('_MI_DEBASER_SINGLEUP', 'Single Upload');
define('_MI_DEBASER_BATCH', 'Batch load');
define('_MI_DEBASER_MAPPROVE', 'Approve files');
define('_MI_DEBASER_PERMISSIONS', 'Permissions');
define('_MI_DEBASER_FILETYPES', 'Filetypes');
define('_MI_DEBASERRAD_ADMIN', 'Radio Administration');

//defines for notifications
define('_MI_DEBASER_GLOBAL_NOTIFY', 'Global');
define('_MI_DEBASER_GLOBAL_NOTIFYDSC', 'Global notifications.');

define('_MI_DEBASER_GENRE_NOTIFY', 'Category');
define('_MI_DEBASER_GENRE_NOTIFYDSC', 'Notifications concerning categories.');

define('_MI_DEBASER_GENRE_NEWGENRE_NOTIFY', 'New category');
define('_MI_DEBASER_GENRE_NEWGENRE_NOTIFYCAP', 'Notify upon new categories.');
define('_MI_DEBASER_GENRE_NEWGENRE_NOTIFYDSC', 'Notification upon new categories.');
define('_MI_DEBASER_GENRE_NEWGENRE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic notification: New category');

define('_MI_DEBASER_SONG_NOTIFY', 'Files');
define('_MI_DEBASER_SONG_NOTIFYDSC', 'Notifications concerning files.');

define('_MI_DEBASER_SONG_NEWSONG_NOTIFY', 'New file');
define('_MI_DEBASER_SONG_NEWSONG_NOTIFYCAP', 'Notify upon new files.');
define('_MI_DEBASER_SONG_NEWSONG_NOTIFYDSC', 'Notifications upon new files.');
define('_MI_DEBASER_SONG_NEWSONG_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic Notification: New file');

define('_MI_DEBASER_SUBMIT_NOTIFY', 'Files');
define('_MI_DEBASER_SUBMIT_NOTIFYDSC', 'Notifications concerning uploads.');

define('_MI_DEBASER_NEWSUBMIT_NOTIFY', 'New upload');
define('_MI_DEBASER_NEWSUBMIT_NOTIFYCAP', 'Notify upon new uploads.');
define('_MI_DEBASER_NEWSUBMIT_NOTIFYDSC', 'Notification upon new uploads.');
define('_MI_DEBASER_NEWSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic Notification: New upload');

define('_MI_DEBASER_MIMETYPESUBMIT_NOTIFY', 'Unknown mimetype');
define('_MI_DEBASER_MIMETYPESUBMIT_NOTIFYCAP', 'Notify upon unknown mimetypes.');
define('_MI_DEBASER_MIMETYPESUBMIT_NOTIFYDSC', 'Notification upon unknown mimetypes.');
define('_MI_DEBASER_MIMETYPESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic Notification: Unknown mimetype');

define('_MI_DEBASER_EMPTYMIMETYPE_NOTIFY', 'Empty mimetype');
define('_MI_DEBASER_EMPTYMIMETYPE_NOTIFYCAP', 'Notify upon empty mimetypes.');
define('_MI_DEBASER_EMPTYMIMETYPE_NOTIFYDSC', 'Notification upon empty mimetypes.');
define('_MI_DEBASER_EMPTYMIMETYPE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatic Notification: Empty mimetype');

define('_MI_DEBASERRAD_DESC', 'Gives a popup block with which you can play internet radio');
define('_MI_DEBASERRAD_TITLE', 'Internetradio');

define('_MI_DEBASER_WARNING',
       'This module comes as is, without any guarantees whatsoever. Although this module is beta, it is still under active development. This release can be used in a live website or a production environment, but its use is under your own responsibilityi, which means the author is not responsible.');
define('_MI_DEBASER_AUTHORMSG', 'debaser is my first XOOPS module so do not complain about the code. Forgive me for not knowing better, but I am quite proud that I came so far. Not bad for someone with very limited programming skills? Hard work for someone who is only able to write &lt;?php echo "Hello World"; ?&gt; ;-).');
define('_MI_DEBASER_MODULE_DISCLAIMER', 'Disclaimer');
define('_MI_DEBASER_AUTHOR_WORD', 'Author comments');
define('_MI_DEBASER_MODULE_STATUS', 'Version');
define('_MI_DEBASER_AUTHOR_INFO', 'Author information');
define('_MI_DEBASER_AUTHOR_WEBSITE', 'Website of the author');
define('_MI_DEBASER_AUTHOR_EMAIL', 'E-Mail of the author');
define('_MI_DEBASER_AUTHOR_CREDITS', 'Module credits');
define('_MI_DEBASER_MODULE_SUPPORT', 'Supportsite');
define('_MI_DEBASER_MODULE_BUG', 'Submit bug');
define('_MI_DEBASER_MODULE_FEATURE', 'Request feature');
define('_MI_DEBASER_MODULE_INFO', 'General module information');
define('_AM_DEBASER_BY', 'Developed by');

define('_MI_DEBASER_HELP', 'Help');

define('_MI_DEBASER_CREDITS', 'I would like to thank all module developers who gave me the opportunity to steal and learn from their excellent scripts. Especially: "About this module" stolen from marcan\'s smart-modules. "Mimetype-Management", "Category-Images" and parts of getfile.php stolen from various wf-modules. "Admin-Interface" stolen from hszalasar\'s modules. I would like to thank the getid3-project for their class for reading file information. I would like to thank Mark Lubkowitz for his id3-class which inspired me for this module. I would like to thank Bob Janes for his exoops-conversion of iRadio. Of course I would like to thank Predator, chapi, phppp, Marcan, Liquid, gnikalu and Mithrandir for their help. If I forgot someone: Sorry!');
