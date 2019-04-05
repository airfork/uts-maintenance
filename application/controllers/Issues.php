<?php
/**
 * Created by PhpStorm.
 * User: airfork
 * Date: 3/24/19
 * Time: 3:18 AM
 */

class Issues extends CI_Controller {
    private $production = false;
    private $webURL = 'https://inspection-list.herokuapp.com';

    public function __construct() {
        parent::__construct();
        $this->load->model('issues_model');
        $this->load->helper('url_helper');
        $this->load->helper('download');
        $this->load->helper('file');
        $this->load->library('session');
        if (getenv('PRODUCTION')) {
            $this->production = true;
        }
    }

    // Generate master excel sheet of issues
    public function master() {
        if (!$this->validate()) {
            if ($this->production) {
                redirect($this->webURL.'/login', 'refresh');
                return;
            }
            redirect('/login', 'refresh');
            return;
        }

        $locations = array(
            'Destination Signs & Emergency Button',
            'Zonar',
            'Stop Request',
            'Radio & PA',
            'Passenger Seats',
            'Emergency Equipment',
            'ADA',
            'Emergency Exits',
            'Auxiliary Fan',
            'Heat/AC',
            'Driver\'s Seat',
            'Mirrors',
            'Defroster',
            'Interior Lighting',
            'Windshield Wipers',
            'Glass Breakage',
            'Bike Racks',
            'Other',
        );
        $writer = new XLSXWriter();
        $evenStyle = array('font'=>'Arial','font-size'=>11, 'fill'=>'#ccc', 'border'=>'left,right', 'border-style' => 'thin', 'halign' => 'center');
        $oddStyle = array('font'=>'Arial','font-size'=>11, 'border'=>'left,right', 'border-style' => 'thin', 'halign' => 'center');

        foreach ($locations as $location) {
            $rows = $this->issues_model->get_issues($location);
            $count = 0;
            $location = ($location == 'Destination Signs & Emergency Button' ? 'Dest. Signs' : $location);
            $writer->writeSheetHeader($location, array('Unit #' => 'integer', 'Details' => 'string', 'Date Reported' => 'MM/DD/YYYY'), $col_options = ['widths'=>[10,48,19], 'halign' => 'center', 'border'=>'left,right,top,bottom', 'border-style' => 'thin', 'font-style' => 'bold']);
            foreach ($rows as $issue) {
                $style = ($count++ % 2 == 0 ? $evenStyle : $oddStyle);
                $writer->writeSheetRow($location, array($issue['busnumber'], $issue['description'], $issue['createdate']), $style);
            }
            $writer->writeSheetRow($location, array());
        }

        $filename =APPPATH.'/tmp/Bus.xlsx';
        if ($this->production) {
            $filename = 'Bus.xlsx';
        }
        ob_clean();
        $writer->writeToFile($filename);
        $data = file_get_contents($filename);
        force_download('Bus.xlsx', $data. true);
    }

    private function validate() {
        if (empty($_SESSION['id'])) {
            return false;
        }
        return true;
    }
}