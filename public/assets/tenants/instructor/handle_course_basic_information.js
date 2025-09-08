// this file is responsible for creating , display,updating,deleteing course basic information tab
$(document).ready(function(){
    let url = window.location;
    let websiteurl = url.origin; // gets full address of our domain (http://lmslite.test)
    let pathnames = url.pathname;
    
    // Remove leading/trailing slashes and split by "/"
    let segments = pathnames.split('/').filter(Boolean); // help get the tenant name from url

    // Get the first segment
    let firstPart = segments[0]; // get tenant name from the url
    
    let finalurl = websiteurl+pathnames;



        
        // toastr["warning"]("message", "title");



        // step1 : get query in url to auto creae course
        // getting current query params from url
        const params = new URLSearchParams(window.location.search);
        const courseid = params.get('courseid');
        if(courseid){            
            $.ajax({
                url:finalurl,
                method:'POST',
                dataType:'json',
                data:{action:'auto-createcourse',courseid:courseid}
            })

        }



        // we showing the sell and resel price when option selected is pro
        let course_type = document.getElementById('course_type');
        let price_elements = document.getElementById('price_elements');

        if (!course_type || !price_elements) {
            console.error('Missing element in DOM');
            return;
        }

        // this checks if course type is paid (need intraction from user) means user should choose 
        course_type.addEventListener('change', function () {
            if (this.value.toLowerCase() === 'paid') {
                price_elements.classList.remove('d-none');
            } else {
                price_elements.classList.add('d-none');
            }
        });

    // basic course information form submission
    $('#basic_course_informations').submit(function(event){
        event.preventDefault();
        let regular_price = parseFloat($('#regular_price').val());
        let sell_price    = parseFloat($('#sell_price').val());

        let formData = new FormData(this); 
        formData.append('action','savetap1');// 'this' is the form element
        formData.append('courseid',courseid);// 'this' is the form element

        $.ajax({
        url:finalurl,
        method:'POST',
        dataType:'json',
        data:formData,
        processData: false, // prevent jQuery from turning FormData into a query string
        contentType: false, // let the browser set Content-Type with boundary
        success:function(response){
        
          
            if(response.errors){
                
                
                if(response.errors.course_title){
                toastr['error'](response.errors.course_title,'Course Basic Informations');

                }
                if(response.errors.course_description){
                toastr['error'](response.errors.course_description,'Course Basic Informations');

                }
                if(response.errors.course_level){
                toastr['error'](response.errors.course_level,'Course Basic Informations');

                }
                if(response.errors.course_type){
                toastr['error'](response.errors.course_type,'Course Basic Informations');

                }
                if(response.errors.course_status){
                toastr['error'](response.errors.course_status,'Course Basic Informations');

                }
                if(response.errors.course_video_intro){
                toastr['error'](response.errors.course_video_intro,'Course Basic Informations');

                }
                // if(response.errors.course_thumbnail){
                // toastr['error'](response.errors.course_thumbnail,'Course Basic Informations');

                // }
                if(response.errors == 'filenotsupported'){
                toastr['error']('File provided in course thumbnail is not allowed ','Course Basic Informations');

                }
                if(response.errors == 'emptyprices'){
                toastr['error']('Regular and Sell price are required','Course Basic Informations');

                }
                if(response.errors == 'price_error'){
                toastr['error']('Sell price should be less then the regular price  ','Course Basic Informations');

                }
                
            }
                            
            
           
                
               

             if(response.success){
                toastr['success']('Succesfully saved course basic information','Course Basic Informations');

                }
                
                
                
            // console.log(response);
           
            
        },
        error:function(xhr, status, error){
            console.log(error);
        }

    })
    })


    // display already created course content inside the course creation page
    let alreadycreatedcourse = websiteurl+'/'+firstPart+'/instructor/dashboard/course/alreadycreatedcourse';
    function displaycreatedcourse(){
    let course_title = document.getElementById('course_title');
    let course_description = document.getElementById('course_description');
    let course_level = document.getElementById('course_level');
    let course_type = document.getElementById('course_type');
    let regular_price = document.getElementById('regular_price');
    let sell_price = document.getElementById('sell_price');
    let course_status = document.getElementById('course_status');
    let course_video_intro = document.getElementById('course_video_intro');
        // get all the input fields in course basic information tab (exclude image input field)
        
        $.ajax({
        url:alreadycreatedcourse,
        method:'GET',
        data:{courseid:courseid},
        dataType:'json',
        success:function(response){
            // console.log(response.error == 'coursenotfound');
             if (response.error == 'coursenotfound'){
                toastr['error']('Sorry we failed to get current course content, please try again later!!!.', 'Course Basic information');
                // toastr['error']('Error occur while we fetching current course, please try agan later.','Course Basic information');
            }else{
                
                course_title.value = response.title;
                course_description.value = response.course_descriptions;
                
                course_level.innerHTML = `
                <option value="">Select Course Level</option>
                <option value="begginer" ${response.course_level == 'begginer'? 'selected' :''}>Begginer</option>
                <option value="intermediate" ${response.course_level == 'intermediate'? 'selected' :''}>Intermediate</option>
                <option value="advanced" ${response.course_level == 'advanced'? 'selected' :''}>Advanced</option>
                <option value="expert" ${response.course_level == 'expert'? 'selected' :''}>Expert</option>
                `
                course_type.innerHTML = `
                 <option value="">Select Course Pricing Model</option>
                  <option value="free" ${response.course_type == 'free'? 'selected' :''}>Free</option>
                  <option value="paid" ${response.course_type == 'paid'? 'selected' :''}>Paid</option>
                `
                //check if course type =  paid (when we display course content we check if course type is paid then display the inut prices)
                 if (course_type.value === 'paid') {
                price_elements.classList.remove('d-none');
            } else {
                price_elements.classList.add('d-none');
            }
                console.log();
            }


            regular_price.value = response.regular_price??'0';
            sell_price.value = response.sell_price??'0';

            course_status.innerHTML = `
                  <option value="">Select Course Status</option>
                  <option value="publish" ${response.course_status == 'publish'? 'selected' :''}>Publish</option>
                  <option value="draft"${response.course_status == 'draft'? 'selected' :''}>Draft</option>
                  <option value="comingsoon" ${response.course_status == 'comingsoon'? 'selected' :''}>Coming Soon</option>
            `;

            course_video_intro.value = response.video_intro??'';

        },
        error:function(xhr, status, error){
            console.log(error);
        }
    })
    }

    displaycreatedcourse();
    // console.log('helo');
    
    
    
});