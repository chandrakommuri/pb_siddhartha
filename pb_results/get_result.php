<?php
	if(isset($_GET["rollno"]))
	{
		$rollno=$_GET["rollno"];
		require_once("pb_connect.php");
		require_once("result.php");
		require_once("semester.php");
		require_once("part.php");
		require_once("course.php");

		$result = new Result();

		$info_query = "select n.rollno rollno, n.name name, n.sect section, n.fname parent_gaurdian, n.sec_lan second_language from names n where rollno = '$rollno'";
		$info_result = mysql_query($info_query);
		if(mysql_num_rows($info_result))
		{
			$info = mysql_fetch_assoc($info_result);

			$result->rollno = $info['rollno'];
			$result->name = $info['name'];
			$result->section = $info['section'];
			$result->parent_gaurdian = $info['parent_gaurdian'];
			$result->second_language = $info['second_language'];

			$results_query = "select 
								r.sem as semester, 
								c.ccode as course_code, 
								c.title as title, 
								(case r.tp 
									when 'T' then 'Theory' 
									when 'P' then 'Practical' 
									when 'PW' then 'Project Work' 
									when 'C' then 'Extra Curricular' 
									when 'PW' then 'Diversified' 
									else r.tp end) as type,
								(case 
									when  c.ccode like 'ENG%' or c.ccode like 'HIN%' or c.ccode like 'TEL%' then '1' 
									when  c.ccode not like 'ENG%' and c.ccode not like 'HIN%' and c.ccode not like 'TEL%' and c.ccode not like 'AEC%' and c.ccode not like 'AOC%' then '2'
									when  c.ccode like 'AEC%' or c.ccode like 'AOC%' then '3'
									end) part,
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
			$parts=null;
			while($row=mysql_fetch_assoc($results))
			{
				if($semester == null || $semester->number != $row['semester'])
				{
					$semester = new Semester($row['semester']);
					$semester->parts = array();
					$result->semesters[] = $semester;
				}
				if($semester->parts[$row['part']] == null)
				{
					$semester->parts[$row['part']] = new Part($row['part']);
					$semester->parts[$row['part']]->courses = array();
				}

				$course = $semester->parts[$row['part']]->courses[$row['course_code']];
				if($course == null)
				{
					$course = new Course();
					$course->semester = $row['semester'];
					$course->course_code = $row['course_code'];
					$course->title = $row['title'];
					$course->type = $row['type'];
					$course->part = $row['part'];
					$course->internal = $row['internal'];
					$course->external = $row['external'];
					$course->total = $row['total'];
					$course->grade_points = $row['grade_points'];
					$course->credits = $row['credits'];
					$course->credit_points = $row['credit_points'];
					$course->grade = $row['grade'];
					
					$semester->parts[$course->part]->courses[$course->course_code] = $course;
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
					if($course->type == 'Theory')
					{
						$course->grade_points = $course->total / 10;
					}
					else
					{
						$course->grade_points = $course->total / 5;
					}
					$course->credit_points = $course->grade_points * $course->credits;
				}
				
			}

			echo json_encode($result);
		}
		else
		{
			echo "[]";
		}
	}
?>