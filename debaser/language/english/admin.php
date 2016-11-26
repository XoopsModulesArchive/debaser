<?php
// $Id: language/english/admin.php,v 0.80 2004/10/24 10:00:00 frankblack Exp $
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

define('_AM_DEBASER_SAVE', 'Save');
define('_AM_DEBASER_NEWPLAYER', 'New Player');
define('_AM_DEBASER_NAME', 'Name:');
define('_AM_DEBASER_NEWPLAYERNAME', 'New player name');
define('_AM_DEBASER_CODE', 'Code:');
define('_AM_DEBASER_NEWPLAYERCODE', 'New player code');
define('_AM_DEBASER_DELETED', ' deleted');
define('_AM_DEBASER_NOTDELETED', ' not deleted');
define('_AM_DEBASER_PERMISSIONS', 'Permissions');

//language defines for amdebaser.html
define('_AM_DEBASER_SHOWFILES', 'Show/edit files:');
define('_AM_DEBASER_EDITGENRES', 'Edit categories');
define('_AM_DEBASER_EDITPLAYERS', 'Edit Players');
define('_AM_DEBASER_SINGLEUP', 'Single Upload');
define('_AM_DEBASER_PREFS', 'Preferences');
define('_AM_DEBASER_BATCH', 'Batch load');
define('_AM_DEBASER_APPROVE', 'Approve files');
define('_AM_DEBASER_SHOWSORT', 'Show files sorted by categories');
define('_AM_DEBASER_TOAPPROVE', 'Files to be approved');

//language defines for amshowmpegs.html
define('_AM_DEBASER_ID', 'ID:');
define('_AM_DEBASER_PLAY', 'Play');
define('_AM_DEBASER_FILEADMIN', 'File Administration');
define('_AM_DEBASER_SUREDELETEFILE', 'Are you sure you want to delete this file?');

//language defines for ameditmpegs.html
define('_AM_DEBASER_ARTIST', 'Artist:');
define('_AM_DEBASER_TITLE', 'Title:');
define('_AM_DEBASER_ALBUM', 'Album:');
define('_AM_DEBASER_YEAR', 'Year:');
define('_AM_DEBASER_COMMENT', 'Comment:');
define('_AM_DEBASER_TRACK', 'Track:');
define('_AM_DEBASER_GENRE', 'Category:');
define('_AM_DEBASER_LENGTH', 'Length:');
define('_AM_DEBASER_WEIGHT', 'Weight');
define('_AM_DEBASER_WEIGHT_DSC', 'If the \'Sort by Weight\' option is turned on in the preferences, the files will be sorted by their weight on the user side index page.');

//language defines for amplaymanage.html
define('_AM_DEBASER_NEWPLAYADD', 'New player added');
define('_AM_DEBASER_SUREDELETEPLAYER', 'Are you sure you want to delete this player?');
define('_AM_DEBASER_PLAYERADMIN', 'Player Administration');
define('_AM_DEBASER_HEIGHT', 'Player-Height:');
define('_AM_DEBASER_WIDTH', 'Player-Width:');
define('_AM_DEBASER_AUTOSTART', 'Autostart:');

//various defines for admin site
define('_AM_DEBASER_DBUPDATE', 'Database updated');
define('_MD_DEBASER_MAXUPSIZE', 'Max. Upload Size = ');
define('_AM_DEBASER_ALLUP', 'All files have been uploaded!');
define('_AM_DEBASER_GO', 'Go!');
define('_AM_DEBASERRAD_ADMIN', 'Radio Administration');

//language defines for admin/amapprove
define('_AM_DEBASER_APPROVE2', 'Approve:');
define('_AM_DEBASER_NOAPPROVE', 'There are no files to approve');

//language defines for amgenremanage.html
define('_AM_DEBASER_ADDNEWGENRE', 'Add new category');
define('_AM_DEBASER_EDITGENRE', 'Edit category');
define('_AM_DEBASER_GENREADMIN', 'Category Administration');
define('_AM_DEBASER_SUREDELETEGENRE', 'Are you sure you want to delete this category? If there are still files in this category they will be also deleted.');

