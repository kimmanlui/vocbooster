

/* **************************************************************** */ 

DROP view IF EXISTS `general_flashcards`;
create view general_flashcards as 
select cardid,  name, front, back, dictionary, flashcards.enable, cefr, audiob64 from flashcards, decks
where decks.deckid=flashcards.deckid;

DROP view IF EXISTS `users`;
create view users as select * from users_data where enable='1' and role='s'; 

DROP view IF EXISTS `decks_v`;
create view decks_v as
select * from decks where enable='1';


DROP view IF EXISTS `flashcards_v`;
create view flashcards_v as
select * from flashcards where enable='1' and trim(back)!='NA';

DROP view IF EXISTS `log_v_student`;
create view log_v_student as
select * from log where word!='[No Cards]' and userid in (select id from users);


DROP view IF EXISTS `quiz_v`;
create view quiz_v as 
(select m.deckid, m.front as q,  m.back as ans,
(select a.back from flashcards_v a   where  a.deckid = m.deckid  and m.back!=a.back ORDER BY RAND() limit 1) as R1, 
(select b.back from flashcards_v b   where  b.deckid = m.deckid  and m.back!=b.back  ORDER BY RAND() limit 1) as R2,
(select c.back from flashcards_v c   where  c.deckid = m.deckid  and m.back!=c.back  ORDER BY RAND() limit 1) as R3,
(select d.back from flashcards_v d   where  d.deckid = m.deckid  and m.back!=d.back  ORDER BY RAND() limit 1) as R4,
(select e.back from flashcards_v e   where  e.deckid = m.deckid  and m.back!=e.back  ORDER BY RAND() limit 1) as R5
from flashcards m 
where m.deckid in (select  distinct deckid   from flashcards_v  group by deckid having  max(LENGTH(trim(back))) <30)
 ) union
 (
 select m.deckid, m.back as q, m.front as ans,
(select a.front from flashcards_v a   where  a.deckid = m.deckid  and m.front!=a.front ORDER BY RAND() limit 1) as R1, 
(select b.front from flashcards_v b   where  b.deckid = m.deckid  and m.front!=b.front  ORDER BY RAND() limit 1) as R2,
(select c.front from flashcards_v c   where  c.deckid = m.deckid  and m.front!=c.front  ORDER BY RAND() limit 1) as R3,
(select d.front from flashcards_v d   where  d.deckid = m.deckid  and m.front!=d.front  ORDER BY RAND() limit 1) as R4,
(select e.front from flashcards_v e   where  e.deckid = m.deckid  and m.front!=e.front  ORDER BY RAND() limit 1) as R5
from flashcards m
where m.deckid in (select  distinct deckid   from flashcards_v  group by deckid having  max(LENGTH(trim(back))) <30)
 )   ORDER BY RAND() limit 100;



DROP view IF EXISTS `quiz_v_old`;
create view quiz_v_old as 
(select m.deckid, m.front as q,  m.back as ans,
(select a.back from flashcards a   where  a.deckid = m.deckid  and m.back!=a.back ORDER BY RAND() limit 1) as R1, 
(select b.back from flashcards b   where  b.deckid = m.deckid  and m.back!=b.back  ORDER BY RAND() limit 1) as R2,
(select c.back from flashcards c   where  c.deckid = m.deckid  and m.back!=c.back  ORDER BY RAND() limit 1) as R3,
(select d.back from flashcards d   where  d.deckid = m.deckid  and m.back!=d.back  ORDER BY RAND() limit 1) as R4,
(select e.back from flashcards e   where  e.deckid = m.deckid  and m.back!=e.back  ORDER BY RAND() limit 1) as R5
from flashcards m 
where m.deckid in (select  distinct deckid   from flashcards  group by deckid having  max(LENGTH(trim(back))) <30)
 ) union
 (
 select m.deckid, m.back as q, m.front as ans,
(select a.front from flashcards a   where  a.deckid = m.deckid  and m.front!=a.front ORDER BY RAND() limit 1) as R1, 
(select b.front from flashcards b   where  b.deckid = m.deckid  and m.front!=b.front  ORDER BY RAND() limit 1) as R2,
(select c.front from flashcards c   where  c.deckid = m.deckid  and m.front!=c.front  ORDER BY RAND() limit 1) as R3,
(select d.front from flashcards d   where  d.deckid = m.deckid  and m.front!=d.front  ORDER BY RAND() limit 1) as R4,
(select e.front from flashcards e   where  e.deckid = m.deckid  and m.front!=e.front  ORDER BY RAND() limit 1) as R5
from flashcards m
where m.deckid in (select  distinct deckid   from flashcards  group by deckid having  max(LENGTH(trim(back))) <30)
 )   ORDER BY RAND() limit 100;

 
