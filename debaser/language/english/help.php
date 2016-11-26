<?php
include __DIR__ . '/../../../../mainfile.php';

include __DIR__ . '/../../../../include/cp_functions.php';

    xoops_cp_header();

echo '
<table><tr><td>
<h1>debaser!</h1>

<ol><li><span style="color:#ff0000; font-weight:bold;">What is debaser?</span><br />
debaser is a small in Xoops embedded multimedia-play. With debaser it is possible to play various multimedia files inside Xoops. You can select several player.</li><br />

<li><span style="color:#ff0000; font-weight:bold;">System requirements for debaser</span><br />
debaser works best with Xoops 2.0.6 or higher. In older versions you\'ll see some nasty warnings and notices. Sorry for that.</li><br />

<li><span style="color:#ff0000; font-weight:bold;">How to install debaser</span><br />
debaser will be installed like any other module via module administration. The folders <strong>upload, batchload, images/category</strong> and <strong>images/category/thumbs</strong> need <strong>CHMOD 777</strong>.</li><br />

<li><span style="color:#ff0000; font-weight:bold;">How to upload files</span><br />
Simple! This can be done with <strong>single upload</strong> or <strong>batchload</strong>. With <strong>single upload</strong> you can upload one file at a time. The maximum size of the file depends on the settings in php.ini or the preferences of the module. When you are on the submit page the maximum size will be displayed. If you want to use <strong>batchload</strong> you have to upload the files with ftp into batchload folder. If this is done go to debaser administration and click on <strong>batchload</strong>. Every file in the batchload folder will be processed. For both upload possibilities you can select wether categories will be generated automatically or manually. During batchload it could happen, that files are added with no mimetype, but don\'t worry. These files will be marked to be approved and you can set the information for this.</li><br />

<li><span style="color:#ff0000; font-weight:bold;">How to add links to files</span><br />
Simple! This can be done from user side and admin side if module preferences allow this. You have to two choices. Type in all the information or at leas the URL. If you do not supply additional information the script tries to do this for you. But there are two bad things. The script tries to read the first 10 kb of the file. But not all information about a file lies within the first 10 kb. But why not reading more than 10 kb? It makes no sense to wait such a long time for only adding a link. The other bad news is if <strong>fopen</strong> is not allowed on the remote server the file cannot be read.</li><br />

<li><span style="color:#ff0000; font-weight:bold;">How to add players</span><br />
You can add as many players as you like. This can be done in administration. Just type in the name of the player and the code for the player. At the places where the code is asking for the source you have to type the following variable: <strong><@mp3file@></strong>. Places in the code where height, width and autostart are used, must be replaced with the variables <strong><@height@>, <@width@> and <@autostart@>. <strong>Always</strong> use double-quotes inside the player-code!</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Playing radio streams</span><br />
The functionality of iradio 0.5 is built into debaser. It is not possible to separate the functions of the radio player from the multimedia player. This means if you want to allow guests to use the radio block they have access to multimedia player part.</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Defining multimedia-formats</span><br />
Multimedia-files will be defined through their extension. In the administration part under <strong>Filetypes</strong> you\'ll find a form for creating new multimedia-formats. File-extension can be up to four characters long (without the dot). You must provide the name of the application and the mimetype of the file. The mimetype of a file consists of two parts divided by a slash. If you have more than one mimetype for a filetype then type them in separating each other with a blank. Always make the preselection for players. If you don\'t know the correct mimetype of a file just try to send the file in. On the redirect page the correct mimetype will be displayed.</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Additional information</span><br />
Notifications do not work with <strong>batchload</strong>! Or do you want that the email account or the inbox are flooded with messages?</li><br />

