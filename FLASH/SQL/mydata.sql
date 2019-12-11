



select SID,  DATE_FORMAT(birthday,'%Y%m%d'   )   , 
English_name 
from student_master_IEP_now where status="Tuition"

select 
SID,    
CAST( DATE_FORMAT(birthday,'%Y%m%d' ) AS char)   date  , 
English_name from student_master_IEP_now where status="Tuition"


select 
concat('insert into user_data (username, password, name) values (\'', SID, '\' , \'',
   CAST( DATE_FORMAT(birthday,'%Y%m%d' ) AS char)  ,  '\' , \'', 
   English_name , '\')' ) 
statement 
from student_master_IEP_now where status="Tuition"

select 
concat('insert into user_data (username, password, name) values (\'', Student_mobile, '\' , \'',
   CAST( DATE_FORMAT(birthday,'%m%d' ) AS char)  ,  '\' , \'', 
   English_name , '\')' ) 
statement 
from student_master_all where vocbooster="Y"
