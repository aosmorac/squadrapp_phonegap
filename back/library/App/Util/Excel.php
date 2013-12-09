<?php
include_once '/Excel/PHPExcel.php';
class App_Util_Excel
{
    //private $_excel = null;
    static public function excel ($title = null,$ignoreproperties=false)
    {
        try {
            $excel = new PHPExcel();
            
            
            
        } catch (Exception $exc) {
            echo $exc->getMessage();
            return false;
        }
        if($ignoreproperties===false){
        $excel->getProperties()
            ->setCreator("BlueCargo")
            ->setLastModifiedBy("BlueCargo")
            ->setTitle((is_null($title) ? "Documento Excel" : $title))
            ->setSubject("Documento Excel")
            ->setDescription("Documento Excel, Blue Cargo Group")
            ->setKeywords("Excel Office php")
            ->setCategory("Excel");
        //$this->_excel = $excel;   
        }           
        return $excel;
    }
    static public function getContent ($mExcel)
    {
        $tempFile = "excelTMP" . uniqid();
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $ruta = "{$baseUrl}/images_rs/";
        $pathRuta = $_SERVER["DOCUMENT_ROOT"] . $ruta;
        $objWriter = PHPExcel_IOFactory::createWriter($mExcel, 'Excel5');
        $objWriter->save($pathRuta . $tempFile);
        $fileContainer = file_get_contents($pathRuta . $tempFile);
        unlink($pathRuta . $tempFile);
        return $fileContainer;
    }
    static public function createFile ($mExcel,$nameFile=false,$path_temp=false)
    {
        $tempFile = ($nameFile)?$nameFile:"excelTMP" . uniqid();
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $ruta = ($path_temp)?$path_temp:"{$baseUrl}/images_rs/";
        $pathRuta = $_SERVER["DOCUMENT_ROOT"] . $ruta;
        $objWriter = PHPExcel_IOFactory::createWriter($mExcel, 'Excel5');
        $objWriter->save($pathRuta . $tempFile);
        return $pathRuta . $tempFile;
    }
    static public function load($pFilename,$reader=false){
    	if($reader) {
    		$objReader = PHPExcel_IOFactory::createReader($reader);
    		return $objReader->load($pFilename);
    	}else{
        	return PHPExcel_IOFactory::load($pFilename);
    	}
         /**Ejemplo mostrando todos los datos de un archivo
         *  foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $worksheetTitle = $worksheet->getTitle();
                $highestRow = $worksheet->getHighestRow(); // e.g. 10
                $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $nrColumns = ord($highestColumn) - 64;
                echo "<br>The worksheet " . $worksheetTitle . " has ";
                echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
                echo ' and ' . $highestRow . ' row.';
                echo '<br>Data: <table border="1"><tr>';
                for ($row = 1; $row <= $highestRow; ++$row) {
                    echo '<tr>';
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getValue();
                        $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
                        echo '<td>' . $val . '<br>(Typ ' . $dataType . ')</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
            }
         */
    }
    static public function columnIndexFromString($column,$string=false){
        if($string)
         return PHPExcel_Cell::stringFromColumnIndex($column);
        else
    	return PHPExcel_Cell::columnIndexFromString($column);
    }
    static public function columnStringFromIndex($column,$string=false){
        if($string)
         return PHPExcel_Cell::columnStringFromIndex($column);
        else
    	return PHPExcel_Cell::columnStringFromIndex($column);
    }

    
    static public function getStyleConditional(){
        return new PHPExcel_Style_Conditional();
        /* Ejemplo coloreando una columna de acuerdo a la condicion 
         		$lastColumn=$mExcel->setActiveSheetIndex(0)->getHighestColumn();
                $lastRow=$mExcel->setActiveSheetIndex(0)->getHighestRow();
                
                $objConditional2 = App_Util_Excel::getStyleConditional();
                $objConditional2->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS);
                $objConditional2->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_EQUAL);
                $objConditional2->setConditions('1');
                $objConditional2->getStyle()->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
                $objConditional2->getStyle()->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_GREEN);
                $objConditional2->getStyle()->getFont()->setBold(true);
                 


                $conditionalStyles = $mExcel->getActiveSheet()->getStyle($lastColumn."1")->getConditionalStyles();
                array_push($conditionalStyles, $objConditional2);
                
                $mExcel->getActiveSheet()->getStyle($lastColumn."1")->setConditionalStyles($conditionalStyles);
                $mExcel->getActiveSheet()->duplicateConditionalStyle(
                $mExcel->getActiveSheet()->getStyle($lastColumn."1")->getConditionalStyles(),
                $lastColumn."1:".$lastColumn.$lastRow);
         */
    }
    
}