DROP view IF EXISTS `quiz_back_v`;
create view quiz_back_v as 
 select m.deckid, m.back as q, m.front as ans,
(select a.front from flashcards a   where  a.deckid = m.deckid  and m.front!=a.front ORDER BY RAND() limit 1) as R1, 
(select b.front from flashcards b   where  b.deckid = m.deckid  and m.front!=b.front  ORDER BY RAND() limit 1) as R2,
(select c.front from flashcards c   where  c.deckid = m.deckid  and m.front!=c.front  ORDER BY RAND() limit 1) as R3,
(select d.front from flashcards d   where  d.deckid = m.deckid  and m.front!=d.front  ORDER BY RAND() limit 1) as R4,
(select e.front from flashcards e   where  e.deckid = m.deckid  and m.front!=e.front  ORDER BY RAND() limit 1) as R5
from flashcards m
where m.deckid in (select  distinct deckid   from flashcards  group by deckid having  max(LENGTH(trim(back))) <30)
   ORDER BY RAND() limit 100;


drop view if exists all_log_v_student;

create view all_log_v_student as 
select * from quizlog where !(userid=2 or userid=3)
union 
select id, userid, word q , word a, word s, choice, created_d, deckid d, sid, 'flash' type from log_v_student
where sid is not null and   !(userid=2 or userid=3);



DROP view IF EXISTS `quizlog_v_student`;
create view quizlog_v_student as
select * from quizlog where !(userid=2 or userid=3);


drop   view IF EXISTS v_student_performance;
create view v_student_performance as 
select  userid, word , sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning
 from log_v_student group by userid, word;

drop   view IF EXISTS v_student_performance_wk;
create view   v_student_performance_wk as 
select  userid, word , sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning, week(created_d) wk
 from log_v_student group by userid, word, wk;
 
drop   view IF EXISTS v_deckoptionrpt;
create view v_deckoptionrpt as 
  SELECT decks.deckid,name,count(*) AS cards ,  decks.created_d, decks.enable
  FROM decks JOIN flashcards ON decks.deckid = flashcards.deckid
  GROUP BY decks.deckid order by decks.deckid desc;
  
drop   view IF EXISTS v_student_performance_quiz;  
create view v_student_performance_quiz as 
select  userid, q word , sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning
 from quizlog_v_student where type='q'  group by userid, word;
 
 
 drop function IF EXISTS  wordcount;
  
DELIMITER $$
CREATE FUNCTION wordcount(str TEXT)
       RETURNS INT
       DETERMINISTIC
       SQL SECURITY INVOKER
       NO SQL
  BEGIN
    DECLARE wordCnt, idx, maxIdx INT DEFAULT 0;
    DECLARE currChar, prevChar BOOL DEFAULT 0;
    SET maxIdx=char_length(str);
    WHILE idx < maxIdx DO
        SET currChar=SUBSTRING(str, idx, 1) RLIKE '[[:alnum:]]';
        IF NOT prevChar AND currChar THEN
            SET wordCnt=wordCnt+1;
        END IF;
        SET prevChar=currChar;
        SET idx=idx+1;
    END WHILE;
    RETURN wordCnt;
  END
$$
DELIMITER ;  

drop view IF EXISTS flashcards_v_eg;  
create view flashcards_v_eg as 
select * from flashcards_v where !isnull(numeg) and numeg>0   ;
 
