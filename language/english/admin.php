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
// Name for this module
define('_AM_DEBASER_MODNAME','debaser');

define('_AM_DEBASER_NEWPLAYER','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">New Player</div>');
define('_AM_DEBASER_ADDPLAYER','Add player');
define('_AM_DEBASER_NAME','Name:');
define('_AM_DEBASER_CODE','Code:');
define('_AM_DEBASER_DELETED',' deleted');
define('_AM_DEBASER_NOTDELETED',' not deleted');
define('_AM_DEBASER_APPROVE','Approve files');
define('_AM_DEBASER_SHOWSORT','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Show files by categories</div>');
define('_AM_DEBASER_TOAPPROVE','Files to be approved:');
define('_AM_DEBASER_ID','ID:');
define('_AM_DEBASER_FILEADMIN','File administration');
define('_AM_DEBASER_SUREDELETEFILE','Are you sure you want to delete this file?');
define('_AM_DEBASER_ARTIST','Artist:');
define('_AM_DEBASER_TITLE','Title:');
define('_AM_DEBASER_ALBUM','Album:');
define('_AM_DEBASER_TYPEOFLINK','Link:');
define('_AM_DEBASER_YEAR','Year:');
define('_AM_DEBASER_COMMENT','Description:');
define('_AM_DEBASER_TRACK','Track:');
define('_AM_DEBASER_GENRE','Category:');
define('_AM_DEBASER_LENGTH','Length:');
define('_AM_DEBASER_NEWPLAYADD','New player added');
define('_AM_DEBASER_SUREDELETEPLAYER','Do you really want to delete this player?');
define('_AM_DEBASER_PLAYERADMIN','Player');
define('_AM_DEBASER_HEIGHT','Player height:');
define('_AM_DEBASER_WIDTH','Player width:');
define('_AM_DEBASER_AUTOSTART','Autostart:');
define('_AM_DEBASER_DBUPDATE','Database updated');
define('_AM_DEBASER_APPROVE2','Approval:');
define('_AM_DEBASER_NOAPPROVE','There are no files to approve');
define('_AM_DEBASER_ADDNEWGENRE','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Add new category</div>');
define('_AM_DEBASER_WEIGHT','Position');
define('_AM_DEBASER_BITRATE','Bitrate:');
define('_AM_DEBASERRAD_EDIT','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Edit radio station</div>');
define('_AM_DEBASERRAD_NAME','Name of radio station');
define('_AM_DEBASERRAD_NEW','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Add radio station</div>');
define('_AM_DEBASER_NEWTV','New TV station');
define('_AM_DEBASER_EDITTV','Edit TV station');
define('_AM_DEBASERRAD_NOST','There are no radio stations yet');
define('_AM_DEBASERTV_NOST','There are no tv stations yet');
define('_AM_DEBASERRAD_PICT','Image for radio station');
define('_AM_DEBASERTV_PICT','Image for TV station');
define('_AM_DEBASERRAD_PROG','Available radio stations');
define('_AM_DEBASERTV_PROG','Available TV stations');
define('_AM_DEBASERRAD_STREAM','URL of stream');
define('_AM_DEBASERRAD_URL','URL of website');
define('_AM_DEBASER_EDITPLAYER','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Edit player</div>');
define('_AM_DEBASER_EDITPLAYERMEN','Edit player');
define('_AM_DEBASER_EDITMPEG','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Edit file</div>');
define('_AM_DEBASER_NOSONGAVAIL','There are no files for this category!');
define('_AM_DEBASER_GENREMOVE','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Move files</div>');
define('_AM_DEBASER_GENREFROM','From category: ');
define('_AM_DEBASER_GENRETO',' to category: ');
define('_AM_DEBASER_MOVED','File(s) moved!');
define('_AM_DEBASER_MIME_CREATEF','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Add mimetype</div>');
define('_AM_DEBASER_MIME_MODIFYF','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Edit mimetype</div>');
define('_AM_DEBASER_MIME_EXTF','File extension: <a href="http://www.file-extensions.org" target="_blank"><img src="'.XOOPS_URL.'/modules/debaser/images/question.png" alt="Find new mimetypes" title="Find new mimetypes" /></a>');
define('_AM_DEBASER_MIME_NAMEF','Application type/name:<br /><span style="font-weight: normal">Type in the application to which the file extension is connected to.</span>');
define('_AM_DEBASER_MIME_TYPEF','Mimetypes:<br /><span style="font-weight: normal">Type in the mimetype to which the file extension is connected to. Every mimetype has to be separated by a blank.</span>');
define('_AM_DEBASER_MIME_ADMINF','Allowed admin mimetypes');
define('_AM_DEBASER_MIME_USERF','Allowed user mimetypes');
define('_AM_DEBASER_MIME_CREATE','Create');
define('_AM_DEBASER_MIME_CLEAR','Reset');
define('_AM_DEBASER_MIME_MODIFY','Modify');
define('_AM_DEBASER_MIME_EXTFIND','Search for file extension:<div style="padding-top: 8px;"><span style="font-weight: normal;">Type in the file extension you need more information about.</span></div>');
define('_AM_DEBASER_MIME_CREATED','Mimetype information created');
define('_AM_DEBASER_MIME_MODIFIED','Mimetyp information modified');
define('_AM_DEBASER_MIME_MIMEDELETED','Mimetype %s has been deleted');
define('_AM_DEBASER_MIME_DELETETHIS','Delete selected mimetype?');
define('_AM_DEBASER_MMIMETYPES','Mimetypes');
define('_AM_DEBASER_MIME_INFOTEXT','<ul><li>Mimetypes can be added, modified or deleted with this form.</li><li>Search for new mimetypes on an external website.</li><li>Display of mimetypes for admin or user uploads.</li><li>Modification of the mimetype upload status.</li></ul>');
define('_AM_DEBASER_MIME_ADMINFINFO','<strong>Available mimetypes for admin uploads:</strong>');
define('_AM_DEBASER_MIME_NOMIMEINFO','No mimetype selected.');
define('_AM_DEBASER_MIME_USERFINFO','<strong>Available mimetypes for user uploads:</strong>');
define('_AM_DEBASER_MIME_ID','ID');
define('_AM_DEBASER_MIME_NAME','Application');
define('_AM_DEBASER_MIME_EXT','EXT');
define('_AM_DEBASER_MIME_ADMIN','Admin');
define('_AM_DEBASER_MIME_USER','User');
define('_AM_DEBASER_MINDEX_ACTION','Action');
define('_AM_DEBASER_MINDEX_PAGE','<strong>Page:<strong> ');
define('_AM_DEBASER_ONLINE','Online');
define('_AM_DEBASER_OFFLINE','Offline');
define('_AM_DEBASER_FCATEGORY_CIMAGE', 'Select category image:');
define('_AM_DEBASER_MCATEGORY', 'Edit categories');
define('_AM_DEBASER_DBERROR', 'Database could not be updated!');
define('_AM_DEBASER_MODCAT','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Edit category</div>');
define('_AM_DEBASER_MODIFY','Edit');
define('_AM_DEBASER_CATDELETED','Category and all its data has been deleted!');
define('_AM_DEBASER_DELCCONF','Do you really want to delete this category?');
define('_AM_DEBASER_DELCAT','Delete category');
define('_AM_DEBASER_CATADDED','Category has been created');
define('_AM_DEBASER_SUBCAT','Subcategory of');
define('_AM_DEBASER_PERM_CPERMISSIONS','Category permissions');
define('_AM_DEBASER_PERM_CSELECTPERMISSIONS','Select the categories the groups are allowed to visit');
define('_AM_DEBASER_PERM_MANAGEMENT','Permission management');
define('_AM_DEBASER_TITLANGUAGE','Title in language: ');
define('_AM_DEBASER_DESCLANGUAGE','Description in language:<br />');
define('_AM_DEBASER_CATDESCRIPTION','Description:');
define('_AM_DEBASER_XSPF','Playlist support');
define('_AM_DEBASER_TOFIX','Broken files/links:');
define('_AM_DEBASER_MAINT','Maintenance');
define('_AM_DEBASER_REPDEF','Files/links reported to be broken');
define('_AM_DEBASER_NODEF','There are no broken files/links!');
define('_AM_DEBASER_FREQUENCY','Frequency:');
define('_AM_DEBASER_OWNER','Poster');
define('_AM_DEBASER_FILETYPE','Mimetype:');
define('_AM_DEBASER_URLTOSCRIPT','Directory of player');
define('_AM_DEBASER_PLAYERACTIVE','Show player?');
define('_AM_DEBASER_LANGSELECT','Select language');
define('_AM_DEBASER_PUREJS','Pure javascript player?');
define('_AM_DEBASER_SELPLAYICON','Select player icon');
define('_AM_DEBASER_MODSEC','mod_security is activated on your server and flash uploads are allowed here. Unfortunately due to this combination flash uploads are not possible. You can solve this problem with htaccess directive. Rename no.htaccess into .htaccess. mod_security is now disabled for debaser only. Deactivate mod_security at your own risk. Get informed about the risks before deactivating mod_security.<br /><br />');
define('_AM_DEBASER_PERM_CNOCATEGORY','There are no categories yet!');
define('_AM_DEBASERRAD_NOPLAY','<span style="color:#ff0000;">There are no mimetypes associated with the player!</span>');
define('_AM_DEBASER_PLATFORM','Online platform like YouTube');
define('_AM_DEBASER_SUBMITLINK','Submit links');
define('_AM_DEBASER_EQUALIZER','Show equalizer in template?<br /><span style="color:#ff0000;">Only for Flash based players</span>');
define('_AM_DEBASER_NOCAT2EDIT','<b>There are no categories to edit!</b>');
define('_AM_DEBASER_MADMIN','Administration');
define('_AM_DEBASER_MCATS','Categories');
define('_AM_DEBASER_MPERM','Permissions');
define('_AM_DEBASER_GO2MOD','Go to module');
define('_AM_DEBASER_PREFS','Preferences');
define('_AM_DEBASER_NEWCAT','Create category');
define('_AM_DEBASER_EDITCAT','Edit category');
define('_AM_DEBASER_NEWMIME','Add mimetype');
define('_AM_DEBASER_EDITMIME','Edit mimetype');
define('_AM_DEBASER_NEWRADIO','Add radio station');
define('_AM_DEBASER_EDITRADIO','Edit radio station');
define('_AM_DEBASER_LISTBROKEN','Show broken files/links');
define('_AM_DEBASER_EMBEDDING','Show code for embedding?');
define('_AM_DEBASER_LOFIOK','LoFi version already exists');
define('_AM_DEBASER_MAKELOFI','Write LoFi version (again)');
define('_AM_DEBASER_JEROEN','<b><small>The flash player in directory "jeroen" can only be used for non-commercial purposes. Otherwise a license has to be obtained at <a href="https://www.longtailvideo.com/players/order" target="_blank">this page</a>. To remove this notice just delete the file noncommercial.txt in directory "jeroen".</small></b>');
define('_AM_DEBASER_CHECKALL','Check all');
define('_AM_DEBASER_REWRITEWARN1','Some warnings have arisen while (re)writing the ID3 tags.<br />They were:<br />');
define('_AM_DEBASER_REWRITEWARN2','Error (re)writing ID3 tags to the re-encoded MP3 file. Keeping converted MP3 though.');
define('_AM_DEBASER_REWRITEWARN3','ERROR: there was an error moving the temporary file.');
define('_AM_DEBASERTV_NEW','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Add TV station</div>');
define('_AM_DEBASERTV_NAME','Name of TV station');
define('_AM_DEBASER_MIMENOUPUS','Could not update user information:');
define('_AM_DEBASER_MIMENOUPMIM','Could not update mimetype information:');
define('_AM_DEBASER_ALLBATCHED','Batch processing finished, files with errors have to be approved manually. You can now empty the folder batchload');
define('_AM_DEBASER_BATCHFORMMEN','Batch processing');
define('_AM_DEBASER_BATCHFORM','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Batch processing</div>');
define('_AM_DEBASER_USELAME','Reencode with Lame');
define('_AM_DEBASER_MOREINFO','More file infos');
//defines for more info
define('_AM_DEBASER_BDIRSCAN','<p>Directory scanned in %01.2f seconds.</p>');
define('_AM_DEBASER_BFILENAME','Filename');
define('_AM_DEBASER_BFILESCAN','File parsed in %01.2f seconds.<br />');
define('_AM_DEBASER_BAVERAGE','Average:');
define('_AM_DEBASER_BIDENTFILES','Identified Files:');
define('_AM_DEBASER_BTOTAL','Total:');
define('_AM_DEBASER_BWARNING','Warning');
define('_AM_DEBASER_BERROR','Error');
define('_AM_DEBASER_BSUCCDEL','Successfully deleted %s');
define('_AM_DEBASER_BFAILDEL1','FAILED to delete %s - error deleting file');
define('_AM_DEBASER_BFAILDEL2','FAILED to delete %s - file does not exist');
define('_AM_DEBASER_BFILESIZE','File Size');
define('_AM_DEBASER_BFORMAT','Format');
define('_AM_DEBASER_BPLAYTIME','Playtime');
define('_AM_DEBASER_BBROWSE','Browse: ');
define('_AM_DEBASER_BNOTEXIST','%s does not exist');
define('_AM_DEBASER_BNOREMOTE','<i>Cannot browse remote filesystems</i><br />');
define('_AM_DEBASER_BPARDIR','Parent directory: ');
define('_AM_DEBASER_BBITRATE','Bitrate');
define('_AM_DEBASER_BARTIST','Artist');
define('_AM_DEBASER_BTITLE','Title');
define('_AM_DEBASER_BFILEFILE','File (File)');
define('_AM_DEBASER_BDISABLE','disable');
define('_AM_DEBASER_BENABLE','enable');
define('_AM_DEBASER_BERRWARN','Errors/Warnings');
define('_AM_DEBASER_BVIEWDETAIL','View detailed analysis');
define('_AM_DEBASER_WTRACKOF',' of ');
define('_AM_DEBASER_WGENRE','Genre:');
define('_AM_DEBASER_WOTHERGENRE','Other Genre');
define('_AM_DEBASER_WWRITETAGS','Write Tags:');
define('_AM_DEBASER_REMOVETAGS','Remove non-selected tag formats when writing new tag');
define('_AM_DEBASER_WSTARTWRITE','Starting to write tag(s)<br />');
define('_AM_DEBASER_WINVALIDIMAGE','<b>Invalid image format (only GIF, JPEG, PNG)</b><br />');
define('_AM_DEBASER_WNOTOPEN','<b>Cannot open %s</b><br />');
define('_AM_DEBASER_WNOUPLOAD','<b>No upload happened</b><br />');
define('_AM_DEBASER_WEMBEDIMAGE','<b>WARNING:</b> Can only embed images for ID3v2<br />');
define('_AM_DEBASER_WSUCCWROTE','Successfully wrote tags<br />');
define('_AM_DEBASER_WBROWSECURR','Browse current directory');
define('_AM_DEBASER_WPICTURE','Picture<br />(ID3v2 only)');
define('_AM_DEBASER_WPICTURETYPE','Picture type');
define('_AM_DEBASER_BUNKNOWNFILES','Unknown Files:');
define('_AM_DEBASER_BERRORS','Errors:');
define('_AM_DEBASER_BWARNINGS','Warnings:');
define('_AM_DEBASER_WEDITWRITE','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Tag editor/writer</div>');
define('_AM_DEBASER_MIME_INLIST','Filetype for playlist<br /><span style="font-weight:normal">Select whether this filetype can be added to a playlist</span>');
define('_AM_DEBASER_BATCHFORIMGMEN','Batch thumbnails');
define('_AM_DEBASER_MAKETHUMB','Make thumbnails');
define('_AM_DEBASER_IMGWRITTEN','Thumbnails were created!');
?>