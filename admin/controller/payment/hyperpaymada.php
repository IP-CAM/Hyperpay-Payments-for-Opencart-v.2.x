<?php

class ControllerPaymentHyperpaymada extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('payment/hyperpaymada');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        $data['heading_title'] = $this->language->get('heading_title');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_setting_setting->editSetting('hyperpaymada', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/hyperpaymada', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['permission'])) {
            $data['error_permission'] = $this->error['permission'];
        } else {
            $data['error_permission'] = '';
        }

        if (isset($this->error['heading_title'])) {
            $data['error_heading_title'] = $this->error['heading_title'];
        } else {
            $data['error_heading_title'] = '';
        }

        if (isset($this->error['channel'])) {
            $data['error_channel'] = $this->error['channel'];
        } else {
            $data['error_channel'] = '';
        }

        if (isset($this->error['access_token'])) {
            $data['error_access_token'] = $this->error['access_token'];
        } else {
            $data['error_access_token'] = '';
        }


        //-------------------------------------------------------        

        $data['text_edit'] = $this->language->get('text_edit');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['action'] = $this->url->link('payment/hyperpaymada', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        $data['entry_heading_title'] = $this->language->get('entry_heading_title');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_testmode'] = $this->language->get('entry_testmode');
        $data['entry_testmode_off'] = $this->language->get('entry_testmode_off');
        $data['entry_testmode_on'] = $this->language->get('entry_testmode_on');

        $data['entry_trans_type'] = $this->language->get('entry_trans_type');
        $data['entry_all_trans_type'] = $this->get_hyperpaymada_trans_type();

        $data['entry_trans_mode'] = $this->language->get('entry_trans_mode');
        $data['entry_all_trans_mode'] = $this->get_hyperpaymada_trans_mode();

        $data['entry_channel'] = $this->language->get('entry_channel');
        $data['entry_access_token'] = $this->language->get('entry_access_token');
        //  $data['entry_loginid'] = $this->language->get('entry_loginid');
        //  $data['entry_password'] = $this->language->get('entry_password');

        $data['entry_brands'] = $this->language->get('entry_brands');
        $data['entry_all_brands'] = $this->get_hyperpaymada_payment_methods();

        $data['entry_payment_style'] = $this->language->get('Payment Style');
        $data['entry_all_payment_style'] = $this->get_hyperpaymada_payment_style();

        $data['entry_mailerrors'] = $this->language->get('entry_mailerrors');
        $data['entry_mailerrors_enable'] = $this->language->get('entry_mailerrors_enable');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');

        $data['entry_connector'] = $this->language->get('entry_connector');
        $data['entry_all_connector'] = $this->get_hyperpaymada_connector();
        //-----------------------------------------------------------------------

        if (isset($this->request->post['hyperpaymada_status'])) {
            $data['hyperpaymada_status'] = $this->request->post['hyperpaymada_status'];
        } else {
            $data['hyperpaymada_status'] = $this->config->get('hyperpaymada_status');
        }

        if (isset($this->request->post['hyperpaymada_sort_order'])) {
            $data['hyperpaymada_sort_order'] = $this->request->post['hyperpaymada_sort_order'];
        } else {
            $data['hyperpaymada_sort_order'] = $this->config->get('hyperpaymada_sort_order');
        }

        if (isset($this->request->post['hyperpaymada_testmode'])) {
            $data['hyperpaymada_testmode'] = $this->request->post['hyperpaymada_testmode'];
        } else {
            $data['hyperpaymada_testmode'] = $this->config->get('hyperpaymada_testmode');
        }

        if (isset($this->request->post['hyperpaymada_trans_type'])) {
            $data['hyperpaymada_trans_type'] = $this->request->post['hyperpaymada_trans_type'];
        } else {
            $data['hyperpaymada_trans_type'] = $this->config->get('hyperpaymada_trans_type');
        }

        if (isset($this->request->post['hyperpaymada_trans_mode'])) {
            $data['hyperpaymada_trans_mode'] = $this->request->post['hyperpaymada_trans_mode'];
        } else {
            $data['hyperpaymada_trans_mode'] = $this->config->get('hyperpaymada_trans_mode');
        }

        if (isset($this->request->post['hyperpaymada_heading_title'])) {
            $data['hyperpaymada_heading_title'] = $this->request->post['hyperpaymada_heading_title'];
        } else {
            $data['hyperpaymada_heading_title'] = $this->config->get('hyperpaymada_heading_title');
        }

        if (isset($this->request->post['hyperpaymada_channel'])) {
            $data['hyperpaymada_channel'] = $this->request->post['hyperpaymada_channel'];
        } else {
            $data['hyperpaymada_channel'] = $this->config->get('hyperpaymada_channel');
        }

        if (isset($this->request->post['hyperpaymada_brands'])) {
            $data['hyperpaymada_brands'] = $this->request->post['hyperpaymada_brands'];
        } else {
            $data['hyperpaymada_brands'] = $this->config->get('hyperpaymada_brands');
        }

        if (isset($this->request->post['hyperpaymada_payment_style'])) {
            $data['hyperpaymada_payment_style'] = $this->request->post['hyperpaymada_payment_style'];
        } else {
            $data['hyperpaymada_payment_style'] = $this->config->get('hyperpaymada_payment_style');
        }

        if (isset($this->request->post['hyperpaymada_mailerrors'])) {
            $data['hyperpaymada_mailerrors'] = $this->request->post['hyperpaymada_mailerrors'];
        } else {
            $data['hyperpaymada_mailerrors'] = $this->config->get('hyperpaymada_mailerrors');
        }

        if (isset($this->request->post['hyperpaymada_mailerrors_enable'])) {
            $data['hyperpaymada_mailerrors_enable'] = $this->request->post['hyperpaymada_mailerrors_enable'];
        } else {
            $data['hyperpaymada_mailerrors_enable'] = $this->config->get('hyperpaymada_mailerrors_enable');
        }

        $data['hyperpaymada_admin_email'] = $this->config->get('config_email');

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['hyperpaymada_order_status_id'])) {
            $data['hyperpaymada_order_status_id'] = $this->request->post['hyperpaymada_order_status_id'];
        } else {
            $data['hyperpaymada_order_status_id'] = $this->config->get('hyperpaymada_order_status_id');
        }

        if (isset($this->request->post['hyperpaymada_order_status_failed_id'])) {
            $data['hyperpaymada_order_status_failed_id'] = $this->request->post['hyperpaymada_order_status_failed_id'];
        } else {
            $data['hyperpaymada_order_status_failed_id'] = $this->config->get('hyperpaymada_order_status_failed_id');
        }

        if (isset($this->request->post['hyperpaymada_access_token'])) {
            $data['hyperpaymada_access_token'] = $this->request->post['hyperpaymada_access_token'];
        } else {
            $data['hyperpaymada_access_token'] = $this->config->get('hyperpaymada_access_token');
        }

        if (isset($this->request->post['hyperpaymada_connector'])) {
            $data['hyperpaymada_connector'] = $this->request->post['hyperpaymada_connector'];
        } else {
            $data['hyperpaymada_connector'] = $this->config->get('hyperpaymada_connector');
        }


        $data['text_missing'] = $this->language->get('text_missing');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('payment/hyperpaymada.tpl', $data));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/hyperpaymada')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['hyperpaymada_heading_title']) {
            $this->error['heading_title'] = $this->language->get('error_heading_title');
        }

        if (!$this->request->post['hyperpaymada_channel']) {
            $this->error['channel'] = $this->language->get('error_channel');
        }

        if (!$this->request->post['hyperpaymada_access_token']) {
            $this->error['access_token'] = $this->language->get('access_token');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    private function get_hyperpaymada_payment_methods()
    {
        $hyperpaymada_payments = array(
            'MADA' => 'Mada',

        );

        return $hyperpaymada_payments;
    }


    private function get_hyperpaymada_trans_mode()
    {
        $hyperpaymada_trans_mode = array(
            'CONNECTOR_TEST' => 'CONNECTOR_TEST',
            'INTEGRATOR_TEST' => 'INTEGRATOR_TEST',
            'LIVE' => 'LIVE'
        );

        return $hyperpaymada_trans_mode;
    }

    private function get_hyperpaymada_trans_type()
    {
        $hyperpaymada_trans_type = array(
            'DB' => 'Debit',
            'PA' => 'Pre-Authorization'
        );

        return $hyperpaymada_trans_type;
    }

    private function get_hyperpaymada_payment_style()
    {
        $hyperpaymada_payment_style = array(
            'card' => 'Card',
            'plain' => 'Plain'
        );

        return $hyperpaymada_payment_style;
    }

    private function get_hyperpaymada_connector()
    {
        $hyperpaymada_connector = array(
            'visa' => 'VISA ACP',
            'migs' => 'MIGS / MPGS'
        );
        return $hyperpaymada_connector;
    }
}