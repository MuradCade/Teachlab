<?php
// check if session already runing if not run new session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//check if session exist , if it exist prevent user from seeing login page
if(isset($_SESSION['userid'])){
    header('location:checkroles.php');
    exit();
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TeachLab - Login</title>

  <link rel="icon" type="image/x-icon" href="https://cdn.pixabay.com/photo/2012/04/24/12/46/letter-39873_640.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="module" src="https://cdn.jsdelivr.net/gh/lekoala/pop-notify@master/pop-notify.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/lekoala/pop-notify/pop-notify.css" rel="stylesheet">
  </head>
  <body style="background-color:#f2f2f2;">


      <div class="container">
      <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-12 mx-auto">
        <div class="card mt-5 p-2">
        <div class="card-head mt-4 px-4 text-center mb-2">
         <div class="text-center">
             <img  class="mb-2 " width="150" src="data:image/png;base64,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"/>
         </div>
          <h4 style="font-size:24px;" class="fw-bold mb-3">Login</h4>
          <p class="text-wrap tex-center" style="font-size:16px;line-height:1.6em;font-weight:500;">TeachLab simplifies the management of student information, <br>
          allowing teachers to concentrate on what they do best: teaching.</p>
        </div>
       <?php if(isset($_GET['emptyemail'])){?>
        <p class='bg-danger text-white fw-bold p-1 text-center ' style='font-size:15px;'>Empty email field</p>
        <?php }?>
        <?php if(isset($_GET['emptypwd'])){?>
          <p class='bg-danger text-white fw-bold p-1 text-center ' style='font-size:15px;'>Empty password field</p>
        <?php }?>
        <?php if(isset($_GET['emaildoesntexist'])){?>
          <p class='bg-danger text-white fw-bold p-1 text-center ' style='font-size:15px;'>User with provided email doesn't exist</p>
        <?php }?>
        <?php if(isset($_GET['wrongemailorpassword'])){?>
          <p class='bg-danger text-white fw-bold p-1 text-center ' style='font-size:15px;'>Wrong email or password</p>
        <?php }?>
        <?php if(isset($_GET['usernotfound'])){?>
          <p class='bg-danger text-white fw-bold p-1 text-center ' style='font-size:15px;'>Sorry , user not found</p>
        <?php }?>
        <?php if(isset($_GET['usernotverified'])){?>
          <p class='bg-danger text-white fw-bold p-1 text-center ' style='font-size:15px;'>user is not verified , please verify to get access to your account</p>
        <?php }?>
        <?php if(isset($_GET['pwdrecovered'])){?>
          <p class='bg-success text-white fw-bold p-1 text-center ' style='font-size:15px;'>Account Recovered Successfully , Please Login.</p>
        <?php }?>
        <?php if(isset($_GET['reoverylinkexpired'])){?>
          <p class='bg-danger text-white fw-bold p-1 text-center ' style='font-size:15px;'>Sorry, Recover Account Link Expired</p>
        <?php }?>
        
        <form method="post" action='../include/login.inc.php'>
          <div class="form-group p-2">
              <label class="form-label" style="font-size:15px;">Email</label>
              <input type="email" id="email" name="email" class="form-control" placeholder="Enter Email..." autocomplete="false"/>
          </div>

          
          <div class="form-group p-2" style="position:relative;">
              <label class="form-label" style="font-size:15px;">Password</label>
              <input type="password" id="pwd" name="pwd" class="form-control" placeholder="Enter Password..." autocomplete="false"/>
              <i class="bt bi-eye-slash-fill" style="position:absolute; top:48px;right:20px; cursor:pointer;" id="hide"></i>
              <i class="bt bi-eye-fill"style="position:absolute; top:48px;right:20px; display:none; cursor:pointer;" id="show"></i>
            </div>
          
                <div class="px-2 mb-2">
                <input type="submit" class="mt-2 btn btn-primary" value="Login" name='login'/>
                </div>
          </form>
        </div>
      </div>
    </div>
      </div>
    <p class="text-center mt-1 fw-medium mt-2" style="font-size:14px;font-weight:600 !important;">Don't Have Acccount Yet? <a href="signup.php">Create Account</a> | Or  <a href='forgetpwd.php'>Forget Password</a></p>


<script>        
let hide = document.getElementById('hide');
let show = document.getElementById('show');
let pwd = document.getElementById('pwd');



hide.addEventListener('click',function(){
        pwd.type = 'text';
        show.style.display='block';
        hide.style.display='none';

        
});
show.addEventListener('click',function(){
    pwd.type = 'password';
    hide.style.display='block';
    show.style.display='none';
});
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  </body>
</html>

