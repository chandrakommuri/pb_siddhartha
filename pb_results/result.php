<?php 
    class Result implements JsonSerializable
	{
		public $rollno;
		public $name;
		public $section;
        public $parent_gaurdian;
		public $second_language;
        public $semesters;
		
		public function jsonSerialize() {
            return get_object_vars($this);
        }
		
	}
?>