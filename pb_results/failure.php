<?php 
	require_once("course.php");
    class Failure extends Course
	{
        public $rollno;
		
		public function jsonSerialize() {
            return get_object_vars($this);
        }
		
	}
?>