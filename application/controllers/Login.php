<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->viewFolder = strtolower(__CLASS__);
    }
    
    public function connexion()
    {
        $data = array(
            'title' => 'Enseigne Diffusion, le leader de la signalétique et enseignes sur Valenciennes',
            'description' => 'ED, votre plateforme de pilotage.',
            'content' => $this->viewFolder . '/' . __FUNCTION__
        );
        $this->load->view('template/content', $data);
    }
    
    public function identification()
    {
        
        $this->form_validation->set_rules('loginId', 'Login', 'trim|required');
        $this->form_validation->set_rules('loginPass', 'Mot de passe', 'trim|required');
       
        if (!$this->form_validation->run()) :
            echo json_encode(array('type' => 'error', 'message' => validation_errors()));
            exit;
        else :
            if ($this->input->post('loginId') != 'francois' && md5($this->input->post('loginId')) != '0d3b650ad5d990dce3fa88808251a4bf') :
                echo json_encode(array('type' => 'error', 'message' => 'Mauvais identifiant ou mot de passe' ));
                exit;
            else :
                switch (md5($this->input->post('loginPass'))) :
                    case '79e94706301d01c541b1a0eac7d4e6c4':
                    case 'e98279b020e44d7f43aa9bc97010b950':
                        /* ADMIN */
                        $this->session->set_userdata(array('loginTime' => time(), 'userLevel' => 'admin' ));
                        echo json_encode(array('type' => 'success'));
                        exit;
                        break;
                    case 'f8a4094bc6b3b26bc6c8d030f826bc52':
                        /* USER */
                        $this->session->set_userdata(array('loginTime' => time(), 'userLevel' => 'user' ));
                        echo json_encode(array('type' => 'success'));
                        exit;
                        break;
                    default:
                        echo json_encode(array('type' => 'error', 'message' => 'Mauvais identifiants' ));
                        exit;
                endswitch;
            endif;
        endif;
    }

    
}