drop view IF EXISTS flashcards_v_dictionary;  
create view flashcards_v_dictionary as 
select * from flashcards_v where !isnull(dictionary)  ; 

drop view IF EXISTS flashcards_cefr_v;
create view flashcards_cefr_v as 
select * from flashcards where cefr !='' and  cefr !='N' ;


drop   view IF EXISTS v_dictation_performance;
create view v_dictation_performance as 
select  userid, a word, sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning
 from quizlog_v_student where type='d' or type='v' or type='f' group by userid, word;
 
drop   view IF EXISTS v_dictation_performance_wk;
create view   v_dictation_performance_wk as 
select  userid, a word , sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning, week(created_d) wk
 from quizlog_v_student where type='d' or type='v' or type='f' group by userid, word, wk; 

drop   view IF EXISTS v_quiz_performance;
create view v_quiz_performance as 
select  userid, a word, sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning
 from quizlog_v_student where type='q' group by userid, word;
 
drop   view IF EXISTS v_quiz_performance_wk;
create view   v_quiz_performance_wk as 
select  userid, a word , sum( if (choice=1, 1, 0)) correct , sum(1) total , round(sum( if (choice=1, 1, 0))/ sum(1),2) learning, week(created_d) wk
 from quizlog_v_student where type='q' group by userid, word, wk;  



drop   view IF EXISTS v_activity;
create view   v_activity as
(select concat('ID=',users.id ,'&created_d=',max(created_d),'&type=',type )  hideP, name , round(SUM(IF(choice = '1', 1 , 0) )/ count(choice)*100,0) as mark, max(created_d )  Date, SUBSTRING(DayName( max(created_d)),1,3) day , type
            from quizlog_v_student, users where userid = users.id  group by sid, username 
)
union
(select concat('ID=',users.id ,'&created_d=',max(created_d),'&type=flash' )  hideP, name , round(SUM(IF(choice = '1', 1 , 0) )/ count(choice)*100,0) as mark, max(created_d) Date, SUBSTRING(DayName( max(created_d)),1,3) day , 'flash' type
            from log_v_student, users where !isnull(sid) and userid = users.id  group by sid, username 
) 
union
(select concat('ID=',users.id ,'&created_d=',max(created_d),'&type=flash' )  hideP, name , round(SUM(IF(choice = '1', 1 , 0) )/ count(choice)*100,0) as mark, max(created_d) Date, SUBSTRING(DayName( max(created_d)),1,3) day , 'flash' type
            from log_v_student, users where isnull(sid) and userid = users.id  group by substring(created_d, 1, 13), username 
) 
order by Date desc;  
 

drop   view IF EXISTS v_activity_with_id;
create view   v_activity_with_id as
(select concat('ID=',users.id ,'&created_d=',max(created_d),'&type=',type )  hideP, users.id ,name , round(SUM(IF(choice = '1', 1 , 0) )/ count(choice)*100,0) as mark, max(created_d )  Date, SUBSTRING(DayName( max(created_d)),1,3) day , type
            from quizlog_v_student, users where userid = users.id  group by sid, username 
)
union
(select concat('ID=',users.id ,'&created_d=',max(created_d),'&type=flash' )  hideP, users.id , name , round(SUM(IF(choice = '1', 1 , 0) )/ count(choice)*100,0) as mark, max(created_d) Date, SUBSTRING(DayName( max(created_d)),1,3) day , 'flash' type
            from log_v_student, users where !isnull(sid) and userid = users.id  group by sid, username 
) 
union
(select concat('ID=',users.id ,'&created_d=',max(created_d),'&type=flash' )  hideP, users.id , name , round(SUM(IF(choice = '1', 1 , 0) )/ count(choice)*100,0) as mark, max(created_d) Date, SUBSTRING(DayName( max(created_d)),1,3) day , 'flash' type
            from log_v_student, users where isnull(sid) and userid = users.id  group by substring(created_d, 1, 13), username 
) 
order by Date desc;   


