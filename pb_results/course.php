<?php 
    class Course implements JsonSerializable
	{
        public $semester;
        public $course_code;
        public $title;
        public $type;
        public $month_year;
        public $part;
        public $internal = 0;
        public $external = 0;
        public $total = 0;
        public $grade_points = 0;
        public $credits = 0;
        public $credit_points = 0;
        public $grade;
        public $internal_pass = 'F';
        public $external_pass = 'F';
        public $pass = 'F';
		
		public function jsonSerialize() {
            return get_object_vars($this);
        }
		
	}
?>