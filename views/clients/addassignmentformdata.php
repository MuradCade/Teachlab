<?php
include('../../model/dbcon.php');

   if(isset($_POST['submit'])){
            if(isset($_GET['formid']) && isset($_GET['filetype']) && isset($_GET['coursename'])&& isset($_GET['marks'])){
                $filealowedinassignmentform = $_GET['filetype'];
                $formid = $_GET['formid'];
                $base64 = base64_encode($formid);
                $stdid = $_POST['stdid'];
                $stdname = $_POST['stdname'];
                $fileuploaded = $_FILES['fileuploaded']['name'];
                $extension = pathinfo($fileuploaded, PATHINFO_EXTENSION);
                $coursename = $_GET['coursename'];
                $marks = $_GET['marks'];
                // echo $extension;
                $allowedwordfiles = ['docx','doc'];
                $allowedpowerpointfiles = ['pptx','ppt','pptm','ppsx','potx','ppsm'];



                if(empty($stdid)){
                    header("location:assignmentform.clients.php?formid=".$base64."&emptyidfield");
                    exit();
                }else if(empty($stdname)){
                    header("location:assignmentform.clients.php?formid=".$base64."&emptynamefield");
                    exit();
                }else if(empty($fileuploaded)){
                    header("location:assignmentform.clients.php?formid=".$base64."&emptyfilefield");
                    exit();
                }else{
                    $path = '../teacher/uploads/';
                    $filesize = $_FILES['fileuploaded']['size']/1024;
                    $targets = $path.basename($_FILES['fileuploaded']['name']);
                    $sql = "select stdid from assignmententries where stdid = '$stdid'";
                    $result = mysqli_query($connection,$sql);
                    if(mysqli_num_rows($result) == 0){
                        
                        // if file extension is word documents
                        if($filealowedinassignmentform == 'word_document' && in_array($extension,$allowedwordfiles)){
                               // move the file to folder and store it in database
                               $sql2 =  "insert into assignmententries(stdid,stdfullname,coursename,marks,uploadedfile,filesize,formid)
                               values('$stdid','$stdname','$coursename','$marks','$fileuploaded','$filesize','$formid')";
                                $result2 = mysqli_query($connection,$sql2);
                                if($result2){
                                    if(move_uploaded_file($_FILES['fileuploaded']['tmp_name'],$targets)){
                                        header('location:assignmentform.clients.php?success');
                                        exit();

                                    }else{
                                     header('location:assignmentform.clients.php?failedtoupload');
                                     exit();
                                    }
                                }else{
                                    header('location:assignmentform.clients.php?queryfailed');
                                    exit();
                                }
                              

                               // if  file extension is power point
                        }else if($filealowedinassignmentform == 'power_point' && in_array($extension,$allowedpowerpointfiles)){
                             // move the file to folder and store it in database
                             $sql3 = "insert into assignmententries(stdid,stdfullname,coursename,marks,uploadedfile,filesize,formid)
                             values('$stdid','$stdname','$coursename','$marks','$fileuploaded','$filesize','$formid')";
                             $result3 = mysqli_query($connection,$sql3);
                           if($result3){
                            if(move_uploaded_file($_FILES['fileuploaded']['tmp_name'],$targets)){
                                header('location:assignmentform.clients.php?success');
                                exit();

                            }else{
                             header('location:assignmentform.clients.php?failedtoupload');
                             exit();
                            }
                        }else{
                            header('location:assignmentform.clients.php?queryfailed');
                            exit();
                        }
                    }else{
                        header("location:assignmentform.clients.php?formid=".$base64."&filenotsupported");
                        exit();
                     
                    }
                    
                  
                }else{
                    header("location:assignmentform.clients.php?formid=".$base64."&idfound");
                    exit();
                }
            }


       
   }else{
    header("location:assignmentform.clients.php?&redirected");
    exit();
   }

   
   }