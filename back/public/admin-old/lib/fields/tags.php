<?

final class tags extends field{

	function view(){
			}
	function bake_field (){
					echo "<div class='control-group'><label class='control-label'>";
							echo formatear_primera_may($this->ninjaTabla->labels[$i]);
							echo "</label><div class='controls'>";
							echo "(Separats per comes i sense espais)";
							echo "<br>Tags existents: ";				
							$data = mysql_query("SELECT tags FROM ".$this->ninjaTabla->tabla);
							$LIST = array();
							while ($camp_tag = mysql_fetch_array($data)) { 
							if (strstr($camp_tag['tags'],",")){
							$aux = explode(",",$camp_tag['tags']);
							for ($ie = 0;$ie < count($aux);$ie++) if ($aux[$ie] != "") $LIST[] = trim($aux[$ie]);
							} else {
							$LIST[] = trim($camp_tag['tags']);
							}
							}
							$PUNTS = array();
							for ($ie = 0;$ie < count($LIST);$ie++){
								if ($LIST[$ie] != ""){
									if (!isset($PUNTS[$LIST[$ie]])) $PUNTS[$LIST[$ie]]=1;
									else  $PUNTS[$LIST[$ie]]++;
								}
							}
							arsort($PUNTS);
							
							
							$y = 0;
							foreach($PUNTS as $key => $value){
							if ($y > 0) echo ", ";
			                       echo "<a href='javascript:click_tag(\"$key\");'>$key</a> ";
                       
		                       $y++;
               			    }							
							echo "<br>";
							
							
							
							
							
							
					echo "<input class=\"text-input medium-input\" type=\"text\" cols=\"120\" name=\"".$this->ninjaTabla->fields[$i]."\" id=\"".$this->ninjaTabla->fields[$i]."\" value=\"".trim($raw[$this->ninjaTabla->fields[$i]])."\">"; echo "</div></div>";	
	}
		
	function exec_add () {
		if (substr($this->value,strlen($this->value)-1,strlen($this->value)) == ',') $this->value = substr($this->value,0,strlen($this->value)-1);
		return addslashes(stripslashes($this->value)); 
	}
	function exec_edit () {
		if (substr($this->value,strlen($this->value)-1,strlen($this->value)) == ',') $this->value = substr($this->value,0,strlen($this->value)-1);
						return addslashes(stripslashes($this->value));
		return ''; 
	}

}