drop   view IF EXISTS v_dictation_toplist_P1;
CREATE VIEW v_dictation_toplist_p1 AS
    SELECT 
        userid ,
        SUM(choice) AS mark
    FROM
        quizlog
    WHERE
        userid IN (SELECT id FROM users)
            AND type in ('d', 'v','f') 
	group by userid order by mark desc;
 

 
drop   view IF EXISTS v_dictation_toplist_P2; 
create view v_dictation_toplist_P2 as
select * , (select count(*) from v_dictation_toplist_P1) as total  from v_dictation_toplist_P1;


drop   view IF EXISTS v_learnby_toplist_P1;
CREATE VIEW v_learnby_toplist_p1 AS
    SELECT 
        userid ,
        SUM(if(choice=1, 1, -0.75)) AS mark
    FROM
        quizlog
    WHERE
        userid IN (SELECT id FROM users)
            AND type in ('c','e') 
	group by userid order by mark desc;

drop   view IF EXISTS v_learnby_toplist_P2; 
create view v_learnby_toplist_P2 as
select * , (select count(*) from v_learnby_toplist_P1) as total  from v_learnby_toplist_P1;

 
-- SELECT @rn:=@rn+1 AS rank, userid, name ,  round(mark,1) mark, total, round(@rn/total*100,1) top
-- FROM v_dictation_toplist_P2
--  t1, (SELECT @rn:=0) t2 , users
--  where userid=users.id

drop   view IF EXISTS quiz_dictionary_back_v; 
CREATE VIEW quiz_dictionary_back_v AS
    SELECT 
        m.deckid AS deckid,
        m.dictionary AS q,
        m.front AS ans,
        (SELECT 
                a.front
            FROM
                flashcards_v a
            WHERE
                ((a.deckid = m.deckid)
                    AND (m.front <> a.front))
            ORDER BY RAND()
            LIMIT 1) AS R1,
        (SELECT 
                b.front
            FROM
                flashcards_v b
            WHERE
                ((b.deckid = m.deckid)
                    AND (m.front <> b.front))
            ORDER BY RAND()
            LIMIT 1) AS R2,
        (SELECT 
                c.front
            FROM
                flashcards_v c
            WHERE
                ((c.deckid = m.deckid)
                    AND (m.front <> c.front))
            ORDER BY RAND()
            LIMIT 1) AS R3,
        (SELECT 
                d.front
            FROM
                flashcards_v d
            WHERE
                ((d.deckid = m.deckid)
                    AND (m.front <> d.front))
            ORDER BY RAND()
            LIMIT 1) AS R4,
        (SELECT 
                e.front
            FROM
                flashcards_v e
            WHERE
                ((e.deckid = m.deckid)
                    AND (m.front <> e.front))
            ORDER BY RAND()
            LIMIT 1) AS R5
    FROM
        flashcards_v_dictionary m
    WHERE
        m.deckid IN (SELECT DISTINCT
               flashcards_v.deckid
            FROM
                flashcards_v
            GROUP BY flashcards_v.deckid
            HAVING (MAX(LENGTH(TRIM(flashcards_v.back))) < 30))
    ORDER BY RAND()
    LIMIT 100;


	
drop   view IF EXISTS quiz_eg_back_v_easy; 
CREATE VIEW quiz_eg_back_v_easy  AS
    SELECT 
        m.deckid AS deckid,
        m.eg1 AS q,
        m.front AS ans,
        (SELECT 
                a.front
            FROM
                flashcards_v a
            WHERE
                ((a.deckid != m.deckid)
                    AND (m.front <> a.front))
            ORDER BY RAND()
            LIMIT 1) AS R1,
        (SELECT 
                b.front
            FROM
                flashcards_v b
            WHERE
                ((b.deckid != m.deckid)
                    AND (m.front <> b.front))
            ORDER BY RAND()
            LIMIT 1) AS R2,
        (SELECT 
                c.front
            FROM
                flashcards_v c
            WHERE
                ((c.deckid != m.deckid)
                    AND (m.front <> c.front))
            ORDER BY RAND()
            LIMIT 1) AS R3,
        (SELECT 
                d.front
            FROM
                flashcards_v d
            WHERE
                ((d.deckid != m.deckid)
                    AND (m.front <> d.front))
            ORDER BY RAND()
            LIMIT 1) AS R4,
        (SELECT 
                e.front
            FROM
                flashcards_v e
            WHERE
                ((e.deckid != m.deckid)
                    AND (m.front <> e.front))
            ORDER BY RAND()
            LIMIT 1) AS R5
    FROM
        flashcards_v_eg m
    WHERE
        m.deckid IN (SELECT DISTINCT
                flashcards_v.deckid
            FROM
                flashcards_v
            GROUP BY flashcards_v.deckid
            HAVING (MAX(LENGTH(TRIM(flashcards_v.back))) < 30))
    ORDER BY RAND()
    LIMIT 100;



	

