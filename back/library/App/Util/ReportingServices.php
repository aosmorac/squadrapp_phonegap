<?php
include_once '/ssrs/SSRSReport.php';
class App_Util_ReportingServices
{
    private $_UID = "";
    private $_PASSWD = "";
    private $_SERVICE_URL = "";
    private $_NAME_REPORT = "";
    private $_ssrs_report = null;
    private $_parametersArray = array();
    function __construct ($options = Array())
    {
        if (! count($options)) {
            //$this->_SERVICE_URL = "http://desar01/ReportServer/";
            $this->_UID = "bluecargogroup\\user.blue";
            $this->_PASSWD = "gZtdH3o1jv";
                        $this->_SERVICE_URL = "http://BLUE05MP112:81/ReportServer"; 
//	                    $this->_UID="BLUE05MP112\Marino";
//	                    $this->_PASSWD="12345678";
	        $this->_SERVICE_URL = "http://reportsrv02.bluecargogroup.com/ReportServer"; 
	        //            $this->_UID="bluecargogroup\miguel.aguacia";
	        //            $this->_PASSWD="ma2968662";
        }
        $credentials = new Credentials($this->_UID, $this->_PASSWD);
        try {
            $ssrs_report = new SSRSReport($credentials, $this->_SERVICE_URL);
        } catch (SSRSReportException $serviceException) {
            $priorityMessenger = new App_View_Helper_PriorityMessenger();
            $priorityMessenger->priorityMessenger(
            $serviceException->GetErrorMessage(), "error");
            return false;
        }
        $this->_ssrs_report = $ssrs_report;
        return $ssrs_report;
    }
    function isValid(){
    	if(is_null($this->_ssrs_report)) return false;
    	return true;
    }
    function listChilrend ($folder="")
    {
        $catalogItems = $this->_ssrs_report->ListChildren("/", true);
        $reports = array();
        foreach ($catalogItems as $catalogItem) {
        	
            if ($catalogItem->Type == ItemTypeEnum::$Report) {
            	if(strpos($catalogItem->Path,$folder)===0){
	                $reports[] = array("Name" => $catalogItem->Name, 
	                "Path" => $catalogItem->Path);
	            }
        	}
        }
        return $reports;
    }
    function getReportParameters ($report=null, $database = null)
    {
    	if (is_null($report)){
    		$report = $this->_NAME_REPORT;
    	}
        $dataSourceCredentials = null;
        if (! empty($database)) {
            define("DS_DB", "GB_MAIN");
            define("DS_UID", "USRBLUENET");
            define("DS_PWD", "12345678");
            $dataSourceCredentials = array();
            $dataSourceCredentials[0] = new DataSourceCredentials();
            $dataSourceCredentials[0]->DataSourceName = DS_DB;
            $dataSourceCredentials[0]->UserName = DS_UID;
            $dataSourceCredentials[0]->Password = DS_PWD;
        }
        $reportParameters = $this->_ssrs_report->GetReportParameters($report, 
        null, true, null, $dataSourceCredentials);
        return $reportParameters;
    }
    function listRenderingExtensions ()
    {
        $extensions = $this->_ssrs_report->ListRenderingExtensions();
        $result = array();
        foreach ($extensions as $extension) {
            $result[] = $extension->Name;
        }
        return $result;
    }
    function renderElement ($report, $parameters, $options=array())
    {
    	$this->_NAME_REPORT=$report;
    	$this->_parametersArray = $parameters;
        $txtLanguage = App_Util_Language::getTextLanguage();
        if (! $this->_ssrs_report)
            return "";
        $format = $options["format"];
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $ruta = "{$baseUrl}/images_rs/";
        $pathRuta = $_SERVER["DOCUMENT_ROOT"] . $ruta;
        $nombreReporte = $report;
        $pos = strpos($nombreReporte, ".");
        $i = 0;
        while (strpos($nombreReporte, "/") !== false) {
            $nombreReporte = substr($nombreReporte, 
            strpos($nombreReporte, "/") + 1);
        }
        if(App_User::getUserName()=="admin"){
        	//Zend_Debug::dump($this->getReportParameters($report));
        	//Zend_Debug::dump($parameters);
        }
        $this->_ssrs_report->LoadReport2($report, NULL);
        $parameters_report = $this->getParameters($parameters);
        $executionInfo = $this->_ssrs_report->SetExecutionParameters2(
        $parameters_report, "es-CO");
        $reportParameters = $executionInfo->Parameters;
        if(App_User::getUserName()=="admin"){
        	//Zend_Debug::dump($reportParameters);
        }
        switch ($format) {
            case "HTML":
            	$requerido=false;
            	$html = '<center><div id="contentReport" style="width:800px;" >';
            	$htmlParams = $this->renderParameters($reportParameters,$requerido,$options["viewparam"]); 
                	$html .= "\n<div style='background-color:#D9D9D9; width:800px;' align='left'>";
                	$html .= $htmlParams;
                	$html .= "\n</div>";
            	if($requerido){
	            		$html .= "\n".'<div id="contentReport" style="overflow:auto; width:800px;background-color:white">';
            		    $html .= "\n" . '</div>';
                		$html .= "\n" . '</div></center>';
            		
            		return $html;
            	}
                $htmlFormat = new RenderAsHTML();
                $htmlFormat->StreamRoot = $ruta;
                $result_html = $this->_ssrs_report->Render2($htmlFormat, 
                PageCountModeEnum::$Estimate, $Extension, $MimeType, $Encoding, 
                $Warnings, $StreamIds);
                $i = 0;
                foreach ($StreamIds as $StreamId) {
                    $result_image = $this->_ssrs_report->RenderStream(
                    $htmlFormat, $StreamIds[$i], $Encoding, $MimeType);
                    if (! $handle = fopen($pathRuta . $StreamIds[$i], 'wb')) {
                        echo $txtLanguage->Error->ssrs->file->nowriteInput;
                        exit();
                    }
                    if (fwrite($handle, $result_image) === FALSE) {
                        echo $txtLanguage->Error->ssrs->file->nowriteOutput;
                        exit();
                    }
                    fclose($handle);
                    $i ++;
                }
                $this->check_link($result_html);
            	$html .= "\n".'<div style="overflow:auto; width:800px; height:400px;background-color:white">';
                $html .= $result_html;
                $html .= "\n" . '</div>';
                $html .= "\n" . '</div></center>';
                return $html;
                break;
            case 'PDF':
                $render = new RenderAsPDF();
                break;
            case 'EXCEL':
                $render = new RenderAsEXCEL();
                break;
            case 'WORD':
                $render = new RenderAsWORD();
                break;
            case 'MHTML':
                $render = new RenderAsMHTML();
                break;
            case 'CSV':
                $render = new RenderAsCSV();
                break;
            case 'XML':
                $render = new RenderAsXML();
                break;
            case 'IMAGE':
                $render = new RenderAsIMAGE();
                break;
            default:
                $priorityMessenger = new App_View_Helper_PriorityMessenger();
                $priorityMessenger->priorityMessenger(
                $txtLanguage->Error->ssrs->report->noType, "error");
                return false;
                break;
        }
        $result = $this->_ssrs_report->Render2($render, 
        PageCountModeEnum::$Estimate, $Extension, $MimeType, $Encoding, 
        $Warnings, $StreamIds);
        $charset = ($Encoding) ? "; charset={$Encoding}" : "";
        header("Content-Type: {$MimeType}{$charset}");
        header(
        "Content-Disposition: attachment; filename=\"{$nombreReporte}.{$Extension}\"");
        header("Content-length: " . (string) (strlen($result)));
        header(
        "Expires: " . gmdate("D, d M Y H:i:s", 
        mktime(date("H") + 2, date("i"), date("s"), date("m"), date("d"), 
        date("Y"))) . " GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        echo $result;
    }
    function renderParameters ($reportParameters,&$requerid=false,$viewparam)
    {
    	$i = 0;
        $controls = "";
        $controls .= "\n<div style='float: right; height:22px;width: 170px;'>";
        $controls .= $this->getExportFormats();
        $controls .= "\n</div>";
        $style="";
        if(!$viewparam){
        	$controls .= "\n<table border='0'><tr><td style='height:22px;'>&nbsp;</td></tr></table>";
        	$style="style='display:none;' ";
        }
		$controls .= "\n<input {$style}type='submit' value='Ver Reporte' style='float: right;margin-right:3px;margin-top:3px;' />";
        $controls .= "\n<table border='0' {$style}>";
        foreach ($reportParameters as $reportParameter) {
        	if(!empty($reportParameter->Prompt)){
        		
        	
	        	if(!($reportParameter->AllowBlank) && count($reportParameter->DefaultValues)==0) $requerid=true;
	            //TODO: poner de dos parametros por fila
	            if ($i % 2 == 0)
	                $controls .= "\n<tr><td>";
	            else
	                $controls .= "<td>";
	                 //Valores por defecto
	            $default = null;
	            foreach ($reportParameter->DefaultValues as $vals)
	                foreach ($vals as $key => $def)
	                    $default = $def;
	            $controls .= $reportParameter->Prompt . "</td><td>";
	            //si es una lista poner un select
	            if (sizeof($reportParameter->ValidValues) > 0) {
	                $dependencies = empty($reportParameter->Dependencies) ? "onchange='getParameters();'" : "";
	                if($reportParameter->MultiValue){
	                	$multiselect="multiselect";
	                	$multiple = "multiple='multiple'";
	                	$corchete = "[]";
	                }else{
	                	$multiselect="nomultiselect";
	                	$multiple="";
	                	$corchete = "";
	                }
	                $controls .= "\n<select class='$multiselect'  $multiple name='{$reportParameter->Name}{$corchete}' id='{$reportParameter->Name}{$corchete}' $dependencies size='5' width='190px'>";
	                if(!is_array($default)) {
	                	$default2=$default;
	                		$default=array();
	                		$default[]=$default2;
	                };
	                foreach ($reportParameter->ValidValues as $values) {
	                    //Marcar los valores por defecto
	                    $selected = (in_array($values->Value, $default)) ? "selected='selected'" : "";
	                    $controls .= "\n<option value='" . $values->Value .
	                     "' $selected>" . trim($values->Label) . "</option>";
	                }
	                $controls .= "\n</select\n>";
	            } //tipo booleano con checkbox
	else 
	                if ($reportParameter->Type == "Boolean") {
	                    //escoger valor por defecto
	                    $selected = (! empty($default) &&
	                     $default != "False") ? "checked='checked'" : "";
	                    $controls .= "\n<input class='$reportParameter->Type' name='$reportParameter->Name' id='$reportParameter->Name' type='checkbox' $selected/>";
	                } //Otros tipos de datos  (DateTime, Integer, Float)
	else {
						if(array_key_exists($reportParameter->Name, $this->_parametersArray)){
							$default = $this->_parametersArray[$reportParameter->Name];
						}
						
	                    $selected = (! empty($default)) ? "value='" .
	                     $default . "'" : "";
	                    $controls .= "\n<input class='$reportParameter->Type' name='$reportParameter->Name' id='$reportParameter->Name' type='text' $selected/>";
	                }
	            //TODO: cerrar la fila
	            if ($i % 2 == 0)
	                $controls .= "</td>";
	            else
	                $controls .= "</td></tr>";
	            $i ++;
        	}
        }
        
        $controls .= "\n</table>";
        return $controls;
    }
    
    function getExportFormats ()
    {
        //$extensions = $this->_ssrs_report->ListRenderingExtensions();
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $controls="";
        $style="margin-top:1px;margint-top:1px;width: 25px;";
        $controls .= "\n<button class='button_report' style='{$style}' value='PDF' ><img src='".$baseUrl."/img/formatos/pdf.gif' title='PDF'/></button>";
        $controls .= "\n<button class='button_report' style='{$style}' value='EXCEL' ><img src='".$baseUrl."/img/formatos/xls.gif' title='EXCEL'/></button>";
        $controls .= "\n<button class='button_report' style='{$style}' value='WORD' ><img src='".$baseUrl."/img/formatos/doc.gif' title='WORD'/></button>";
        //        $controls .= "\n<button style='width=22px' value='CSV' name='CSV' ><img src='".$baseUrl."/img/formatos/CSV.gif' title='CSV'/></button>";
        $controls .= "\n<button class='button_report' style='{$style}' value='MHTML' ><img src='".$baseUrl."/img/formatos/htm.gif' title='MHTML'/></button>";
        $controls .= "\n<button class='button_report' style='{$style}' value='IMAGE' ><img src='".$baseUrl."/img/formatos/jpg.gif' title='IMAGE'/></button>";
        $controls .= "\n<button class='button_report' style='{$style}' value='XML'  ><img src='".$baseUrl."/img/formatos/txt.gif' title='XML'/></button>";
        
        return $controls;
    }
    function getParameters ($parametersArray)
    {
        $parameters = array();
        $i = 0;
        $parametersRequired = $this->getReportParameters();
        if(APPLICATION_ENV!="production"){
        	//Zend_Debug::dump($parametersRequired,"Parametros Requeridos");
        }
        $arrayParametersRequired = array();
        foreach ($parametersRequired as $value) {
        	$arrayParametersRequired[$value->Name]=$value->Type;
        }
		if(isset($arrayParametersRequired['use_id'])){
			$loggedUser = new Zend_Session_Namespace("loggedUser");
			$mvc = $loggedUser->MVC;
			$menu = $loggedUser->MenuActual;
			$mvc = $mvc["Module"] . "/" . $mvc["Controller"] . "/" . $mvc["action"];
			$perfil=App_Perfil::load($menu,$mvc);
			$permit = $perfil->getPermit('special');
			if(!$permit && !isset($parametersArray["use_id"])) {
				$parametersArray["use_id"]=App_User::getUserId();
			}
		}
		//Zend_Debug::dump($parametersArray,"parametersArray");
        foreach ($parametersArray as $key => $param) {
            if (! empty($param)) {
            	if(key_exists($key, $arrayParametersRequired)){
				//Zend_Debug::dump($key,"key");
	            	if(is_array($param)){
						foreach ($param as $value) {
							$parameters[$i] = new ParameterValue();
			                $parameters[$i]->Name = $key;
			                $parameters[$i]->Value = $value;
			                $i ++;
						}            		
	            	}else{
		                $parameters[$i] = new ParameterValue();
		                $parameters[$i]->Name = $key;
		                $posZero = strpos($param, " 00:00:00");
		                if($posZero!==false && $arrayParametersRequired[$key]=="DateTime"){
		                	$param= str_replace(" 00:00:00", "", $param);
		                	$param= str_replace("_", "/", $param);
		                	$starDate = new Zend_Date($param,"mm/dd/yyyy");
	       					$param=$starDate->toString("dd/mm/yyyy");
		                }
		                $parameters[$i]->Value = $param;
		                $i ++;
	            	}
            	}
            }
            if ($i > 100)
                break;
        }
        return $parameters;
    }
    function check_link(&$result_html){
		preg_match_all("|href=\"(.*)\"|U", $result_html, $salida, PREG_SET_ORDER);
		$arrayLinks=array();
		$arrayLinksCambiados=array();
	    for($i=0;$i<count($salida);$i++){
			$string=trim($salida[$i][1]);
			if(!in_array($string, $arrayLinks)){
		    	$arrayLinks[$i]=$string;
				
				$string=str_replace($this->_SERVICE_URL, "", $arrayLinks[$i]);
				$string=str_replace("?", "", $string);
				$string=str_replace("%2F", "_", $string);
				$string=str_replace("%20", " ", $string);
				$string=preg_replace("/&/","/",$string);
//				$string=str_replace("&", "?", $string,1);
//				$string=str_replace("&rc%3AParameters=Collapsed", "", $string);
//				$string=str_replace("&rc%3AToolbar=False", "", $string);
				$string=str_replace("%3A", ":", $string);
				$string=str_replace("=", "/", $string);
				
				$arrayLinksCambiados[$i]="style=\"cursor: pointer;\" onclick=\"redirectReport('".$string."');";
			}
		}
		foreach ($arrayLinks as $key=>$value) {
			$result_html=str_replace('href="'.$arrayLinks[$key], ''.$arrayLinksCambiados[$key], $result_html);
		}
//		Zend_Debug::dump($arrayLinks);
//		Zend_Debug::dump($arrayLinksCambiados);
    }
}//fin de la clase