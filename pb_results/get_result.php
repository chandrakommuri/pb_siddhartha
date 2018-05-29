<?php
	if(isset($_GET["rollno"]))
	{
		$rollno=$_GET["rollno"];
		require_once("pb_connect.php");
		require_once("result.php");
		require_once("course.php");
		require_once("semester.php");

		$result = new Result();

		$info_query = "select n.rollno rollno, n.name name, n.sect section, n.fname parent_gaurdian, n.sec_lan second_language from names n where rollno = '$rollno'";
		$info = mysql_fetch_assoc(mysql_query($info_query));

		$result->rollno = $info['rollno'];
		$result->name = $info['name'];
		$result->section = $info['section'];
		$result->parent_gaurdian = $info['parent_gaurdian'];
		$result->second_language = $info['second_language'];

		$results_query = "select 
							r.sem as semester, 
							c.ccode as course_code, 
							c.title as title, 
							(case r.tp when 'T' then 'Theory' when 'P' then 'Practical' when 'PW' then 'Project Work' else r.tp end) as type,
							r.ie1 as internal,
							r.se1 as external,
							(r.ie1 + r.se1) as total,
							(case r.tp when 'T' then ((r.ie1 + r.se1)/10) else ((r.ie1 + r.se1)/5) end) as grade_points,
							c.credits as credits,
							round((case r.tp when 'T' then ((r.ie1 + r.se1)/10) * c.credits else ((r.ie1 + r.se1)/5) * c.credits end), 2) as credit_points,
							r.grade as grade
							from 
								results r, 
								codes c 
							where 
								r.rollno='$rollno' and 
								r.ccode = c.ccode order by r.sem";

		$results = mysql_query($results_query);
		$result->semesters = array(); 

		$semester=null;
		$courses=null;
		while($row=mysql_fetch_assoc($results))
		{
			if($semester == null || $semester->number != $row['semester'])
			{
				$semester = new Semester($row['semester']);
				$semester->courses = array();
				$result->semesters[] = $semester;
			}
			$course = new Course();
			$course->semester = $row['semester'];
			$course->course_code = $row['course_code'];
			$course->title = $row['title'];
			$course->type = $row['type'];
			$course->internal = $row['internal'];
			$course->external = $row['external'];
			$course->total = $row['total'];
			$course->grade_points = $row['grade_points'];
			$course->credits = $row['credits'];
			$course->credit_points = $row['credit_points'];
			$course->grade = $row['grade'];
			$semester->courses[] = $course;
		}

		echo json_encode($result);
	}
?>