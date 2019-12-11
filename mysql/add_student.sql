


select concat('insert into users_data (username, password, name) values (\'',student_mobile, '\', \'', DATE_FORMAT(birthday, ' %Y%m%d') , '\', \'',  English_Name ,'\');')   as s from student_master_all where ID=4832

select concat('insert into users_data (username, password, name) values (\'',student_mobile, '\', \'', DATE_FORMAT(birthday, '%Y%m%d') , '\', \'',  English_Name ,'\');')   as s 
from student_master_all where  vocbooster='Y'
