<?php
$library = simplexml_load_file('http://xoccupyoaklandx.api.channel.livestream.com/2.0/lslibrary');
$namespaces = $library->getNameSpaces(TRUE);
$ls = $library->channel->children($namespaces['ls']);
$directory = $ls->directory->children();
foreach ($directory->item as $item) {
  $item = $item->children($namespaces['ls']);
  $items[] = $item;
}
foreach ($ls->directory->directory as $directory) {
  $directory = $directory->children();
  foreach ($directory->item as $item) {
    $item = $item->children($namespaces['ls']);
    $items[] = $item;
  }
}
foreach ($items as $item) {
  preg_match('!^http://www.livestream.com/filestore/user-files/choccupyoakland/([0-9]{4}/[0-9]{2}/[0-9]{2}/[a-z0-9-]+)_[0-9]_low.jpg$!', $item->thumbnailLow, $matches);
  if (isset($matches[1])) {
    $url = 'http://clipdownload.livestream.com/mogulus-user-files/choccupyoakland/' . $matches[1] . '.mp4';
    passthru('wget -c ' . escapeshellarg($url));
  }
  else {
    trigger_error("MISSING THUMBNAIL - CANNOT GENERATE DOWNLOAD URL");
  }
}
