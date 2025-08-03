<?php

// deleting all files  inside the uploads when entire assignment form that contains student submission  is deleted



function deleteallfiles($wordfiles,$pdf_files){
    unlink($wordfiles);
    unlink($pdf_files);

}

