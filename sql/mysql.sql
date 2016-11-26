CREATE TABLE debaser_files (
  xfid      INT(11)          NOT NULL AUTO_INCREMENT,
  filename  TEXT             NOT NULL,
  added     INT(10)          NOT NULL DEFAULT 0,
  title     VARCHAR(50)      NOT NULL DEFAULT '',
  artist    VARCHAR(50)      NOT NULL DEFAULT '',
  album     VARCHAR(50)      NOT NULL DEFAULT '',
  year      INT(4)           NOT NULL DEFAULT 0,
  addinfo   TEXT             NOT NULL ,
  track     INT(3)           NOT NULL DEFAULT 0,
  genre     VARCHAR(50)      NOT NULL DEFAULT '',
  length    VARCHAR(50)      NOT NULL DEFAULT '',
  bitrate   VARCHAR(50)      NOT NULL DEFAULT '',
  link      VARCHAR(100)     NOT NULL DEFAULT '',
  frequence VARCHAR(50)      NOT NULL DEFAULT '',
  rating    DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
  votes     INT(11)          NOT NULL DEFAULT '0',
  approved  INT(1)           NOT NULL DEFAULT '0',
  fileext   VARCHAR(4)       NOT NULL DEFAULT '',
  weight    INT(10)          NOT NULL DEFAULT '0',
  hits      INT(11) UNSIGNED NOT NULL DEFAULT '0',
  views     INT(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (xfid)
)
  ENGINE = MyISAM;

CREATE TABLE debaser_player (
  xpid      INT(11)      NOT NULL AUTO_INCREMENT,
  name      VARCHAR(128) NOT NULL DEFAULT '',
  html_code MEDIUMTEXT   NOT NULL,
  height    INT(4)       NOT NULL DEFAULT 0,
  width     INT(4)       NOT NULL DEFAULT 0,
  autostart INT(1)       NOT NULL DEFAULT '1',
  PRIMARY KEY (xpid)
)
  ENGINE = MyISAM;

CREATE TABLE debaser_user (
  dbusid    INT(11) NOT NULL AUTO_INCREMENT,
  uid       INT(11) NOT NULL DEFAULT '0',
  useplayer TEXT    NOT NULL,
  PRIMARY KEY (dbusid)
)
  ENGINE = MyISAM;

CREATE TABLE debaser_genre (
  genreid    INT(11)      NOT NULL AUTO_INCREMENT,
  subgenreid INT(11)      NOT NULL DEFAULT '0',
  genretitle VARCHAR(128) NOT NULL,
  imgurl     VARCHAR(150) NOT NULL DEFAULT '',
  catweight  INT(4)       NOT NULL DEFAULT '0',
  PRIMARY KEY (genreid)
)
  ENGINE = MyISAM;

CREATE TABLE debaservotedata (
  ratingid        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  lid             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  ratinguser      INT(11)             NOT NULL DEFAULT '0',
  rating          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  ratinghostname  VARCHAR(60)         NOT NULL DEFAULT '',
  ratingtimestamp INT(10)             NOT NULL DEFAULT '0',
  PRIMARY KEY (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname),
  KEY lid (lid)
)
  ENGINE = MyISAM;

INSERT INTO debaser_player VALUES (1, 'Windows Media Player Audio',
                                   '<object id="MediaPlayer1" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab# Version=5,1,52,701" standby="Loading Microsoft Windows� Media Player components..." type="application/x-oleobject" width="<@width@>" height="<@height@>">\r\n<param name="fileName" value="<@mp3file@>" />\r\n<param name="animationatStart" value="true" />\r\n<param name="transparentatStart" value="true" />\r\n<param name="autoStart" value="<@autostart@>" />\r\n<param name="showControls" value="true" />\r\n<param name="Volume" value="-300" />\r\n<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" src="<@mp3file@>" name="MediaPlayer1" width="<@width@>" height="<@height@>" autostart="<@autostart@>" showcontrols="1" volume="-300"></embed>\r\n</object>',
                                   '46', '280', '1');
INSERT INTO debaser_player VALUES (2, 'Flash Player - Audio',
                                   '<object id="flash" classid="CLSID:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"  width="<@width@>" height="<@height@>">\r\n <param name="movie" value="onlamp.swf?mp3file=<@mp3file@>" />\r\n <param name="quality" value="high" />\r\n <param name="bgcolor" value="#708090" />\r\n <embed src="onlamp.swf?mp3file=<@mp3file@>" quality="high"  width="<@width@>" height="<@height@>" bgcolor="#708090" name="onlamp" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">\r\n </embed>\r\n</object>',
                                   '50', '400', '1');
INSERT INTO debaser_player VALUES (3, 'Real Player Audio',
                                   '<object \r\n  id="RVOCX" \r\n  classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" \r\n  width="<@width@>" \r\n  height="<@height@>">\r\n <param name="src" value="<@mp3file@>" />\r\n <param name="autostart" value="<@autostart@>" />\r\n <param name="controls" value="all" />\r\n <param name="console" value="audio" />\r\n <embed \r\n  type="audio/x-pn-realaudio-plugin" \r\n  src="<@mp3file@>"\r\n  width="<@width@>"\r\n  height="<@height@>"\r\n  autostart="<@autostart@>" \r\n  controls="all"\r\n  console="audio">\r\n </embed>\r\n</object>',
                                   '120', '320', '1');
INSERT INTO debaser_player VALUES (4, 'Real Player Video',
                                   '<object \r\n  id="RVOCX" \r\n  classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" \r\n  width="<@width@>" \r\n  height="<@height@>">\r\n <param name="src" value="<@mp3file@>" />\r\n <param name="autostart" value="<@autostart@>" />\r\n <param name="controls" value="imagewindow,all" />\r\n <param name="console" value="video" />\r\n <embed \r\n  type="audio/x-pn-realaudio-plugin" \r\n  src="<@mp3file@>"\r\n  width="<@width@>"\r\n  height="<@height@>"\r\n  autostart="<@autostart@>" \r\n  controls="imagewindow,all"\r\n  console="video">\r\n </embed>\r\n</object>',
                                   '240', '320', '1');
INSERT INTO debaser_player VALUES (5, 'Windows Media Player Video',
                                   '<object id="MediaPlayer" width="<@width@>" height="<@height@>" classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Windows Media Player components..." type="application/x-oleobject">\r\n<param name="FileName" value="<@mp3file@>" valuetype="ref" ref />\r\n<param name="AudioStream" value="1" />\r\n<param name="AutoSize" value="0" />\r\n<param name="AutoStart" value="<@autostart@>" />\r\n<param name="AnimationAtStart" value="0" />\r\n<param name="AllowScan" value="-1" />\r\n<param name="AllowChangeDisplaySize" value="-1" />\r\n<param name="AutoRewind" value="0" />\r\n<param name="Balance" value="0" />\r\n<param name="BaseURL" value />\r\n<param name="BufferingTime" value="5" />\r\n<param name="CaptioningID" value />\r\n<param name="ClickToPlay" value="-1" />\r\n<param name="CursorType" value="0" />\r\n<param name="CurrentPosition" value="-1" />\r\n<param name="CurrentMarker" value="0" />\r\n<param name="DefaultFrame" value />\r\n<param name="DisplayBackColor" value="0" />\r\n<param name="DisplayForeColor" value="16777215" />\r\n<param name="DisplayMode" value="1" />\r\n<param name="DisplaySize" value="2" />\r\n<param name="Enabled" value="-1" />\r\n<param name="EnableContextMenu" value="-1" />\r\n<param name="EnablePositionControls" value="-1" />\r\n<param name="EnableFullScreenControls" value="-1" />\r\n<param name="EnableTracker" value="-1" />\r\n<param name="InvokeURLs" value="-1" />\r\n<param name="Language" value="-1" />\r\n<param name="Mute" value="0" />\r\n<param name="PlayCount" value="1" />\r\n<param name="PreviewMode" value="0" />\r\n<param name="Rate" value="1" />\r\n<param name="SAMILang" value />\r\n<param name="SAMIStyle" value />\r\n<param name="SAMIFileName" value />\r\n<param name="SelectionStart" value="-1" />\r\n<param name="SelectionEnd" value="-1" />\r\n<param name="SendOpenStateChangeEvents" value="-1" />\r\n<param name="SendWarningEvents" value="-1" />\r\n<param name="SendErrorEvents" value="-1" />\r\n<param name="SendKeyboardEvents" value="0" />\r\n<param name="SendMouseClickEvents" value="0" />\r\n<param name="SendMouseMoveEvents" value="0" />\r\n<param name="SendPlayStateChangeEvents" value="-1" />\r\n<param name="ShowCaptioning" value="0" />\r\n<param name="ShowControls" value="-1" />\r\n<param name="ShowAudioControls" value="-1" />\r\n<param name="ShowDisplay" value="-1" />\r\n<param name="ShowGotoBar" value="0" />\r\n<param name="ShowPositionControls" value="0" />\r\n<param name="ShowStatusBar" value="-1" />\r\n<param name="ShowTracker" value="-1" />\r\n<param name="TransparentAtStart" value="0" />\r\n<param name="VideoBorderWidth" value="5" />\r\n<param name="VideoBorderColor" value="333333" />\r\n<param name="VideoBorder3D" value="-1" />\r\n<param name="Volume" value="-1" />\r\n<param name="WindowlessVideo" value="-1" />\r\n<embed type="application/x-mplayer2" pluginspage = "http://www.microsoft.com/windows/mediaplayer/" width="<@width@>" height="<@height@>" src="<@mp3file@>" name="player" autostart="<@autostart@>" showcontrols="1" showstatusbar="1" showdisplay="1">\r\n</embed>\r\n</object>',
                                   '412', '385', '1');
INSERT INTO debaser_player VALUES (6, 'Quicktime Audio',
                                   '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="<@width@>" height="<@height@>"  codebase="http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0">\r\n	<param name="type" value="<@mp3file@>" />\r\n	<param name="autoplay" value="<@autostart@>" />\r\n	<param name="target" value="myself" />\r\n	<param name="src" value="<@mp3file@>" />\r\n	<param name="href" value="<@mp3file@>" />\r\n	<param name="pluginspage" value="http://www.apple.com/quicktime/download/indext.html" />\r\n	<param name="ShowControls" value="1" />\r\n	<param name="ShowStatusBar" value="1" />\r\n	<param name="showdisplay" value="0" />\r\n	<embed width="<@width@>" height="<@height@>" src="<@mp3file@>" href="<@mp3file@>" type="video/quicktime" target="myself" border="0" showcontrols="1" showdisplay="0" showstatusbar="1" autoplay="<@autostart@>" pluginspage="http://www.apple.com/quicktime/download/indext.html">\r\n	</embed>\r\n</object>',
                                   '30', '385', '1');
INSERT INTO debaser_player VALUES (7, 'Quicktime Video',
                                   '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="<@width@>" height="<@height@>"  codebase="http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0">\r\n	<param name="type" value="<@mp3file@>" />\r\n	<param name="autoplay" value="<@autostart@>" />\r\n	<param name="target" value="myself" />\r\n	<param name="src" value="<@mp3file@>" />\r\n	<param name="href" value="<@mp3file@>" />\r\n	<param name="pluginspage" value="http://www.apple.com/quicktime/download/indext.html" />\r\n	<param name="ShowControls" value="1" />\r\n	<param name="ShowStatusBar" value="1" />\r\n	<param name="showdisplay" value="1" />\r\n	<embed width="<@width@>" height="<@height@>" src="<@mp3file@>" href="<@mp3file@>" type="video/quicktime" target="myself" border="0" showcontrols="1" showdisplay="1" showstatusbar="1" autoplay="<@autostart@>" pluginspage="http://www.apple.com/quicktime/download/indext.html">\r\n	</embed>\r\n</object>',
                                   '412', '385', '1');
INSERT INTO debaser_player VALUES (8, 'Flashplayer - SWF',
                                   '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" height="<@height@>" width="<@width@>">\r\n<param name="movie" value="<@mp3file@>" />\r\n<param name="quality" value="best" />\r\n<param name="play" value="<@autostart@>" />\r\n<embed height="<@height@>" pluginspage="http://www.macromedia.com/go/getflashplayer" src="<@mp3file@>" type="application/x-shockwave-flash" width="<@width@>" quality="best" play="<@autostart@>" />\r\n</object>',
                                   '412', '385', '1');

INSERT INTO `debaser_genre` VALUES (1, 0, 'Other', '', '0');

CREATE TABLE debaserradio (
  radio_id      INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  radio_name    VARCHAR(20)      NOT NULL DEFAULT '',
  radio_stream  VARCHAR(255)     NOT NULL DEFAULT '',
  radio_url     VARCHAR(50)      NOT NULL DEFAULT '',
  radio_picture VARCHAR(50)      NOT NULL DEFAULT '',
  PRIMARY KEY (radio_id)
)
  ENGINE = MyISAM;

INSERT INTO debaserradio VALUES ('0', 'Yorin FM', 'http://media.rtl.nl/yorinfm/on_air/yorin.asx', 'http://www.yorin.nl', 'yorin.gif');
INSERT INTO debaserradio VALUES ('0', 'Radio 3FM', 'http://www.omroep.nl/radio3/live20.asx', 'http://www.omroep.nl/radio3/', '');
INSERT INTO debaserradio VALUES ('0', 'Sky radio', 'http://www.skyradio.nl/player/skyradio.asx', 'http://www.skyradio.nl', '');
INSERT INTO debaserradio VALUES ('0', 'Hotradio', 'http://www.hotradio.nl/sound/stream/livestream.asx', 'http://www.hotradio.nl', '');
INSERT INTO debaserradio VALUES ('0', 'Noordzee FM', 'mms://hollywood.win2k.vuurwerk.nl/noordzee', 'http://www.noordzeefm.nl', '');
INSERT INTO debaserradio VALUES ('0', 'Radio NRG', 'http://www.radionrg.com/listenlive.asx', 'http://www.radionrg.com', '');
INSERT INTO debaserradio VALUES ('0', 'Baja radio', 'http://www.bajaradio.com/vuurwerk.asx', 'http://www.bajaradio.com', '');
INSERT INTO debaserradio VALUES ('0', 'Capital FM', 'http://www.radio-now.co.uk/l/capitalfmlo.asx', 'http://www.capitalfm.com', 'capital.gif');
INSERT INTO debaserradio VALUES ('0', 'Virgin Radio', 'http://www.smgradio.com/core/audio/wmp/live.asx?service=vr', 'http://www.virginradio.co.uk', '');
INSERT INTO debaserradio VALUES ('0', 'Flash FM', 'http://www.flashfm.com/live/buildasx.asp?service=flashfmuk22', 'http://www.flashfm.com/', '');
INSERT INTO debaserradio VALUES ('0', 'BBC Radio 1', 'http://www.bbc.co.uk/radio1/realaudio/media/r1live.rpm', 'http://www.bbc.co.uk/radio1/', '');

CREATE TABLE debaser_mimetypes (
  mime_id        INT(11)      NOT NULL AUTO_INCREMENT,
  mime_ext       VARCHAR(60)  NOT NULL DEFAULT '',
  mime_types     TEXT         NOT NULL,
  mime_name      VARCHAR(255) NOT NULL DEFAULT '',
  mime_admin     INT(1)       NOT NULL DEFAULT '1',
  mime_user      INT(1)       NOT NULL DEFAULT '0',
  mime_preselect INT(3)       NOT NULL DEFAULT '0',
  KEY mime_id (mime_id)
)
  ENGINE = MyISAM;

INSERT INTO debaser_mimetypes VALUES (1, 'mp3', 'application/mp3 audio/mpeg audio/mp3', 'MPEG Audio Stream, Layer III', 1, 1, 3);
INSERT INTO debaser_mimetypes VALUES (2, 'mpeg', 'video/mpeg video/mpg', 'MPEG Movie', 1, 0, 4);
INSERT INTO debaser_mimetypes VALUES (3, 'wav', 'audio/wav audio/x-wave', 'Waveform Audio', 1, 0, 1);
INSERT INTO debaser_mimetypes VALUES (4, 'swf', 'application/x-shockwave-flash', 'Macromedia Flash Format File', 1, 0, 8);
INSERT INTO debaser_mimetypes VALUES (5, 'mov', 'video/quicktime', 'Quicktime Video', 1, 0, 7);
INSERT INTO debaser_mimetypes VALUES (6, 'rm', 'application/vnd.rn-realmedia audio/x-realaudio', 'RealMedia Streaming Media', 1, 0, 4);
INSERT INTO debaser_mimetypes VALUES (7, 'avi', 'video/avi video/x-msvideo', 'Audio Video Interleave File', 1, 0, 7);
INSERT INTO debaser_mimetypes VALUES (8, 'wmv', 'video/x-ms-wmv', 'Windows Media File', 1, 0, 5);
