<?php
// This is the handler for captcha image requests
// The captcha ID is placed in the session, so session vars are required for this plug-in

include_once 'header.php';

// -------------------- EDIT THESE ----------------- //
$images = array(
  _MD_DEBASER_CAPT_HOUSE => 'images/captchaImages/01.png',
  _MD_DEBASER_CAPT_KEY => 'images/captchaImages/04.png',
  _MD_DEBASER_CAPT_FLAG => 'images/captchaImages/06.png',
  _MD_DEBASER_CAPT_CLOCK => 'images/captchaImages/15.png',
  _MD_DEBASER_CAPT_BUG => 'images/captchaImages/16.png',
  _MD_DEBASER_CAPT_PEN => 'images/captchaImages/19.png',
  _MD_DEBASER_CAPT_LIGHTBULB => 'images/captchaImages/21.png',
  _MD_DEBASER_CAPT_NOTE => 'images/captchaImages/40.png',
  _MD_DEBASER_CAPT_HEART => 'images/captchaImages/43.png',
  _MD_DEBASER_CAPT_WORLD => 'images/captchaImages/99.png'
);
// ------------------- STOP EDITING ---------------- //


$_SESSION['simpleCaptchaAnswer'] = null;
$_SESSION['simpleCaptchaTimestamp'] = time();
$SALT = "o^Gj".$_SESSION['simpleCaptchaTimestamp']."7%8W";

header("Content-Type: application/json");

echo '{ ';

if (!isset($images) || !is_array($images) || sizeof($images) < 3) {
  echo 'error: "There aren\'t enough images!" }';
  exit;
}

if (isset($_POST['numImages']) && strlen($_POST['numImages']) > 0) {
  $numImages = intval($_POST['numImages']);
} else if (isset($_GET['numImages']) && strlen($_GET['numImages']) > 0) {
  $numImages = intval($_GET['numImages']);
}
$numImages = ($numImages > 0)?$numImages:5;
$size = sizeof($images);
$num = min(array($size, $numImages));

$keys = array_keys($images);
$used = array();
mt_srand(((float) microtime() * 587) / 33);
for ($i=0; $i<$num; ++$i) {
  $r = rand(0, $size-1);
  while (array_search($keys[$r], $used) !== false) {
    $r = rand(0, $size-1);
  }
  array_push($used, $keys[$r]);
}
$selectText = $used[rand(0, $num-1)];
$_SESSION['simpleCaptchaAnswer'] = sha1($selectText . $SALT);

echo 'text: "'.$selectText.'", images: [ ';

shuffle($used);
for ($i=0; $i<sizeof($used); ++$i) {
  if ($i > 0) { echo ', '; }
  echo '{ hash: "'.sha1($used[$i] . $SALT).'", file: "'.$images[$used[$i]].'" }';
}
echo ' ] }';
?>