<?
class Util
{

	function MultiHTMLParser($data, $options = array(
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_TIMEOUT => 5,
          CURLOPT_FOLLOWLOCATION => TRUE,
          CURLOPT_AUTOREFERER => TRUE,
          CURLOPT_BINARYTRANSFER => TRUE,
          CURLOPT_MAXREDIRS => 5,
          CURLOPT_USERAGENT => "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)",
         )) {

 // array of curl handles
 $curly = array();
 // data to be returned
 $result = array();

 // multi handle
 $mh = curl_multi_init();

 // loop through $data and create curl handles
 // then add them to the multi-handle
 foreach ($data as $id => $d) {

  $curly[$id] = curl_init();

  $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;

  curl_setopt ($curly[$id], CURLOPT_URL, $url);
  curl_setopt ($curly[$id], CURLOPT_HEADER, 0);
  curl_setopt ($curly[$id], CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)");
  if(substr_count("https://",$url)) curl_setopt ($curly[$id], CURLPROTO_HTTPS , 1);

  // post?
  if (is_array($d)) {
   if (!empty($d['post'])) {
    curl_setopt($curly[$id], CURLOPT_POST,       1);
    curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
   }
  }

  // extra options?
  if (!empty($options)) {
   curl_setopt_array($curly[$id], $options);
  }

  curl_multi_add_handle($mh, $curly[$id]);
 }

 // execute the handles
 $running = null;
 do {
  $mrc = curl_multi_exec($mh, $running);
 /// echo $mrc."<BR>"; flush();
 } while ($mrc == CURLM_CALL_MULTI_PERFORM);

 while ($running && $mrc == CURLM_OK) {
  if (curl_multi_select($mh) != -1) {
   do {
    $mrc = curl_multi_exec($mh, $running);
   } while ($mrc == CURLM_CALL_MULTI_PERFORM);
  }
 // echo curl_multi_select($mh)."<BR>";
 }

 // get content and remove handles
 foreach($curly as $id => $c) {
	
	$D = curl_multi_getcontent($c);
	$D = iconv("EUC-KR","UTF-8",$D);
  $result[$id] = array('content'=>$D);
  $result[$id]['header'] = curl_getinfo($c);
  curl_multi_remove_handle($mh, $c);
 }
 // print_r($result);
 // all done
 curl_multi_close($mh);
 unset($mh);
 return $result;
}














}




?>