<?php 
    class Semester implements JsonSerializable
	{
		public $number;
		public $courses;

		public function __construct($number)
		{
			$this->number = $number;
		}
		
		public function jsonSerialize() {
            return get_object_vars($this);
        }
	}
?>