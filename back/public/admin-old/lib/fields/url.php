<?

final class url extends field{

	function view(){
		return "<a href='".$this->value."' target='_blank'>".$this->value."</a>";
	}
	function bake_field (){
		return "<input  type=\"text\" class='span3' name=\"".$this->fieldname."\" id=\"".$this->fieldname."\" value=\"".trim($this->value)."\">";

		
	}
		
	function exec_add () {
		return $this->value;
	}
	function exec_edit () {
		return $this->value;	
	}

}

