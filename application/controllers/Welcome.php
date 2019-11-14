<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function imageResize(){
		
		if ($this->form_validation->run('imageResizeValidation') !== FALSE) {
				$data = $this->input->post();
			$height = $data['height'];
			$width = $data['width'];
			$img_link = $data['img_url'];
			$path = 'assets/images';
			if(!empty($img_link)){
				if (@getimagesize($img_link)) {
				$linkfilename = 'logo'.time().'.png';
				$img = 'assets/images/'.$linkfilename; 
				$linkfile =file_get_contents($img_link);
				fopen($img, "w");
				file_put_contents($img, $linkfile);
				$uploadData['full_path'] = $img;
				$uploadData['file_name'] = $linkfilename;
				$linkfilenametoresize['file_name'] = $linkfilename;
				$uploadedImage = $this->resizeImag($width, $height,$uploadData,$path);
				$this->checkImageNotEmpty($uploadedImage);
				}else{
				 $response = array('message' => 'Something went wrong...please try again');   
			     $this->output->set_status_header(500)->set_content_type('application/json')->set_output(json_encode($response));
				}
				

			}else{
				$config['upload_path'] = 'assets/images';
	            $config['allowed_types'] = 'jpg|jpeg|png|gif';
	            $config['file_name'] = $_FILES['img_file']['name'];

	            //Load upload library and initialize configuration
	            $this->load->library('upload', $config);
	            $this->upload->initialize($config);
	            if ($this->upload->do_upload('img_file')) {
	            	$uploadData = $this->upload->data();
					$uploadedImage = $this->resizeImag($width, $height,$uploadData,$path);
					$this->checkImageNotEmpty($uploadedImage);
				}else{
					$error = array('error' => $this->upload->display_errors()); 
					if(!empty($error['error'])){
						$response = array('message' => $error['error']);   
			    	 $this->output->set_status_header(500)->set_content_type('application/json')->set_output(json_encode($response));
					}else{
					$response = array('message' => 'Something went wrong...please try again');   
			     $this->output->set_status_header(500)->set_content_type('application/json')->set_output(json_encode($response));
					}
					
				}
			}
		}else{
			$this->index();
		}

	}

	public function checkImageNotEmpty($img){
    	if(!empty($img)){
			  $response = array('message' => 'Image resized successfully', 'image_path' => base_url().$img);   
			     $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($response));
		}else{
			 $response = array('message' => 'Something went wrong...please try again', 'status'=> 'error');   
			     $this->output->set_status_header(500)->set_content_type('application/json')->set_output(json_encode($response));
		}
    }
	
	public function resizeImag($width, $height, $filedata,$filename) {
        $this->load->library('image_lib');
        $configSize['image_library'] = 'gd2';
        $configSize['source_image'] = $filedata['full_path'];
        $configSize['create_thumb'] = FALSE;
        $configSize['maintain_ratio'] = FALSE;
        $configSize['width'] = $width;
        $configSize['height'] = $height;
        $configSize['new_image'] = $filename . '/' . $width . 'x' . $height . 'image' . $filedata['file_name'];
        $this->image_lib->initialize($configSize);            
        $this->image_lib->resize();
        $this->image_lib->clear();
        return $configSize['new_image'];
	}
}
