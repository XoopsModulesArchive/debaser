CREATE TABLE debaser_files (
	xfid int(11) NOT NULL auto_increment,
	filename text NOT NULL,
	added int(10) NOT NULL,
	title varchar(255) NOT NULL default '',
	artist varchar(255) NOT NULL default '',
	album varchar(255) NOT NULL default '',
	year int(4) NOT NULL,
	track int(3) NOT NULL,
	genreid int(11) NOT NULL,
	length varchar(50) NOT NULL default '',
	bitrate varchar(50) NOT NULL default '',
	frequence varchar(50) NOT NULL default '',
	rating double(6,4) NOT NULL default '0.0000',
	votes int(11) NOT NULL default '0',
	approved int(1) NOT NULL default '0',
	fileext varchar(10) NOT NULL default '',
	hits int(11) unsigned NOT NULL default '0',
	views int(11) unsigned NOT NULL default '0',
	uid int(11) NOT NULL,
	language varchar(50) NOT NULL default '',
	linktype varchar(255) NOT NULL default '',
	linkcode text NOT NULL default '',
	haslofi int(1) NOT NULL default '0',
  PRIMARY KEY  (xfid)
) TYPE=MyISAM;

CREATE TABLE debaser_broken (
	brokenid int(11) NOT NULL auto_increment,
	whichid int(11) NOT NULL,
	brokentitle varchar(255) NOT NULL default '',
	reporter int(11) NOT NULL,
	reason text NOT NULL default '',
	PRIMARY KEY (brokenid)
) TYPE=MyISAM;
	
CREATE TABLE debaser_text (
	textid int(11) NOT NULL auto_increment,
	textfileid int(11) NOT NULL,
	textfiletext text NOT NULL default '',
	textcatid int(11) NOT NULL,
	textcatsubid int(11) NOT NULL,
	textcattitle varchar(255) NOT NULL default '',
	textcattext text NOT NULL default '',
	language varchar(50) NOT NULL default '',
	PRIMARY KEY (textid)
)	TYPE=MyISAM;

CREATE TABLE debaser_player (
  xpid int(11) NOT NULL auto_increment,
  xsubid int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  html_code mediumtext NOT NULL,
  height int(4) NOT NULL,
  width int(4) NOT NULL,
  autostart varchar(10) NOT NULL default '',
  xspf varchar(4) NOT NULL default '',
  playericon varchar(255) NOT NULL default '',
  canplay text NOT NULL default '',
  urltoscript varchar(50) NOT NULL default '',
  isactive int(1) NOT NULL default '0',
  platform int(1) NOT NULL default '0',
  equalizer int(1) NOT NULL default '0',
  embedding int(1) NOT NULL default '0',
  PRIMARY KEY (xpid)
) TYPE=MyISAM;

CREATE TABLE debaser_user (
  dbusid int(11) NOT NULL auto_increment,
  debuid int(11) NOT NULL,
  publicplaylist int(1) NOT NULL default '0',
  flashupload int(1) NOT NULL default '0',
  playlist text NOT NULL,
  PRIMARY KEY  (dbusid)
) TYPE=MyISAM;

CREATE TABLE debaser_genre (
	genreid int(11) NOT NULL auto_increment,
	subgenreid int(11) NOT NULL default '0',
	genretitle varchar(255) NOT NULL default '',
	imgurl varchar(255) NOT NULL default '',
	catweight int(4) NOT NULL default '0',
	language varchar(50) NOT NULL default '',
	total int(11) NOT NULL default '0',
	PRIMARY KEY (genreid)
) TYPE=MyISAM;

CREATE TABLE debaservotedata (
	ratingid int(11) unsigned NOT NULL auto_increment,
	lid int(11) unsigned NOT NULL default '0',
	ratinguser int(11) NOT NULL default '0',
	rating tinyint(3) unsigned NOT NULL default '0',
	ratinghostname varchar(60) NOT NULL default '',
	ratingtimestamp int(10) NOT NULL default '0',
	PRIMARY KEY  (ratingid),
	KEY ratinguser (ratinguser),
	KEY ratinghostname (ratinghostname),
	KEY lid (lid)
) TYPE=MyISAM;

CREATE TABLE debaserradio (
  radio_id int(10) unsigned NOT NULL auto_increment,
  radio_name varchar(50) NOT NULL default '',
  radio_stream varchar(255) NOT NULL default '',
  radio_url varchar(255) NOT NULL default '',
  radio_picture varchar(50) NOT NULL default '',
  canplay text NOT NULL default '',
  PRIMARY KEY  (radio_id)
) TYPE=MyISAM;