//language defines for upload
define('_MD_DEBASER_EXTLINK', 'Ext. Link');
define('_MD_DEBASER_GENRE', 'Category:');
define('_MD_DEBASER_ARTIST', 'Artist:');
define('_MD_DEBASER_TITLE', 'Title:');
define('_MD_DEBASER_ALBUM', 'Album:');
define('_MD_DEBASER_YEAR', 'Year:');
define('_MD_DEBASER_COMMENT', 'Add. Info:');
define('_MD_DEBASER_TRACK', 'Track:');
define('_MD_DEBASER_LENGTH', 'Length:');
define('_MD_DEBASER_BITRATE', 'Bitrate:');
define('_MD_DEBASER_FREQUENCY', 'Frequency:');
define('_AM_DEBASER_SINGLEUPSUCC', 'Upload was successful');
define('_AM_DEBASER_UPLOADFILELINKNO', 'You cannot send in both, a file and a link at the same time!');
define('_MD_DEBASER_ALREADYEXIST', 'File already exist!');

//language defines for admin/radioindex.php
define('_AM_DEBASERRAD_EDIT', 'Change settings');
define('_AM_DEBASERRAD_ERR1', 'Insert the name of the radio station');
define('_AM_DEBASERRAD_ERR2', 'Insert a stream url of the radio station');
define('_AM_DEBASERRAD_NAME', 'Name of the radio station');
define('_AM_DEBASERRAD_NEW', 'Add new radio station');
define('_AM_DEBASERRAD_NOST', 'There are no radio stations available');
define('_AM_DEBASERRAD_PICT', "Image of radio station<div style='padding-top: 8px;'><span style='font-weight: normal;'>The image have to reside in /debaser/images</span></div>");
define('_AM_DEBASERRAD_PROG', 'Available radio stations');
define('_AM_DEBASERRAD_STREAM', 'URL of stream');
define('_AM_DEBASERRAD_TITLE', 'Internetradio');
define('_AM_DEBASERRAD_URL', 'URL of website');

define('_AM_DEBASER_EDITPLAYER', 'Edit player');
define('_AM_DEBASER_EDITMPEG', 'Edit file');

define('_AM_DEBASER_GOMOD', 'Go to module');
define('_AM_DEBASER_HELP', 'Help');
define('_AM_DEBASER_ABOUT', 'About this module');
define('_AM_DEBASER_MODADMIN', ' Module administration:');
define('_AM_DEBASER_NOSONGAVAIL', 'There are no files for this category!');

define('_MD_DEBASER_ADDLINK', 'Add link');
define('_MD_DEBASER_ADDMPEG', 'Add file');

define('_AM_DEBASER_GENREMOVE', 'Move files');
define('_AM_DEBASER_GENREFROM', 'From category: ');
define('_AM_DEBASER_GENRETO', ' to category: ');
define('_AM_DEBASER_MOVED', 'File(s) has/have been moved!');

define('_AM_DEBASER_ADDNEWSUBGENRE', 'Add subcategory');
define('_AM_DEBASER_SUBGENRE', 'Subcategory:');
define('_AM_DEBASER_GENREIN', ' create in ');

/* admin/mimetypes.php */
define('_AM_DEBASER_FILETYPES', 'Filetypes');
define('_AM_DEBASER_MIME_CREATEF', 'Create Mimetype');
define('_AM_DEBASER_MIME_MODIFYF', 'Modify Mimetype');
define('_AM_DEBASER_MIME_EXTF', 'File Extension:');
define('_AM_DEBASER_MIME_NAMEF', "Application Type/Name:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Enter application associated with this extension.</span></div>");
define('_AM_DEBASER_MIME_TYPEF', "Mimetypes:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Enter each mimetype associated with the file extension. Each mimetype must be seperated with a space.</span></div>");
define('_AM_DEBASER_MIME_ADMINF', 'Allowed Admin Mimetype');
define('_AM_DEBASER_MIME_USERF', 'Allowed User Mimetype');
define('_AM_DEBASER_MIME_CREATE', 'Create');
define('_AM_DEBASER_MIME_CLEAR', 'Reset');
define('_AM_DEBASER_MIME_MODIFY', 'Modify');
define('_AM_DEBASER_MIME_FINDMIMETYPE', 'Find New Mimetype:');
define('_AM_DEBASER_MIME_EXTFIND', "Search File Extension:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Enter file extension you wish to search.</span></div>");
define('_AM_DEBASER_MIME_FINDIT', 'Get Extension!');
define('_AM_DEBASER_MIME_CREATED', 'Mimetype Information Created');
define('_AM_DEBASER_MIME_MODIFIED', 'Mimetype Information Modified');
define('_AM_DEBASER_MIME_MIMEDELETED', 'Mimetype %s has been deleted');
define('_AM_DEBASER_DBERROR', 'Database error!');
define('_AM_DEBASER_MIME_DELETETHIS', 'Delete selected mimetype?');
define('_AM_DEBASER_MIME_DELETE', 'Delete');
define('_AM_DEBASER_MMIMETYPES', 'Mimetype Management');
define('_AM_DEBASER_MIME_INFOTEXT', '<ul><li>New mimetypes can be created, edit or deleted easily via this form.</li><li>Search for a new mimetypes via an external website.</li><li>View displayed mimetypes for Admin and User uploads.</li><li>Change mimetype upload status.</li></ul>');
define('_AM_DEBASER_MIME_ADMINFINFO', '<strong>Mimetypes that are available for Admin uploads:</strong>');
define('_AM_DEBASER_MIME_NOMIMEINFO', 'No mimetypes selected.');
define('_AM_DEBASER_MIME_USERFINFO', '<strong>Mimetypes that are available for User uploads:</strong>');
define('_AM_DEBASER_MIME_ID', 'ID');
define('_AM_DEBASER_MIME_NAME', 'Application Type');
define('_AM_DEBASER_MIME_EXT', 'EXT');
define('_AM_DEBASER_MIME_ADMIN', 'Admin');
define('_AM_DEBASER_MIME_USER', 'User');
define('_AM_DEBASER_MINDEX_ACTION', 'Action');
define('_AM_DEBASER_MINDEX_PAGE', '<strong>Page:<strong> ');
define('_AM_DEBASER_ONLINE', 'Online');
define('_AM_DEBASER_OFFLINE', 'Offline');
define('_AM_DEBASER_PLAYERPRESELECT', 'Default-Player');

