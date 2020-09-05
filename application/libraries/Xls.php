<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include('./PHPExcel/PHPExcel.php');

class Xls extends PHPExcel {
    public function __construct() {
        // $this->obj =& get_instance();
        // $this->ex = new PHPExcel();
        parent::__construct();
    }

    function d_load($objPHPExcel,$filename){
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}