CREATE TABLE debaser_tv (
  tv_id int(10) unsigned NOT NULL auto_increment,
  tv_name varchar(50) NOT NULL default '',
  tv_stream varchar(255) NOT NULL default '',
  tv_url varchar(255) NOT NULL default '',
  tv_picture varchar(50) NOT NULL default '',
  canplay text NOT NULL default '',
  PRIMARY KEY  (tv_id)
) TYPE=MyISAM;

INSERT INTO debaser_player VALUES (1, 0, 'Windows Media Player Audio', '<!--[if IE]><object id="MediaPlayer1" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab# Version=5,1,52,701" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject" width="<@width@>" height="<@height@>">\r\n<param name="fileName" value="<@mp3file@>" />\r\n<param name="animationatStart" value="true" />\r\n<param name="transparentatStart" value="true" />\r\n<param name="autoStart" value="<@autostart@>" />\r\n<param name="showControls" value="true" />\r\n<param name="Volume" value="-300" /><![endif]-->\r\n<object class="playerdisplay" type="application/x-mplayer2" data="<@mp3file@>" width="<@width@>" height="<@height@>">\r\n<param name="fileName" value="<@mp3file@>" />\r\n<param name="animationatStart" value="true" />\r\n<param name="transparentatStart" value="true" />\r\n<param name="autoStart" value="<@autostart@>" />\r\n<param name="showControls" value="true" />\r\n<param name="Volume" value="-300" />\r\n</object>\r\n<!--[if IE]></object><![endif]-->', 46, 280, '1', 'wpl', 'wmp.gif', 'mp3', '', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (2, 0, 'Flash Player - Audio', '<!--[if IE]>\r\n<object classid="CLSID:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="<@width@>" height="<@height@>">\r\n<param name="movie" value="<@urltoscript@>onlamp.swf?mp3file=<@mp3file@>" />\r\n<param name="quality" value="high" />\r\n<param name="bgcolor" value="#708090" /><![endif]-->\r\n<object class="playerdisplay" data="<@urltoscript@>onlamp.swf?mp3file=<@mp3file@>" width="<@width@>" height="<@height@>" type="application/x-shockwave-flash">\r\n<param name="bgcolor" value="#708090" />\r\n<param name="quality" value="high" />\r\n</object>\r\n<!--[if IE]></object><![endif]-->', 50, 400, '1', '0', 'onlamp.gif', 'mp3', 'onlamp', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (3, 0, 'Real Player Audio', '<!--[if IE]>\r\n<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="<@width@>" height="<@height@>">\r\n<param name="src" value="<@mp3file@>" />\r\n<param name="autostart" value="<@autostart@>" />\r\n<param name="controls" value="all" />\r\n<param name="console" value="audio" /><![endif]-->\r\n<object class="playerdisplay" type="audio/x-pn-realaudio-plugin" data="<@mp3file@>" width="<@width@>" height="<@height@>">\r\n<param name="autostart" value="<@autostart@>" />\r\n<param name="controls" value="all" />\r\n<param name="console" value="audio" />\r\n</object>\r\n<!--[if IE]></object><![endif]-->', 120, 320, 'true', 'smil', 'real.gif', 'mp3', '', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (4, 0, 'Real Player Video', '<!--[if IE]>\r\n<object id="RVOCX" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="<@width@>" height="<@height@>"><param name="src" value="<@mp3file@>" /><param name="autostart" value="<@autostart@>" /><param name="controls" value="imagewindow,all" /><param name="console" value="video" />\r\n<![endif]-->\r\n<object class="playerdisplay" type="audio/x-pn-realaudio-plugin"  src="<@mp3file@>" width="<@width@>" height="<@height@>" autostart="<@autostart@>" controls="imagewindow,all" console="video"> </object>\r\n<!--[if IE]></object><![endif]-->', 240, 320, '1', '0', '', '', '', 0, 0, 0, 0);
INSERT INTO debaser_player VALUES (5, 0, 'Windows Media Player Video', '<object class="playerdisplay" type="application/x-mplayer2" pluginspage = "http://www.microsoft.com/windows/mediaplayer/" width="<@width@>" height="<@height@>" src="<@mp3file@>" name="player" autostart="<@autostart@>" showcontrols="1" showstatusbar="1" showdisplay="1"></object>\r\n<!--[if IE]><object id="MediaPlayer" width="<@width@>" height="<@height@>" classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components..." type="application/x-oleobject"><param name="FileName" value="<@mp3file@>" valuetype="ref" ref /><param name="AudioStream" value="1" /><param name="AutoSize" value="0" /><param name="AutoStart" value="<@autostart@>" /><param name="AnimationAtStart" value="0" /><param name="AllowScan" value="-1" /><param name="AllowChangeDisplaySize" value="-1" /><param name="AutoRewind" value="0" /><param name="Balance" value="0" /><param name="BaseURL" value /><param name="BufferingTime" value="5" /><param name="CaptioningID" value /><param name="ClickToPlay" value="-1" /><param name="CursorType" value="0" /><param name="CurrentPosition" value="-1" /><param name="CurrentMarker" value="0" /><param name="DefaultFrame" value /><param name="DisplayBackColor" value="0" /><param name="DisplayForeColor" value="16777215" /><param name="DisplayMode" value="1" /><param name="DisplaySize" value="2" /><param name="Enabled" value="-1" /><param name="EnableContextMenu" value="-1" /><param name="EnablePositionControls" value="-1" /><param name="EnableFullScreenControls" value="-1" /><param name="EnableTracker" value="-1" /><param name="InvokeURLs" value="-1" /><param name="Language" value="-1" /><param name="Mute" value="0" /><param name="PlayCount" value="1" /><param name="PreviewMode" value="0" /><param name="Rate" value="1" /><param name="SAMILang" value /><param name="SAMIStyle" value /><param name="SAMIFileName" value /><param name="SelectionStart" value="-1" /><param name="SelectionEnd" value="-1" /><param name="SendOpenStateChangeEvents" value="-1" /><param name="SendWarningEvents" value="-1" /><param name="SendErrorEvents" value="-1" /><param name="SendKeyboardEvents" value="0" /><param name="SendMouseClickEvents" value="0" /><param name="SendMouseMoveEvents" value="0" /><param name="SendPlayStateChangeEvents" value="-1" /><param name="ShowCaptioning" value="0" /><param name="ShowControls" value="-1" /><param name="ShowAudioControls" value="-1" /><param name="ShowDisplay" value="-1" /><param name="ShowGotoBar" value="0" /><param name="ShowPositionControls" value="0" /><param name="ShowStatusBar" value="-1" /><param name="ShowTracker" value="-1" /><param name="TransparentAtStart" value="0" /><param name="VideoBorderWidth" value="5" /><param name="VideoBorderColor" value="333333" /><param name="VideoBorder3D" value="-1" /><param name="Volume" value="-1" /><param name="WindowlessVideo" value="-1" /></object><![endif]-->', 412, 385, '1', '0', '', '', '', 0, 0, 0, 0);
INSERT INTO debaser_player VALUES (6, 0, 'Quicktime Audio', '<!--[if IE]>\r\n<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="<@width@>" height="<@height@>"  codebase="http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0">\r\n<param name="type" value="<@mp3file@>" />\r\n<param name="autoplay" value="<@autostart@>" />\r\n<param name="target" value="myself" />\r\n<param name="src" value="<@mp3file@>" />\r\n<param name="href" value="<@mp3file@>" />\r\n<param name="ShowControls" value="1" />\r\n<param name="ShowStatusBar" value="1" />\r\n<param name="showdisplay" value="0" /><![endif]-->\r\n<object class="playerdisplay" width="<@width@>" height="<@height@>" data="<@mp3file@>" type="audio/quicktime">\r\n<param name="href" value="<@mp3file@>" />\r\n<param name="target" value="myself" />\r\n<param name="showcontrols" value="1" />\r\n<param name="showdisplay" value="0" />\r\n<param name="showstatusbar" value="1" />\r\n<param name="autoplay" value="<@autostart@>" />\r\n</object>\r\n<!--[if IE]></object><![endif]-->', 30, 385, 'true', 'smil', 'quicktime.gif', 'mp3', '', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (7, 0, 'Quicktime Video', '<!--[if IE]><object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="<@width@>" height="<@height@>">\r\n <param name="src" value="<@mp3file@>" />\r\n <param name="controller" value="true" />\r\n <param name="autoplay" value="<@autostart@>" /><![endif]-->\r\n <object type="video/quicktime" data="<@mp3file@>" width="320" height="<@height@>" class="playerdisplay">\r\n  <param name="controller" value="true" />\r\n  <param name="autoplay" value="<@autostart@>" />\r\n </object>\r\n<!--[if IE]></object><![endif]-->', 412, 385, 'false', '0', 'quicktime.gif', 'avi riff', '', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (8, 0, 'Flashplayer - SWF', '<object class="playerdisplay" height="<@height@>" pluginspage="http://www.macromedia.com/go/getflashplayer" src="<@mp3file@>" type="application/x-shockwave-flash" width="<@width@>" quality="best" play="<@autostart@>" /></object>\r\n<!--[if IE]><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" height="<@height@>" width="<@width@>"><param name="movie" value="<@mp3file@>" /><param name="quality" value="best" /><param name="play" value="<@autostart@>" /></object><![endif]-->', 412, 385, '1', '0', '', '', '', 0, 0, 0, 0);
INSERT INTO debaser_player VALUES (9, 0, 'DIVX-Player', '<object class="playerdisplay" type="video/divx" src="<@mp3file@>" custommode="none" width="<@width@>" height="<@height@>" autoPlay="<@autostart@>"  previewImage=""  pluginspage="http://go.divx.com/plugin/download/"></object>\r\n<!--[if IE]><object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="<@width@>" height="<@height@>" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab"><param name="custommode" value="none" /><param name="previewImage" value="" /><param name="autoPlay" value="<@autostart@>" /><param name="src" value="<@mp3file@>" /></object><![endif]-->', 320, 260, 'false', '0', '', '', '', 0, 0, 0, 0);
INSERT INTO debaser_player VALUES (10, 0, 'JW Player Audio JS', '<div id="jeroenaudio">\r\n<a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.\r\n</div>\r\n<script type="text/javascript" src="<@urltoscript@>swfobject.js"></script>\r\n<script type="text/javascript">\r\nvar s2 = new SWFObject("<@urltoscript@>player.swf","playlist","<@width@>","<@height@>","7");\r\ns2.addParam("allowfullscreen","true");\r\ns2.addParam("allowscriptaccess","always");\r\ns2.addParam("flashvars","file=<@mp3file@>&autostart=<@autostart@>&shuffle=false&repeat=list");\r\n//s2.addVariable("displaywidth","330");\r\n//s2.addVariable("displayheight","330");\r\n//s2.addParam("backcolor","0xffffff");\r\n//s2.addParam("frontcolor","0x666666");\r\n//s2.addParam("lightcolor","0xbd972d");\r\n//s2.addParam("screencolor","0xAFB7AA");\r\n//s2.addParam("autostart","true");\r\n//s2.addParam("shuffle","false");\r\n//s2.addParam("autoscroll","true");\r\n//s2.addParam("repeat","list");\r\n//s2.addVariable("overstretch","fit");\r\n//s2.addVariable("width","328");\r\n//s2.addVariable("height","20");\r\ns2.write("jeroenaudio");\r\n</script>', 20, 328, 'true', 'xml', 'jwflv.gif', 'mp3', 'jeroen', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (11, 0, 'JW Player Audio HTML', '<!--[if IE]>\r\n<object classid="CLSID:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"  width="<@width@>" height="<@height@>">\r\n<param name="src" value="<@urltoscript@>player.swf" />\r\n<param name="allowscriptaccess" value="always" />\r\n<param name="allowfullscreen" value="true" />\r\n<param name="flashvars" value="file=<@mp3file@>&autostart=<@autostart@>&shuffle=false&repeat=list" /><![endif]-->\r\n<object class="playertype" data="<@urltoscript@>player.swf" width="<@width@>" height="<@height@>" type="application/x-shockwave-flash">\r\n<param name="allowscriptaccess" value="always" />\r\n<param name="allowfullscreen" value="true" />\r\n<param name="flashvars" value="file=<@mp3file@>&autostart=<@autostart@>&shuffle=false&repeat=list" />\r\n</object>\r\n<!--[if IE]></object><![endif]-->', 20, 328, 'true', 'xml', 'jwflv.gif', 'mp3', 'jeroen', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (12, 0, 'YouTube', '', 344, 425, '', '0', 'youtube.gif', '', '', 1, 0, 1, 0);
INSERT INTO debaser_player VALUES (13, 0, 'clipfish', '', 380, 464, '', '0', 'clipfish.gif', '', '', 0, 0, 1, 0);
INSERT INTO debaser_player VALUES (14, 0, 'myvideo', '', 406, 470, '', '0', 'myvideo.gif', '', '', 1, 0, 1, 0);
INSERT INTO debaser_player VALUES (15, 0, 'Windows Media Player Audio External', 'external', 0, 0, '', 'wpl', 'wmp.gif', 'mp3', '', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (16, 0, 'JW Player Video HTML', '<!--[if IE]>\r\n<object classid="CLSID:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"  width="<@width@>" height="<@height@>">\r\n<param name="src" value="<@urltoscript@>player.swf" />\r\n<param name="allowscriptaccess" value="always" />\r\n<param name="allowfullscreen" value="true" />\r\n<param name="flashvars" value="file=<@mp3file@>&autostart=<@autostart@>&shuffle=false&repeat=list" /><![endif]-->\r\n<object class="playertype" data="<@urltoscript@>player.swf" width="<@width@>" height="<@height@>" type="application/x-shockwave-flash">\r\n<param name="allowscriptaccess" value="always" />\r\n<param name="allowfullscreen" value="true" />\r\n<param name="flashvars" value="file=<@mp3file@>&autostart=<@autostart@>&shuffle=false&repeat=list" />\r\n</object>\r\n<!--[if IE]></object><![endif]-->', 400, 600, 'true', '0', 'jwflv.gif', 'flv', 'jeroen', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (17, 0, 'HTML5 Audio', '<audio src="<@mp3file@>" width="<@width@>" height="<@height@>" <@autostart@> controls>No HTML5 support</audio>', 32, 300, 'autoplay', '0', 'html5.gif', 'mp3', '', 1, 0, 0, 0);
INSERT INTO debaser_player VALUES (18, 0, 'Flowplayer Video JS', '<script type="text/javascript" src="<@urltoscript@>flowplayer-3.1.4.min.js"></script>\r\n<a href="<@mp3file@>" style="display:block;width:<@width@>px;height:<@height@>px" id="flowplayer<@id@>"></a> \r\n<script type="text/javascript">\r\nflowplayer("flowplayer<@id@>", "<@urltoscript@>flowplayer-3.1.5.swf",  { \r\nclip: { \r\nautoPlay: false, \r\nautoBuffering: true,\r\nscaling: "fit"\r\n}\r\n});\r\n</script>', 280, 500, 'false', '0', 'flowplayer.gif', 'flv mp4', 'flowplayer', 1, 0, 0, 0);

CREATE TABLE debaser_mimetypes (
  mime_id int(11) NOT NULL auto_increment,
  mime_pid int(11) NOT NULL default '0',
  mime_ext varchar(60) NOT NULL default '',
  mime_types text NOT NULL,
  mime_name varchar(255) NOT NULL default '',
  mime_admin int(1) NOT NULL default '1',
  mime_user int(1) NOT NULL default '0',
  mimeinlist int(1) NOT NULL default '0',
  KEY mime_id (mime_id)
) TYPE=MyISAM;

INSERT INTO debaser_mimetypes VALUES (1, 0, 'mp3', 'application/mp3 audio/mpeg audio/mp3', 'MPEG Audio Stream, Layer III', 1, 1, 0);
INSERT INTO debaser_mimetypes VALUES (2, 0, 'mpeg', 'video/mpeg video/mpg', 'MPEG Movie', 1, 0, 0);
INSERT INTO debaser_mimetypes VALUES (3, 0, 'wav', 'audio/wav audio/x-wave', 'Waveform Audio', 1, 0, 0);
INSERT INTO debaser_mimetypes VALUES (4, 0, 'swf', 'application/x-shockwave-flash', 'Macromedia Flash Format File', 1, 0, 0);
INSERT INTO debaser_mimetypes VALUES (5, 0, 'mov', 'video/quicktime', 'Quicktime Video', 1, 0, 0);
INSERT INTO debaser_mimetypes VALUES (6, 0, 'rm', 'application/vnd.rn-realmedia audio/x-realaudio', 'RealMedia Streaming Media', 1, 0, 0);
INSERT INTO debaser_mimetypes VALUES (7, 0, 'avi', 'video/avi video/x-msvideo', 'Audio Video Interleave File', 1, 0, 0);
INSERT INTO debaser_mimetypes VALUES (8, 0, 'wmv', 'video/x-ms-wmv', 'Windows Media File', 1, 0, 0);
INSERT INTO debaser_mimetypes VALUES (9, 0, 'flv', 'video/x-flv', 'Flash Video File', 1, 0, 0);
INSERT INTO debaser_mimetypes VALUES (10, 0, 'riff', 'video/avi', 'Audio Video Interleave File RIFF', 1, 0, 0);
INSERT INTO debaser_mimetypes VALUES (11, 0, 'mp4', 'video/mp4', 'MPEG Layer 4', 1, 0, 0);