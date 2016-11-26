<?php
// $Id: language/english/main.php,v 0.10 2004/04/24 10:00:00 frankblack Exp $
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

define('_MD_DEBASER_FILE', 'File:');
define('_MD_DEBASER_ALBUM', 'Album:');
define('_MD_DEBASER_YEAR', 'Year:');
define('_MD_DEBASER_COMMENT', 'Additional info:');
define('_MD_DEBASER_TRACK', 'Track:');
define('_MD_DEBASER_GENRE', 'Category:');
define('_MD_DEBASER_LENGTH', 'Length:');
define('_MD_DEBASER_BITRATE', 'Bitrate:');
define('_MD_DEBASER_FREQUENCY', 'Frequency:');
define('_MD_DEBASER_DOWNLOAD', 'Download');
define('_MD_DEBASER_UPLOADSUCC', 'Upload was successful!');
define('_MD_DEBASER_TITLE', 'Title:');
define('_MD_DEBASER_ARTIST', 'Artist:');
define('_MD_DEBASER_SECONDS', 'minutes');
define('_MD_DEBASER_KBITS', 'kBit/s');
define('_MD_DEBASER_HERTZ', 'Hz');

//language defines for category.html
define('_MD_DEBASER_INDEX', 'Index');
define('_MD_DEBASER_PLAY', 'Play');
define('_MD_DEBASER_NOFILES', 'There are currently no files available for this category');

//language defines for upload.html
define('_MD_DEBASER_MAXUPSIZE', 'Max. Upload Size = ');
define('_MD_DEBASER_MAXBYTES', 'bytes');
define('_MD_DEBASER_EXTLINK', 'Ext. Link');
define('_MD_DEBASER_FCATEGORY_GROUPPROMPT', 'Category access permission:');

//language defines for getfile.php
define('_MD_DEBASER_FILENOTFOUND', 'The file could not be found!');

//language defines for singlefile.php
define('_MD_DEBASER_READMORE', 'More information...');
define('_MD_DEBASER_RATETHIS', 'Rate this file!');
define('_MD_DEBASER_RATING', 'Rating');
define('_MD_DEBASER_VOTES', 'Votes');
define('_MD_DEBASER_NOTRATED', 'This file hasn&#8217t been rated yet');
define('_MD_DEBASER_VIEWS', 'Views');
define('_MD_DEBASER_HITS', 'Downloads');
define('_MD_DEBASER_EDIT', 'Edit');

//language defines for ratefile.php
define('_MD_DEBASER_NORATING', 'You did not select a rating for this file!');
define('_MD_DEBASER_VOTEONCE', 'Please do not vote for the same resource more than once.');
define('_MD_DEBASER_VOTEAPPRE', 'Your vote is appreciated.');
define('_MD_DEBASER_THANKYOU', 'Thank you for taking the time to vote here at %s');
define('_MD_DEBASER_UNKNOWNERROR', 'ERROR. Returning you to where you where!');

//language defines for mydebaser.php
define('_MD_DEBASER_DBUPDATED', 'Database Updated Successfully!');
define('_MD_DEBASER_MYFAVPLAYER', 'Select your favourite player:');

//language defines for player.php
define('_MD_DEBASER_NOPLAYERYET', '<strong>You have not selected a player! Set your player in submenu myDebaser.</strong><br /><br />This window will close automatically.');

//language defines for include/functions.php
define('_MD_DEBASER_ALREADYEXIST', 'File already exist!');

define('_MD_DEBASER_ADDLINK', 'Add link');
define('_MD_DEBASER_ADDMPEG', 'Add file');

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
define('_MD_FAILOPENDIR', 'Failed opening directory:');
define('_MD_FAILOPENDIRWRITE', 'Failed opening directory with write permission: ');
define('_MD_FILESIZE', 'File Size:');
define('_MD_MAXSIZEALLOW', 'Maximum Size Allowed:');
define('_MD_MIMENOTALLOW', 'Mimetype not allowed: ');
define('_MD_FAILUPLOADING', 'Failed uploading file: ');
define('_MD_ERROR_RETURN', '<h4>Errors Returned While Uploading</h4>');

/* uploadresult.php */
define('_MD_DEBASER_CLOSEWIN', 'Close window');
define('_MD_DEBASER_THANKYOUAPPROVE', 'Thanks for your submission. Your submission must be approved by the webmaster.');
