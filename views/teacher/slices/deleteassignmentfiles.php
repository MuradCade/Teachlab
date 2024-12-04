<?php
// when submitted student assignment deleted by the teacher , we should also delete their submitted document from
// our system.
// this is delete single fil(means only delete the content of the one student)


function deleteassignmentdocuement($word_document,$pdf_document){
    unlink($word_document);
    unlink($pdf_document);

}