<li><span style="color:#ff0000; font-weight:bold;">debaser preferences</span><br />
There are a lot of preferences to set. An explanation of all the functions follow now.<br /><br />
<ol><li><span style="color: #006633; font-weight:bold;">Download:</span> Here you can allow if files can be downloaded.</li><br />
<li><span style="color: #006633; font-weight:bold;">Files per page:</span> How many files should be displayed per page?</li><br />
<li><span style="color: #006633; font-weight:bold;">Upload permission:</span> Here you can define which groups are allowed to upload files. Uploads for guests is a separate option.</li><br />
<li><span style="color: #006633; font-weight:bold;">Allow anonymous to submit files:</span> Must I explain this?</li><br />
<li><span style="color: #006633; font-weight:bold;">Autoapprove single file uploads?</span> Here you can define if single uploads are approved automatically.</li><br />
<li><span style="color: #006633; font-weight:bold;">Autoapprove submitted links?</span> Here you can define if submitted links are approved automatically.</li><br />
<li><span style="color: #006633; font-weight:bold;">Autoapprove batchloads?</span> Here you can define if batchloads are approved automatically.</li><br />
<li><span style="color: #006633; font-weight:bold;">Max. Single uploadsize in bytes:</span> Here you can define the maximum size for single uploads. The value displayed reflects the maximum setting from your php.ini.</li><br />
<li><span style="color: #006633; font-weight:bold;">Kind of data submission</span> Here you can define if files, links to files or both can be submitted.</li><br />
<li><span style="color: #006633; font-weight:bold;">Guest Rating</span> Here you can define if guests are allowed to vote for files.</li><br />
<li><span style="color: #006633; font-weight:bold;">Automatic categories for single uploads?</span> If you want to have less work (depends) use this option set this option to <strong>Yes</strong>. In this case categories will be read (if available) from the file. If <strong>No</strong> it is possible to select the categories from a pulldown-menu. If category information is not set or can\'t be read the category will be defined as <strong>Other</strong>. Of course you can change the name of this category. This category cannot be deleted with debaser administration. If you delete this category with phpMyAdmin this module may not function properly. If you don\'t want this category to be displayed use the permission system of debaser.</li><br />
<li><span style="color: #006633; font-weight:bold;">Automatic categories for batchloads?</span> For explanation see the previous point.</li><br />
<li><span style="color: #006633; font-weight:bold;">Upload directory for category images</span> Here you can define the path for the category images. If you select another folder than the default one, make sure that this folder is writable.</li><br />
<li><span style="color: #006633; font-weight:bold;">Thumbnails:</span> Category images will be displayed through the thumbnails. Set the option to <strong>No</strong> if you server does not use any graphic-libraries. Supported filetypes are gif, jpg and png.</li><br />
<li><span style="color: #006633; font-weight:bold;">Maximum width of thumbnail images</span> Here you can define the maximum width of the category images. Works only if option Thumbnails is set to <strong>Yes</strong>.</li><br />
<li><span style="color: #006633; font-weight:bold;">Maximum height of thumbnail images</span> Here you can define the maximum height of the category images. Works only if option Thumbnails is set to <strong>Yes</strong>.</li><br />
<li><span style="color: #006633; font-weight:bold;">Thumbnail Quality:</span> Here you can define the quality of the category images. The value <strong>100</strong> means quality 100 %. Works only if option Thumbnails is set to <strong>Yes</strong>.</li><br />
<li><span style="color: #006633; font-weight:bold;">Update Thumbnails?</span> If selected thumbnail images will be updated at each page render, otherwise the first thumbnail image will be used regardless. Works only if option Thumbnails is set to <strong>Yes</strong>.</li><br />
<li><span style="color: #006633; font-weight:bold;">Keep Image Aspect Ratio?</span> Works only if option Thumbnails is set to <strong>Yes</strong>.</li><br />
<li><span style="color: #006633; font-weight:bold;">Use permission system for categories?</span> Here you can define if you want to use the XOOPS permission system for categories or not.</li><br />
<li><span style="color: #006633; font-weight:bold;">Use permission system for files?</span> Here you can define if you want to use the XOOPS permission system for files or not.</li><br />
<li><span style="color: #006633; font-weight:bold;">Player preselection</span> Here you can define the groups that should use preselected players. Anonymous users use preselected players per default.</li><br />
<li><span style="color: #006633; font-weight:bold;">Sort files on</span> Files could be sorted on different criteria.</li><br />
<li><span style="color: #006633; font-weight:bold;">Order of files</span> Order of files can be ascending or descending.</li><br />
<li><span style="color: #006633; font-weight:bold;">Sort categories on</span> Categories could be sorted on different criteria.</li><br />
<li><span style="color: #006633; font-weight:bold;">Order of categories</span> Order of categories can be ascending or descending.</li><br />
<li><span style="color: #006633; font-weight:bold;">Use tooltips</span> With tooltips additional information will be shown on links in the blocks and in genre.php.</li><br />
</ol></li><br />

<li><span style="color:#ff0000; font-weight:bold;">Changes in 0.7</span><br />
- New id3-class which reads id3v1- and id3vs-information<br />
- debaser works definitely with register_globals Off<br />
- Notifications added<br />
- Automatic or manual creation of categories<br />
- Registered users can select their favourite player<br />
- Links to mpeg-files could be submitted<br />
- Overlib&copy; library for popup-information. If you want to use Overlib&copy; with your templates visit <a href="http://smarty.php.net/manual/en/language.function.popup.php" target="_blank">http://smarty.php.net</a> or <a href="http://www.bosrup.com/web/overlib/" target="_blank">http://www.bosrup.com</a><br />
</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Changes in 0.8</span><br />
- New id3-class that is able to read information from nearly any file format<br />
- Creation of unlimited nested subcategories<br />
- Nearly all forms are using now xoopsform-classes<br />
- Complete rewrite of admin side<br />
- Complete rewrite of myDebaser-page. Now players can be assigned to filetypes<br />
- File- and mimetypes can be managed now<br />
- Moving files to other categories<br />
- Superfluos templates deleted<br />
- Permissions added<br />
- Admin will be notified if unknown filetypes are submitted<br />
- Images for main categories<br />
</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Changes in 0.9</span><br />
- Categories and files can be sorted on different criteria<br />
- Number of files in categories will be displayed<br />
- Block for popular files added<br />
- Preselection of players<br />
</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Changes in 0.91</span><br />
- Counter for visited files and downloads<br />
- New Popup-Info-Script<br />
- Bugfixes in upload-script<br />
</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Changes in 0.92</span><br />
- Additional fields for inserting height, width and autostart of the player<br />
- New blocks which will play the files inside the block<br />
- Bugfix max. upload size<br />
- Bugfix Radioadministration, long stream-urls are now possible<br />
- Filetype .wmv added
</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Credits</span><br />
onlamp.swf and various small parts are not written by me but from Mark Lubkowitz (mail@mark-lubkowitz.de). Overlib&copy; is from http://www.bosrup.com/web/overlib/. The id3-class is getid3. At this point I would like to thank Chapi, gnikalu and Predator for their support.</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Apologies</span><br />
This is my first module, so do not complain about the code. If you find severe errors please report them to <a href="http://dev.xoops.org/modules/xfmod/tracker/?func=add&group_id=1024&atid=197" target="_blank">bug tracker</a>.</li><br />

<li><span style="color:#ff0000; font-weight:bold;">Things to do</span><br />
- Playlists<br />
- Winamp player
etc. pp.</li><br /></ul>

Enjoy!
frankblack  
</td></tr></table>';

    xoops_cp_footer();
