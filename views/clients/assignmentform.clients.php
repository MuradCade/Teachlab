<?php 
include('./../../model/dbcon.php');


if(isset($_GET['formid'])){
    $formid = base64_decode($_GET['formid']);


    $sql = "select * from assignmentform  where formid = '$formid'";
    $result = mysqli_query($connection,$sql);
    if(mysqli_num_rows($result) == 0){
        header('location:../login.php');
        exit();
    }
    
    while($row= mysqli_fetch_assoc($result)){

        if($row['formstatus'] == 'active'){
                    
    ?>
    


    

    <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">
    <title>Teachlab - Assignment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body style="background-color:#f5f5f5;overflow-x: hidden;">
    <div class="row">
        <div class="col-md-9 ms-sm-auto col-lg-8 mx-auto mt-2">
        <div class="card p-4">
            <h4 class="card-title">
                <?php echo $row['title']?>
            </h4>
            <p>
            <?php echo $row['description']?>
            </p>
        </div>
        <div class="card p-4 mt-2">
            <?php if(isset($_GET['emptyidfield'])){?>
                <p class='bg-danger p-2 text-white fw-bold'>Empty Student ID Field</p>
            <?php  } ?>
            <?php if(isset($_GET['emptynamefield'])){?>
                <p class='bg-danger p-2 text-white fw-bold'>Empty Student Name Field</p>
            <?php  } ?>
            <?php if(isset($_GET['emptyfilefield'])){?>
                <p class='bg-danger p-2 text-white fw-bold'>Empty Upload Assignment File Field</p>
            <?php  } ?>
            <?php if(isset($_GET['filenotsupported'])){?>
                <p class='bg-danger p-2 text-white fw-bold'>Sorry Filed  Uploaded Not Supported</p>
            <?php  } ?>
            <?php if(isset($_GET['idfound'])){?>
                <p class='bg-danger p-2 text-white fw-bold'>Sorry , student with this id already submitted the assignment</p>
            <?php  } ?>
            <form method='post' action="addassignmentformdata.php?formid=<?php echo $formid?>&filetype=<?php echo $row['uploadedfilename']?>&coursename=<?php echo $row['coursename']; ?>&marks=<?php echo $row['marks']?>" enctype="multipart/form-data">
        <div class="card-body">
            <div class="form-group mb-4">
                <label class='form-label'>Student ID</label>
                <input type='text' class='form-control' name='stdid' placeholder="Enter Student ID"/>
            </div>
            <div class="form-group mb-4">
                <label class='form-label'>Student Name</label>
                <input type='text'  class='form-control' name='stdname' placeholder="Enter Student Name"/>
            </div>
            <div class="form-group mb-4">
                <label class='form-label'>Upload Assingment File</label>
                <input  type='file' class='form-control' name='fileuploaded'/>
                <span style='font-size:14px;'> (only upload  <?php echo $row['uploadedfilename'];?> files)</span>
            </div>
            <button class='btn btn-primary  fw-bold' name='submit'>Submit</button>
    </form>
        </div>
    </div>
        </div>
    </div>
        <div class="text-center">
        <p class='mt-2'>This Form Is Powered By <a href="https://teachlabs.unaux.com/" class='fw-bold'>TeachLab</a></p>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

<?php }else if($row['formstatus'] == 'disable'){?>

    <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">
    <title>Teachlab - Assignment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body style="background-color:#f5f5f5;overflow-x: hidden;">
    <div class="row">
        <div class="col-md-9 ms-sm-auto col-lg-8 mx-auto mt-2">
        <div class="card p-4">
            <h4 class="card-title">
                <?php echo $row['title']?>
            </h4>
            <p>
            <?php echo $row['description']?>
            </p>
        </div>
        <div class="card p-4 mt-2">
         <p class='bg-danger p-2 text-white fw-bold text-center' style='font-size:14px;'>This Form Doesn't Currently Accept Any Submissions.</p> 
        </div>
    </div>
        </div>
    </div>
        <div class="text-center">
        <p class='mt-2'>This Form Is Powered By <a href="https://teachlabs.unaux.com/">TeachLab</a></p>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

<?php }?>
 
<?php }}else if(!isset($_GET['formid']) && !isset($_GET['success'])){ ?>

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
		
		<a href="https://teachlabs.unaux.com/" class="link_404">Go to Home</a>
	</div>
		</div>
		</div>
		</div>
	</div>
</section>

<?php } else if(isset($_GET['success'])){ ?>

    <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">
    <title>Teachlab - Assignment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body style="background-color:#f5f5f5;overflow-x: hidden;">
    <div class="row">
        <div class="col-md-9 ms-sm-auto col-lg-8 mx-auto mt-2">
        <!-- <div class="card p-4">
        </div> -->
        <div class="card p-4 mt-2">
         <p class='bg-success p-2 text-white fw-bold text-center' style='font-size:14px;'>Thank You , We Saved Your Submission.</p> 
        </div>
    </div>
        </div>
    </div>
        <div class="text-center">
        <p class='mt-2'>This Form Is Powered By <a href="https://teachlabs.unaux.com/" class='fw-bold'>TeachLab</a></p>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>


<?php } ?>


