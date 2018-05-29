<?php 
    class Course implements JsonSerializable
	{
        public $semester;
        public $course_code;
        public $title;
        public $type;
        public $internal;
        public $external;
        public $total;
        public $grade_points;
        public $credits;
        public $credit_points;
        public $grade;
		
		public function jsonSerialize() {
            return get_object_vars($this);
        }
		
	}
?>