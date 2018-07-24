<?php
	if(isset($_GET["section"]) && isset($_GET["batch"]))
	{
		$section=$_GET["section"];
		$batch=substr($_GET["batch"],2);
		require_once("pb_connect.php");
		require_once("course.php");

		$failed_courses_query = "select * from v_results where type not like 'Extra Curricular' and section like '$section' and rollno like '$batch%'";
		//echo $failed_courses_query;
		$failed_courses_result = mysql_query($failed_courses_query);
		
		$failed_courses = array();
		$passed_courses = array();
		while($row=mysql_fetch_assoc($failed_courses_result))
		{
			if($failed_courses[$row['rollno']] == null)
			{
				$failed_courses[$row['rollno']] = array();
			}
			if($passed_courses[$row['rollno']] == null)
			{
				$passed_courses[$row['rollno']] = array();
			}
			if($passed_courses[$row['rollno']][$row['course_code']] != null)
			{
				continue;
			}
			$course = $failed_courses[$row['rollno']][$row['course_code']];
			if($course == null)
			{
				$course = new Course();
				$course->semester = $row['semester'];
				$course->course_code = $row['course_code'];
				$course->title = $row['title'];
				$course->type = $row['type'];
				$course->month_year = $row['month_year'];
				$course->part = $row['part'];
				$course->internal = $row['internal'];
				$course->external = $row['external'];
				$course->total = $row['total'];
				$course->internal_pass = $row['internal_pass'];
				$course->external_pass = $row['external_pass'];
				$course->pass = $row['pass'];
				
				if($course->pass == 'F')
				{
					$failed_courses[$row['rollno']][$row['course_code']] = $course;
				}
				else
				{
					$passed_courses[$row['rollno']][$row['course_code']] = $course;
				}
			}
			else
			{
				if($course->internal < $row['internal'])
				{
					$course->internal = $row['internal'];
				}
				if($course->external < $row['external'])
				{
					$course->external = $row['external'];
				}
				
				$course->total = $course->internal + $course->external;
				if($course->part == '3' || $course->type == 'Practical' || substr($course->course_code,0,3) == 'FRE')
				{
					if($course->internal >= 4)
					{
						$course->internal_pass = 'P';
					}
					if($course->external >= 16)
					{
						$course->external_pass = 'P';
					}
				}
				else if($course->part != '4')
				{
					if($course->internal >= 10)
					{
						$course->internal_pass = 'P';
					}
					if($course->external >= 30)
					{
						$course->external_pass = 'P';
					}
				}

				if($course->internal_pass == 'P' && $course->external_pass == 'P')
				{
					unset($failed_courses[$row['rollno']][$row['course_code']]);
					$passed_courses[$row['rollno']][$row['course_code']] = $course;
				}
			}
		}
		
		echo json_encode($failed_courses);
	}
?>