<?php 
include "include/connect.inc.php";
// Paramètres langage
include "include/language.inc.php";
$connect= new mysqli($dbHost, $dbUser, $dbPass, $dbDb);


/* Vérification de la connexion */
if (mysqli_connect_errno()) {
    printf("Échec de la connexion : %s\n", mysqli_connect_error());
    exit();
} 
  
 if(isset($_POST["query"]))  
 {  
      $output = '';  
      $query = "SELECT * FROM sites WHERE name LIKE '%".$_POST["query"]."%'";  
      $result = mysqli_query($connect, $query);  
      $output = '<ul class="list-unstyled">';  
      if(mysqli_num_rows($result) > 0)  
      {  
           while($row = mysqli_fetch_array($result))  
           {  
                //$output .= '<li>'.$row["description"].'</li>';  
				$output .= '<li>'.$row["name"].'</li>'; 
           }  
      }  
      else  
      {  
           $output .= '<li>Club non trouvé</li>';  
      }  
      $output .= '</ul>';  
      echo $output;  
 }  
 ?>  