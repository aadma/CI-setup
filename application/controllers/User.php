<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Public_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    public function index()
    {
        if($this->ion_auth->logged_in()===FALSE)
        {
            redirect('/');
        }
        redirect('/');
		
    }

    public function login()
    {
        $this->data['title'] = "Login";

        $this->load->library('form_validation');
        $this->form_validation->set_rules('identity', 'Identity', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('ajax','AJAX','trim|is_natural');
        if ($this->form_validation->run() === FALSE)
        {
            if($this->input->post('ajax'))
            {
                $response['identity_error'] = form_error('identity');
                $response['password_error'] = form_error('password');
                header("content-type:application/json");
                echo json_encode($response);
                exit;
            }
            $this->load->helper('form');
            $this->render('user/login_view');
        }
        else
        {
            $remember = (bool) $this->input->post('remember');
            $identity = $this->input->post('identity');
            $password = $this->input->post('password');
            $this->ion_auth->set_hook('post_login_successful', 'get_gravatar_hash', $this, '_gravatar', array());

            if ($this->ion_auth->login($identity, $password, $remember))
            {
				$location='';
				if($this->ion_auth->is_admin()){
					$location = 'dashboard';
				}
                if($this->input->post('ajax'))
                {
					
                    $response['logged_in'] = 1;
					$response['goto'] = $location;
                    header("content-type:application/json");
                    echo json_encode($response);
                    exit;
                }
                $this->load->library('rat');
                $this->rat->log('User logged in',1);
                redirect($location);
            }
            else
            {
                if($this->input->post('ajax'))
                {
                    $response['identity'] = $identity;
                    $response['password'] = $password;
                    $response['error'] = $this->ion_auth->errors();
                    header("content-type:application/json");
                    echo json_encode($response);
                    exit;
                }
                $_SESSION['auth_message'] = $this->ion_auth->errors();
                $this->session->mark_as_flash('auth_message');
                redirect('','refresh');
            }
        }
    }
	
	
	
    public function forgot()
    {
        $this->data['title'] = "Forgot email";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('identity', 'Identity', 'required|valid_email');
        if($this->form_validation->run() === FALSE)
        {
			if($this->input->post('ajax')){
				$response['error'] = validation_errors();
				header("content-type:application/json");
				echo json_encode($response);
				exit;
			}
            $this->render('user/forgot_view');
        }
        else
        {
            $email = $this->input->post('identity');

            if($this->ion_auth->forgotten_password($email))
            {
				if($this->input->post('ajax')){
					$response['reset_success'] = '1';
					
					$response['message'] = $this->lang->line('forgot_password_successful');
					header("content-type:application/json");
					echo json_encode($response);
					exit;
				}
                $_SESSION['message'] = $this->lang->line('forgot_password_successful');
				
            }
            else
            {
				if($this->input->post('ajax')){
					$response['error'] = $this->ion_auth->errors();
					header("content-type:application/json");
					echo json_encode($response);
					exit;
				}
                $_SESSION['message'] = $this->ion_auth->errors();
            }
            $this->session->mark_as_flash('auth_message');
            redirect('user/login');
        }
    }
	
	
	public function register(){
		$this->data['title'] = "Register";
        $this->load->library('form_validation');
		if ($this->ion_auth->logged_in())
        {
            redirect('', 'refresh');
        }
		$tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;
		$this->form_validation->set_rules('email', ucfirst($this->lang->line('create_user_validation_email_label')), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
		$this->form_validation->set_rules('identity',ucfirst($this->lang->line('create_user_validation_identity_label')),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
		$this->form_validation->set_rules('password', ucfirst($this->lang->line('create_user_validation_password_label')), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', ucfirst($this->lang->line('create_user_validation_password_confirm_label')), 'required');
		if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity =  $email ;
            $password = $this->input->post('password');

            
        }
		if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email))
        {
            
		   if($this->input->post('ajax')){
			   $response['registered'] = '1';
			   $response['message'] = $this->ion_auth->messages();
			   $response['message'].="Please check your inbox or spam folder in order to activate your account";
			   header("content-type:application/json");
			   echo json_encode($response);
			   exit;
		   }
		   
        }
		else
		{
			$response['error'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->ion_auth->messages()));
			header("content-type:application/json");
			echo json_encode($response);
			exit;
		}
	}
	
    public function _gravatar()
    {
        if($this->form_validation->valid_email($_SESSION['email']))
        {
            $gravatar_url = md5(strtolower(trim($_SESSION['email'])));
            $_SESSION['gravatar'] = $gravatar_url;
        }
        return TRUE;
    }
	
	
	// activate the user
	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("user/login", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("user/forgot_password", 'refresh');
		}
	}
	
	
	// reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}
		$this->load->library('form_validation');
		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'class' => 'form-control',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'class'   => 'form-control',
					'type'    => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->render('auth/reset_password', null);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("user/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('user/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("user/forgot_password", 'refresh');
		}
	}
	
	
	
	
	
	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


    public function logout()
    {
        $this->load->library('rat');
        $this->rat->log('User logged out',1);
		
        $this->ion_auth->logout();
		$_SESSION['auth_message'] = 'loggedout';
        $this->session->mark_as_flash('auth_message');
        redirect('');
    }
}