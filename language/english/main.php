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
define('_MD_DEBASER_YEAR','Year:');
define('_MD_DEBASER_COMMENT','Description:');
define('_MD_DEBASER_TRACK','Track:');
define('_MD_DEBASER_GENRE','Genre:');
define('_MD_DEBASER_LENGTH','Length:');
define('_MD_DEBASER_BITRATE','Bitrate:');
define('_MD_DEBASER_FREQUENCY','Frequency:');
define('_MD_DEBASER_DOWNLOAD','Download');
define('_MD_DEBASER_TITLE','Title:');
define('_MD_DEBASER_ARTIST','Artist:');
define('_MD_DEBASER_SECONDS','Minutes');
define('_MD_DEBASER_KBITS','kBit/s');
define('_MD_DEBASER_HERTZ','Hz');
define('_MD_DEBASER_INDEX','Index');
define('_MD_DEBASER_NOFILES','No files are available for this category');
define('_MD_DEBASER_MAXUPSIZE','Max. upload size = ');
define('_MD_DEBASER_MAXBYTES','Mb');
define('_MD_DEBASER_FILENOTFOUND','The file was not found!');
define('_MD_DEBASER_READMORE','Further information...');
define('_MD_DEBASER_RATETHIS', '< Rate this file!');
define('_MD_DEBASER_VOTES', 'Votes');
define('_MD_DEBASER_NOTRATED', 'This file is not yet rated');
define('_MD_DEBASER_VIEWS','Views');
define('_MD_DEBASER_HITS','Downloads');
define('_MD_DEBASER_EDIT','Edit');
define('_MD_DEBASER_NORATING', 'You did not select a rating for this file!');
define('_MD_DEBASER_VOTEONCE', 'Please vote only once for each file.');
define('_MD_DEBASER_VOTEAPPRE', 'We appreciate your voting.');
define('_MD_DEBASER_THANKYOU', 'Thank you for taking your time to vote on %s');
define('_MD_DEBASER_UNKNOWNERROR', 'ERROR. You are brought back!');
define('_MD_DEBASER_DBUPDATED','Database was updated!');
define('_MD_DEBASER_DBNOTUPDATED','Database was not updated!');
define('_MD_DEBASER_NOFLASHUPLOAD','Flashupload');
define('_MD_DEBASER_PUBLICPLAYLIST','Public playlist');
define('_MD_DEBASER_ADDMPEG','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Add file</div>');
define('_MD_DEBASER_ADDLINK','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Add link</div>');
define('_MD_DEBASER_EDITMPEG','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Edit file</div>');
define('_MD_DEBASER_TOOBIG','Disc space is not sufficient. In order to upload something, you have to delete several files.');
define('_MD_DEBASER_REMAIN','Available disc space (in Mb): ');
define('_MD_DEBASER_FILEADMIN','File administration');
define('_MD_DEBASER_SUREDELETEFILE','Are you sure you want to delete this file?');
define('_MD_DEBASER_NOTDELETED',' Not deleted');
define('_MD_DEBASER_DELETED',' Deleted');
define('_MD_DEBASER_FILEUPLOAD','File upload');
define('_MD_DEBASER_OTHERSONGS','Other files of the user');
define('_MD_DEBASER_MYPLAYLIST','My playlist');
define('_MD_DEBASER_NOCATEGORIES','No categories available!');
define('_MD_DEBASER_TOPLAYLIST','To the playlist');
define('_MD_DEBASER_WASADDED','<span style="font-weight:bold; color:#ff0000;">File was added to the playlist!</span>');
define('_MD_DEBASER_MYSETTINGS','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">My settings</div>');
define('_MD_DEBASER_OWNER','Owner');
define('_MD_DEBASER_NORADIOPARAM','No parameters for radio transmitter or player are available!');
// defines for flash uploader
define('_MD_DEBASER_ALRUPLOADED','File(s) uploaded');
define('_MD_DEBASER_FLASHWARN','<noscript style="color: #000; background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px;">We\'re sorry.  SWFUpload could not load.  You must have JavaScript enabled to enjoy SWFUpload.</noscript><div id="divLoadingContent" style="color: #000; background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">SWFUpload is loading. Please wait a moment...</div><div id="divLongLoading" style="color: #000; background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">SWFUpload is taking a long time to load or the load has failed.  Please make sure that the Flash Plugin is enabled and that a working version of the Adobe Flash Player is installed.</div><div id="divAlternateContent" style="color: #000;background-color: #FFFF66; border-top: solid 4px #FF9966; border-bottom: solid 4px #FF9966; margin: 10px 25px; padding: 10px 15px; display: none;">We\'re sorry.  SWFUpload could not load.  You may need to install or upgrade Flash Player. Visit the <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">Adobe website</a> to get the Flash Player.</div>');
define('_MD_DEBASER_APPROVE','Approved');
define('_MD_DEBASER_LANGSELECT','Language options');
define('_MD_DEBASER_NOSETTINGAVAIL','<span style="color:#ff0000; font-weight:bold;">No user settings are available at the moment!</span>');
define('_MD_DEBASER_PLATFORM','Online platform such as YouTube');
define('_MD_DEBASER_TYPEOFLINK','Link');
define('_MD_DEBASER_LINKADD','The link was added to the database');
define('_MD_DEBASER_PLUGINS','Installed plugins');
define('_MD_DEBASER_DESCLANGUAGE','Description in language:<br />');
define('_MD_DEBASER_CLOSEWIN','Close window');
define('_MD_DEBASER_REPORTBROKEN','<img src="'.XOOPS_URL.'/modules/debaser/images/upperleft.jpg" style="float:left;" alt="" /><div style="float:right; padding-right:5px; padding-top: 46px;">Report damaged file</div>');
define('_MD_DEBASER_BROKENREASON','Reason of damage');
define('_MD_DEBASER_BROKCOMMENT','Further information');
define('_MD_DEBASER_TELLAFRIEND','Tell a friend');
define('_MD_DEBASER_GETCODE','Select the player code, click into the description field, copy code and insert on your website.');
define('_MD_DEBASER_SELECTCODE','Select the player code');
define('_MD_DEBASER_EMBED','Embed the file');
define('_MD_DEBASER_CLICKHERE','Click on the image to play the video');
define('_MD_FAILUPLOADING','Upload failed!');
define('_MD_DEBASER_NOOPENHT', '.htaccess could not be opened for writing!');
define('_MD_DEBASER_NOWRITEHT', 'Writing of .htaccess failed!');
define('_MD_DEBASER_NOVALDATA','Sorry, but you did not send valid data or file was too big!');
define('_MD_DEBASER_UPSUCCESS','Upload successful!');
define('_MD_DEBASER_UPFAIL','Upload failed!');
define('_MD_DEBASER_NOSCRIPT','<span style="font-weight:bold; color:#ff0000">Javascript must be activated, if you want to use this module!</span>');
define('_MD_DEBASER_SEARCH','Search: ');
define('_MD_DEBASER_SEARCHRES','Search results:');
define('_MD_DEBASER_MAIN','Index');
define('_MD_DEBASER_YOUAREHERE','You are here');
define('_MD_DEBASER_TAFSENT','Recommendation was sent!');
define('_MD_DEBASER_TAFNOTSENT','Sending failed!');
define('_MD_DEBASER_RECTHIS','<b>Recommend this file to:</b>');
define('_MD_DEBASER_HUMAN','<small>Are you human? Then pick out the<\/small>');
define('_MD_DEBASER_INTEREST','This link could be interesting to you');
define('_MD_DEBASER_WRONGCAPTCHA','You clicked the wrong picture!');
define('_MD_DEBASER_SUSP','Form processing halted for suspicious activity');
define('_MD_DEBASER_ELAPSE','Too much time elapsed');
define('_MD_DEBASER_GETNOMIME','The mimetype of the sent file could not be detected. Get in touch with the webmaster of this site.');
define('_MD_DEBASER_CAPT_HOUSE','house');
define('_MD_DEBASER_CAPT_KEY','key');
define('_MD_DEBASER_CAPT_FLAG','flag');
define('_MD_DEBASER_CAPT_CLOCK','clock');
define('_MD_DEBASER_CAPT_BUG','bug');
define('_MD_DEBASER_CAPT_PEN','pen');
define('_MD_DEBASER_CAPT_LIGHTBULB','light bulb');
define('_MD_DEBASER_CAPT_NOTE','musical note');
define('_MD_DEBASER_CAPT_HEART','heart');
define('_MD_DEBASER_CAPT_WORLD','world');
define('_MD_DEBASER_SIMILAR','Similar files');
define('_MD_DEBASER_NOSIMATM','<b>There are no similar files at the moment!</b>');
?>