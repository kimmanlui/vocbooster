select sum(if(choice=1, 1, 0))/ count(1) mark,  'flash' type from all_log_v_student where type='flash'
union
select sum(if(choice=1, 1, 0))/ count(1) mark,  type from all_log_v_student where type='q'
union
select sum(if(choice=1, 1, 0))/ count(1) mark,  type from all_log_v_student where type='d'
union
select sum(if(choice=1, 1, 0))/ count(1) mark,  type from all_log_v_student where type='v'
union
select sum(if(choice=1, 1, 0))/ count(1) mark, type from all_log_v_student where type='f'
union
select sum(if(choice=1, 1, 0))/ count(1) mark,  type from all_log_v_student where type='c'
union
select sum(if(choice=1, 1, 0))/ count(1) mark, type from all_log_v_student where type='e';