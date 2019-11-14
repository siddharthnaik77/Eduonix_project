<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
	<title>resize</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/style.css');?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/toastr.min.css');?>">
<script type="text/javascript" src="<?php echo base_url('assets/js/validate.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/custome.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/toastr.min.js'); ?>"></script>
</head>
<body>



<body>

<form action="<?php echo base_url('resize_image');?>" id="image_validate" method="POST" enctype="multipart/form-data">

<div class="file-upload">
  <input class="file-upload-btn" type="file" id="img_file" name="img_file" onchange="readURL(this);" accept="image/*" />

  
  <div class="file-upload-content">
    <img class="file-upload-image" src="#" alt="your image" />
    <div class="image-title-wrap">
    </div>
  </div>

</div>
<div class="file-upload">
	<h3 class="textcenter">OR</h3>
	<label>Image Url</label>
	<input type="url" name="img_url" id="img_url" placeholder="enter image url" >
 </div>
<div class="file-upload">
	<h3>Height And Width</h3>
	<label>Height </label>
	<input type="text" name="height" id="height" placeholder="enter height" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"><br>
	<?php echo form_error('height', '<div class="text-danger">', '</div>'); ?>
    <label>Width </label>
	<input type="text" name="width" id="width" placeholder="enter width" value="<?php set_value('width','',false)?>" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"><br>
	<?php echo form_error('width', '<div class="text-danger">', '</div>'); ?>
	<input type="submit" value="resize" >
</div>
</form>
<div class="file-upload showoutput-img">
	<h3>Output</h3>
	<img src="" id=output_img alt="imgage not found">
	<h4 id='error_msg'></h4>
	<a href="" id="down" download >Click hear to download</a>
</div>
</body>
</html>
<script type="text/javascript">
function readURL(input) {
  if (input.files && input.files[0]) {
	var reader = new FileReader();
	reader.onload = function(e) {
    	$('.image-upload-wrap').hide();
		$('.file-upload-image').attr('src', e.target.result);
      	$('.file-upload-content').show();
		$('.image-title').html(input.files[0].name);
    };
	reader.readAsDataURL(input.files[0]);
	} 
}

$('#image_validate').validate({
    rules: {
            'height':{
            	required:true,
            	maxlength:4
            },
            'width': {
            	required:true,
            	maxlength:4	
            },
            "img_file": {
                    required: '#img_url:blank'
            },
            "img_url": {
                required: '#img_file:blank'
            } 
		},
        messages: {
            'height': {
                required: "Please enter image height resize",
                maxlength: "Max length height not more than 4 digit"
            },
            'width': {
                required: "Please enter image width resize",
                maxlength: "Max length height not more than 4 digit"
            },
        },
        submitHandler: function (form) {

        var data = new FormData(form);
            
            $.ajax({
                url: '<?php echo base_url('resize_image') ?>',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function (response) {
                	$('#image_validate').trigger("reset");
                    var response = JSON.parse(JSON.stringify(response));
                     console.log(response);
                    $("#output_img").attr('src' , response.image_path);
                    $("#down").attr('href',response.image_path);
                    $('html, body').animate({
				        'scrollTop' : $(".showoutput-img").position().top
				    });
                    $('.showoutput-img').css('display','block');
                    $('.loader').hide();
                    toastr.success(response.message, {timeOut: 2000});
                },
                error: function (response) {
                var response = JSON.parse(JSON.stringify(response));
                toastr.error(response.responseJSON.message, {timeOut: 2000});
            }
            });
        }
    });
	
	$(document).on('change','#img_url',function(e){
		e.preventDefault();
		$('#img_file').val('');
		$('.file-upload-content').css('display','none');
	});

	$(document).on('change','#img_file',function(e){
		e.preventDefault();
		$('#img_url').val('');
	});

</script>