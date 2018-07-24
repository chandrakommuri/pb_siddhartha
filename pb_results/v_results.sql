create view v_results as (select 
								r.sect as section,
								r.rollno as rollno,
								r.sem as semester, 
								c.ccode as course_code, 
								c.title as title, 
								(case r.tp 
									when 'T' then 'Theory' 
									when 'P' then 'Practical' 
									when 'PW' then 'Project Work' 
									when 'C' then 'Extra Curricular' 
									when 'D' then 'Diversified' 
									else r.tp end) as type,
								r.my as month_year,
								(case 
									when  c.ccode like 'ENG%' or c.ccode like 'HIN%' or c.ccode like 'TEL%' then '1' 
									when  c.ccode not like 'ENG%' and c.ccode not like 'HIN%' and c.ccode not like 'TEL%' and c.ccode not like 'AEC%' and c.ccode not like 'AOC%' and r.tp not like 'C' and r.tp not like 'D' then '2'
									when  c.ccode like 'AEC%' or c.ccode like 'AOC%' then '3'
									when  r.tp like 'C' or r.tp like 'D' then '4'
									end) as part,
								r.ie1 as internal,
								r.se1 as external,
								(r.ie1 + r.se1) as total,
								(case 
									when  (c.ccode like 'AEC%' or c.ccode like 'AOC%' or c.ccode like 'FRE%' or r.tp like 'P') and r.ie1 >= 4 then 'P'
									when  (c.ccode not like 'AEC%' and c.ccode not like 'AOC%' and c.ccode not like 'FRE%' and r.tp like 'T') and r.ie1 >= 10 then 'P'
									else 'F' end) as internal_pass,
								(case 
									when  (c.ccode like 'AEC%' or c.ccode like 'AOC%' or c.ccode like 'FRE%' or r.tp like 'P') and r.se1 >= 16 then 'P'
									when  (c.ccode not like 'AEC%' and c.ccode not like 'AOC%' and c.ccode not like 'FRE%' and r.tp like 'T') and r.se1 >= 30 then 'P'
									else 'F' end) as external_pass,
								(case 
									when  (c.ccode like 'AEC%' or c.ccode like 'AOC%' or c.ccode like 'FRE%' or r.tp like 'P') and r.ie1 >= 4 and r.se1 >= 16 then 'P'
									when  (c.ccode not like 'AEC%' and c.ccode not like 'AOC%' and c.ccode not like 'FRE%' and r.tp like 'T') and r.ie1 >= 10 and r.se1 >= 30 then 'P'
									else 'F' end) as pass,
								round((case r.tp when 'T' then ((r.ie1 + r.se1)/10) else ((r.ie1 + r.se1)/5) end), 2) as grade_points,
								r.credits as credits,
								round((case r.tp when 'T' then ((r.ie1 + r.se1)/10) * r.credits else ((r.ie1 + r.se1)/5) * r.credits end), 2) as credit_points,
								r.grade as grade
								from 
									results r, 
									codes c 
								where 
									r.ccode = c.ccode 
								order by r.rollno, r.sem, r.ord)