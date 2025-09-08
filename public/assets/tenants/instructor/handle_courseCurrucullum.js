// this file is responsible for create, displaying ,updating and deleting course chapters(curruculum)
$(document).ready(function(){


      let url = window.location;
    let websiteurl = url.origin; // gets full address of our domain (http://lmslite.test)
    let pathnames = url.pathname;

    let coursechapterurl = websiteurl+pathnames+'/createchapters';
    

    $.ajax({
        url:coursechapterurl,
        method:'GET',
        dataType:'json',
        success:function(response){
            console.log(response);
            
        },
        error:function(xhr,status,error){
            console.log(error);
            
        }
    })
    

})