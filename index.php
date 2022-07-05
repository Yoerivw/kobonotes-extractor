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

   $results[$row['BookmarkID']] = [ 'title'=> $row['BookTitle'], 'chapter'=>$row['Chapter'],'notes'=>$row['Text'],'annotations'=>$row['Annotation']];

} 

//What if I make one foreach to the bookmark id as the key for the array containing all the data
// and another foreach to get the different titles
//and another for different chapters
//then add in the data from each individual notes where title = title and chapter = chapter 

$newArray = array();
foreach($results as $bookmarkID => $booknotedata){
   /* var_dump($booknotedata); */
   /* $key = array_search($booknotedata['title'], $titles); */

   $title = $booknotedata['title'];
   $chapter = $booknotedata['chapter'];
   $note = $booknotedata['notes'];
   $annotations = $booknotedata['annotations'];


   //if the title does not exist yet then create the title which now holds an array to store the next level of data
   if(!array_key_exists($title,$newArray)){
      $newArray[$title] = array();
      
   }
   
   //if the title exists and the chapter is not created yet, create that chapter first
   if(array_key_exists($title,$newArray)){
      if(!array_key_exists($chapter,$newArray[$title])){
         $newArray[$title][$chapter] = array();
      }
      //create an array for the text and annotion to be inserted with bookmarkID as the key
      $newArray[$title][$chapter][$bookmarkID] = array();  
   }

   //if the key is found insert the values in the chapter it belongs in corresponding to the bookmarkID
   if(array_key_exists($bookmarkID,$newArray[$title][$chapter])){
      $newArray[$title][$chapter][$bookmarkID]['note'] = $note;
      $newArray[$title][$chapter][$bookmarkID]['annotation'] = $annotations;  
   } 
} 



//I'm going to try get out all of the values and print them to the screen using another nested for if loop

foreach($newArray as $title => $chapters){
   echo '<h1>' . $title.'</h1>';
   foreach($chapters as $idx => $notes){
      echo '<p>Chapter: '. $idx.'</p>';

      foreach($notes as $note){
          echo '<figure><blockquote>Note: '.$note['note'].'</br>Highlight: '.$note['annotation'].'</blockquote></figure>'; 
         
      }
   }
}


echo "Operation done successfully\n";

$db->close();
?>
