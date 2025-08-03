<?php
include('../../model/dbcon.php');
include('turnwordintopdf.php');
   if(isset($_POST['submit'])){
            if(isset($_GET['formid']) && isset($_GET['filetype']) && isset($_GET['coursename'])&& isset($_GET['marks'])){
                $filealowedinassignmentform = $_GET['filetype'];
                $formid = mysqli_real_escape_string($connection,$_GET['formid']);
                $base64 = base64_encode($formid);
                $stdid = mysqli_real_escape_string($connection,$_POST['stdid']);
                $stdname = mysqli_real_escape_string($connection,$_POST['stdname']);
                $fileuploaded = mysqli_real_escape_string($connection , $_FILES['fileuploaded']['name']);
                // get rid of if filename contains space and add underscore
                // $getridoffilespac = str_replace(['-', ' '], ['','_'], $fileuploaded);
                $getridoffileleadingspace = preg_replace('/[\s\-]+/', '_', $fileuploaded);
                $getridoffilespace = str_replace(' ', '_', $getridoffileleadingspace);
                // $getridoffilespac .= str_replace(' ', '_', $fileuploaded);;
                //this variable holds filename with out the extension
                $filewithoutextension = pathinfo($getridoffilespace, PATHINFO_FILENAME).'.pdf';
                $extension = pathinfo($fileuploaded, PATHINFO_EXTENSION);
                $coursename = mysqli_real_escape_string($connection,$_GET['coursename']);
                $marks = mysqli_real_escape_string($connection,$_GET['marks']);
                // echo $extension;
                $allowedwordfiles = ['docx','doc'];



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
                    //pathoffilethat alredyexist inside the uploads folder
                    $originalfilepath = '../teacher/uploads/'.$fileuploaded;
                    $pdf_file_path = '../teacher/uploads/'.$filewithoutextension;
                    $filesize = $_FILES['fileuploaded']['size']/1024;
                    $targets = $path.basename($_FILES['fileuploaded']['name']);
                    $sql = "select stdid from assignmententries where stdid = '$stdid'";
                    $result = mysqli_query($connection,$sql);
                    if(mysqli_num_rows($result) == 0){
                        
                        // if file extension is word documents
                        if($filealowedinassignmentform == 'word_document' && in_array($extension,$allowedwordfiles)){
                               // move the file to folder and store it in database
                               $sql2 =  "insert into assignmententries(stdid,stdfullname,coursename,marks,uploadedfile,filesize,pdf_file ,formid)
                               values('$stdid','$stdname','$coursename','$marks','$fileuploaded','$filesize','$filewithoutextension','$formid')";
                                $result2 = mysqli_query($connection,$sql2);
                                if($result2){
                                    
                                    if(move_uploaded_file($_FILES['fileuploaded']['tmp_name'],$targets)){
                                        convertDocxToPdf($originalfilepath,$pdf_file_path);
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

                            // echo $fileuploaded; 
                            //   echo $filewithoutextension;

                               // if  file extension is power point
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