define('_AM_DEBASER_FILE', 'File:');

/* class/uploader.php */
define('_MD_NOFILECHOOSE', 'You either did not choose a file to upload or the server has insufficient read/writes to upload this file.!');
define('_MD_INVALIDFILESIZE', 'Invalid File Size');
define('_MD_FILENAMEEMPTY', 'Filename Is Empty');
define('_MD_NOFILEUP', 'No file uploaded, this is a error');
define('_MD_PROBUP', 'There was a problem with your upload. Error: 0');
define('_MD_TOOBIG1', 'The file you are trying to upload is too big. Error: 1');
define('_MD_TOOBIG2', 'The file you are trying to upload is too big. Error: 2');
define('_MD_PARTIALLY', 'The file you are trying upload was only partially uploaded. Error: 3');
define('_MD_NOFILESEL4', 'No file selected for upload. Error: 4');
define('_MD_NOFILESEL5', 'No file selected for upload. Error: 5');
define('_MD_UPDIRNOTSET', 'Upload directory not set');
define('_MD_FAILOPENDIR', 'Failed opening directory: ');
define('_MD_FAILOPENDIRWRITE', 'Failed opening directory with write permission: ');
define('_MD_FILESIZE', 'File Size: ');
define('_MD_MAXSIZEALLOW', 'Maximum Size Allowed: ');
define('_MD_MIMENOTALLOW', 'MIME type not allowed: ');
define('_MD_FAILUPLOADING', 'Failed uploading file: ');
define('_MD_ERROR_RETURN', '<h4>Errors Returned While Uploading</h4>');

/* Permissions defines */
define('_AM_DEBASER_PERM_MANAGEMENT', 'Permission Management');
define('_AM_DEBASER_PERM_PERMSNOTE', '<div><strong>NOTE:</strong> Please be aware that even if you&#8217ve set correct viewing permissions here, a group might not see the articles or blocks if you don&#8217t also grant that group permissions to access the module. To do that, go to <strong>System admin > Groups</strong>, choose the appropriate group and click the checkboxes to grant its members the access.</div>');
define('_AM_DEBASER_PERM_CPERMISSIONS', 'Category Permissions');
define('_AM_DEBASER_PERM_CSELECTPERMISSIONS', 'Select categories that each group is allowed to view');
define('_AM_DEBASER_PERM_CNOCATEGORY', 'Cannot set permission\'s: No Categories\'s have been created yet!');
define('_AM_DEBASER_PERM_FPERMISSIONS', 'File Permissions');
define('_AM_DEBASER_PERM_FNOFILES', 'Cannot set permission\'s: No files have been created yet!');
define('_AM_DEBASER_PERM_FSELECTPERMISSIONS', 'Select the files that each group is allowed to view');

define('_AM_DEBASER_FCATEGORY_CIMAGE', 'Select Category Image:');
define('_AM_DEBASER_FCATEGORY_GROUPPROMPT', 'Category Access Permissions:');
