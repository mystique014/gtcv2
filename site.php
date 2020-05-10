<?php
#########################################################################
#                            site.php                                  #
#                                                                       #
#            		Interface de connexion                             #
#               Dernière modification : 30/12/2016                    #
#                                                                       #
#                                                                       #
#########################################################################
/*
 */

?>
    <head>  
           <title>Portail - GTC</title>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <style>  
           ul{  
                background-color:#eee;  
                cursor:pointer;  
           }  
           li{  
                padding:12px;  
           }  
           </style>  
      </head>
	  <body>
           <br /><br /> 
		<div align="center">
			<IMG SRC="img_grr/logo.jpg" ALT="Logo" TITLE="Logo du club"><br><br>
		</div>
		<form action="login.php" method='POST'>		   
           <div class="container" style="width:300px;">  
                <h3 align="center">PORTAIL - GTC</h3><br />  
                <label>Nom de votre club</label>  
                <input type="text" name="table_prefix" id="table_prefix" class="form-control" placeholder="Saisissez le nom de votre club" />  
                <div id="sitelist"></div> 
				<input type="submit" name="submit" value="Validez" style="font-variant: small-caps;">
           </div>  
		</form>
		<div class="container" style="width:300px;">
			<label>Informations:</label>
			<table style="width: 300px; border: 0;" cellpadding="5" cellspacing="0">
			<tr>
			<td style="text-align: left; width:300px ">
			<blink style="color:#900"><?php echo "Placez la page du portail dans vos marques pages"; ?></blink></td>
			</tr>
			</table>
		</div>
      </body>  
 </html>  
 <script>  
 $(document).ready(function(){  
      $('#table_prefix').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"search_site.php",  
                     method:"POST",  
                     data:{query:query},  
                     success:function(data)  
                     {  
                          $('#sitelist').fadeIn();  
                          $('#sitelist').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           $('#table_prefix').val($(this).text());  
           $('#sitelist').fadeOut();  
      });  
 });  
 </script>  

