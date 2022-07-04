<?php
   class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('KoboReader.sqlite');
      }
   }

   $db = new MyDB();
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

$ret = $db->query($sql);




 while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
   $results[] = array('title'=>$row['BookTitle'],
   array('chapter'=>$row['Chapter'], array('bookmarkID'=>$row['BookmarkID'],array('notes'=>$row['Text'],'annotations'=>$row['Annotation'])))); 
   
   



   /* echo $row['BookTitle'] . "\n</br>";
   echo $row['Chapter'] ."\n\n</br>";
   echo $row['Text'] ."\n</br>";
   echo $row['Annotation'] ."\n</br>";
   echo "</br></br>"; */
} 
var_dump($results);
echo "Operation done successfully\n";
$db->close();
?>
