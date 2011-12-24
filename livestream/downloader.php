<?php
$library = simplexml_load_file('http://xoccupyoaklandx.api.channel.livestream.com/2.0/lslibrary');
$namespaces = $library->getNameSpaces(TRUE);
foreach ($library->channel->children($namespaces['ls'])->directory as $directory) {
  foreach ($directory->children()->item as $item) {
    $items[] = $item->children($namespaces['ls']);
  }
  foreach ($directory->directory as $directory) {
    foreach ($directory->children()->item as $item) {
      $items[] = $item->children($namespaces['ls']);
    }
  }
}
foreach ($items as $item) {
  preg_match('!^http://www.livestream.com/filestore/user-files/choccupyoakland/([0-9]{4}/[0-9]{2}/[0-9]{2}/[a-z0-9-]+)_[0-9]_low.jpg$!', $item->thumbnailLow, $matches);
  if (isset($matches[1])) {
    $url = 'http://clipdownload.livestream.com/mogulus-user-files/choccupyoakland/' . $matches[1];
    passthru('wget -c ' . escapeshellarg($url . '.mp4'));
    passthru('wget -c ' . escapeshellarg($url . '.flv'));
  }
  else {
    trigger_error("MISSING THUMBNAIL - CANNOT GENERATE DOWNLOAD URL");
  }
}
