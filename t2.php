$xml = new SimpleXMLElement($xmlString);
echo $xml->bbb->cccc->dddd['Id'];
echo $xml->bbb->cccc->eeee['name'];
// or...........
foreach ($xml->bbb->cccc as $element) {
  foreach($element as $key => $val) {
   echo "{$key}: {$val}";
  }
}