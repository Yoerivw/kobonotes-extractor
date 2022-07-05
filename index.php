<?php

   class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('KoboReader.sqlite');
      }
   } 
 


   $db = new MyDB('KoboReader.sqlite');

   if(!$db){
      echo $db->lastErrorMsg();
   } else {
      echo "Opened the KoboReader database successfully\n </br>";
   }
   $sql =<<<EOF
   select BookmarkID,VolumeID, bm.ContentID as bmContentID, ct.ContentID as ctContentID, BookTitle, Title as Chapter, Text, Annotation, ChapterProgress, StartContainerPath
   from Bookmark bm 
   inner join content ct 
   on bm.ContentID = ct.ContentID
   group by BookmarkID
   order by VolumeID, ChapterProgress, StartContainerPath;
EOF;

$res = $db->query($sql);




 while($row = $res->fetchArray(SQLITE3_ASSOC) ) {

   $results[] = [ 'title'=> $row['BookTitle'], 'chapter'=>$row['Chapter'],'bookmarkID'=>$row['BookmarkID'],'notes'=>$row['Text'],'annotations'=>$row['Annotation']];

   
} 
var_dump($results);
$newresults = array();

foreach($results as $array => $booknotes){
      


   foreach($booknotes as $books => $bookcontent){
      /* echo "array number: ". $array . ' contains ' . $books . ' with ' . $bookcontent. '</br>'; */
     /* echo $booknotes['title'].' </br>';
     echo $booknotes['chapter'].' </br>'; */
     
      if(!in_array($booknotes['title'],$newresults)){
         $newresults['title'] =  $bookcontent;
           /* echo "array number: ". $array . ' contains ' . $books . ' with ' . $bookcontent. '</br>';  */
      } 
      
      
      
   }
};
 //var_dump($newresults); 

echo "Operation done successfully\n";

$db->close();
?>
