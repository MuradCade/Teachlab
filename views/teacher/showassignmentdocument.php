<?php
include('../../model/env.php');
if(isset($_GET['docname'])){
    $filename = base64_decode($_GET['docname']);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeachLab - Document Displayed</title>
    <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
   
</head>
<body>
    <!-- displaying  -->
<?php if($extension == 'docx' || $extension == 'doc'){?>
    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=https://localhost:4000/views/teacher/uploads/<?php echo $filename;?>" width='100%' height='650px' frameborder='0'></iframe>
<?php }else if($extension == 'ppt'){ ?>
    <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=https://localhost:4000/views/teacher/uploads/<?php echo $filename;?>" width='100%' height='650px' frameborder='0'></iframe>

    <?php } ?>
</body>
</html>


<?php } else{?>

    <style>
        
        /*======================
            404 page
        =======================*/
        
        body{
            text-align: center;
        }
        .page_404{ padding:40px 0; background:#fff; font-family: 'Arvo', serif;
        }
        
        .page_404  img{ width:100%;}
        
        .four_zero_four_bg{
         
         background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
            height: 400px;
            background-position: center;
         }
         
         
         .four_zero_four_bg h1{
         font-size:80px;
         }
         
          .four_zero_four_bg h3{
                     font-size:80px;
                     }
                     
                     .link_404{			 
            color: #fff!important;
            padding: 10px 20px;
            background: #39ac31;
            margin: 20px 0;
            display: inline-block;}
            .contant_box_404{ margin-top:-50px;}
            </style>
                <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">
        
            <section class="page_404">
            <div class="container">
                <div class="row">	
                <div class="col-sm-12 ">
                <div class="col-sm-10 col-sm-offset-1  text-center">
                <div class="four_zero_four_bg">
                    <h1 class="text-center ">404</h1>
                
                
                </div>
                
                <div class="contant_box_404">
                <h3 class="h2">
                Look like you're lost
                </h3>
                
                <p>the page you are looking for not avaible!</p>
                
                <a href="../checkroles.php" class="link_404">Go to Home</a>
            </div>
                </div>
                </div>
                </div>
            </div>
        </section>
        
      

<?php } ?>    