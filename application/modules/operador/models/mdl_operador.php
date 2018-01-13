<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_operador extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'operador';
        $this->primary_key = 'operador.id';
    }

    public function default_select()
    {
        $this->db->select('id, nombre_usuario, email');
    }

    public function default_order_by()
    {
        if ($this->order_by && $this->order) {
            $this->db->order_by($this->order_by, $this->order);
        }
        else {
            $this->db->order_by($this->primary_key);
        }
    }
    
    public function login($params = array())
    {
        $row = $this->where(array('nombre' => $params['email'],
                            'clave' => $params['clave']))
                    ->get()->row();
        if ($row && !empty($row)) {
            if ($row->status == '1') {
                    $this->session->set_userdata("logged_in", true);
                    $this->session->set_userdata("username", $row->nombre);
                    $this->session->set_userdata("sesion_id_user", $row->id);
                }
            else {
                $this->session->set_flashdata('custom_error', 'Usted es un usuario deshabilitado no puede ingresar al sistema.');
                redirect('operador/login');
            }
        }
        else {
            $this->session->set_flashdata('custom_info', 'El usuario o contrase&ntilde;a son incorrectos.');
            redirect('operador/login');
        }
    }
    
    public function validar_login()
    {
        return array(
            'email' => array(
                'field' => 'email',
                'label' => '"Usuario"',
                'rules' => 'required|xss_clean'
                ),
            'clave' => array(
                'field' => 'clave',
                'label' => '"Contrase&ntilde;a"',
                'rules' => 'required|xss_clean'
                ),
        );
    }    
        
    public function validation_rules()
    {
        return array(
            'nombre_usuario' => array(
                    'field' => 'nombre_usuario',
                    'label' => 'nombre_usuario',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'clave' => array(
                    'field' => 'clave',
                    'label' => 'clave',
                    'rules' => 'required|trim|xss_clean'
                    ),
            'email' => array(
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'required|trim|valid_email|xss_clean'
                    )
        );
    }
}
?>