drop   view IF EXISTS quiz_eg_back_v; 
CREATE VIEW quiz_eg_back_v AS
    SELECT 
        m.deckid AS deckid,
        m.eg1 AS q,
        m.front AS ans,
        (SELECT 
                a.front
            FROM
                flashcards_v a
            WHERE
                ((a.deckid = m.deckid)
                    AND (m.front <> a.front))
            ORDER BY RAND()
            LIMIT 1) AS R1,
        (SELECT 
                b.front
            FROM
                flashcards_v b
            WHERE
                ((b.deckid = m.deckid)
                    AND (m.front <> b.front))
            ORDER BY RAND()
            LIMIT 1) AS R2,
        (SELECT 
                c.front
            FROM
                flashcards_v c
            WHERE
                ((c.deckid = m.deckid)
                    AND (m.front <> c.front))
            ORDER BY RAND()
            LIMIT 1) AS R3,
        (SELECT 
                d.front
            FROM
                flashcards_v d
            WHERE
                ((d.deckid = m.deckid)
                    AND (m.front <> d.front))
            ORDER BY RAND()
            LIMIT 1) AS R4,
        (SELECT 
                e.front
            FROM
                flashcards_v e
            WHERE
                ((e.deckid = m.deckid)
                    AND (m.front <> e.front))
            ORDER BY RAND()
            LIMIT 1) AS R5
    FROM
        flashcards_v_eg m
    WHERE
        m.deckid IN (SELECT DISTINCT
                flashcards_v.deckid
            FROM
                flashcards_v
            GROUP BY flashcards_v.deckid
            HAVING (MAX(LENGTH(TRIM(flashcards_v.back))) < 30))
    ORDER BY RAND()
    LIMIT 100;





drop view activity_v;
 create view activity_v as 
              (select concat('ID=',users.id ,'&created_d=',max(created_d),'&type=',type )  hideP, name , round(SUM(IF(choice = '1', 1 , 0) )/ count(choice)*100,0) as mark, 
                max(created_d )  Date,  week(max(created_d ))  Week, SUBSTRING(DayName( max(created_d)),1,3) day , type, userid
                from quizlog_v_student, users where userid = users.id  group by sid, username 
              )
              union
              ( select concat('ID=',users.id ,'&created_d=',max(created_d),'&type=flash')  hideP, name , 
                round(SUM(IF(choice = '1', 1 , 0) )/ count(choice)*100,0) as mark, max(created_d )  Date, week(max(created_d ))  Week, 
                SUBSTRING(DayName( max(created_d)),1,3) day , 'flash' type, userid
                from log_v_student, users where !isnull(sid) and userid = users.id  group by sid, username 
              )
              union
              (select concat('ID=',users.id ,'&created_d=',max(created_d),'&type=flash' )  hideP, name , round(SUM(IF(choice = '1', 1 , 0) )/ count(choice)*100,0) as mark, 
               max(created_d) Date, week(max(created_d ))  Week, SUBSTRING(DayName( max(created_d)),1,3) day , 'flash' type, userid
                from log_v_student, users where isnull(sid) and userid = users.id  group by substring(created_d, 1, 13), username 
              )

/* **************************************************************** */ 
