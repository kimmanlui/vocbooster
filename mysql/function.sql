

DROP FUNCTION if exists top_dictation; 
//
DELIMITER //  
CREATE FUNCTION top_dictation(p_text varchar(50)) RETURNS varchar(50)
BEGIN
  DECLARE lastC  varchar(1);
  DECLARE ranking  varchar(50);
  DECLARE lastV varchar(50);
  DECLARE randkey varchar(50);
drop TEMPORARY  table if exists toptemp; 
CREATE temporary TABLE toptemp 
 SELECT @rn:=@rn+1 AS rank, userid, name ,  round(mark,1) mark,  round(@rn/total*100,1) top
FROM v_dictation_toplist_P2
  t1, (SELECT @rn:=0) t2 , users
  where userid=users.id;
 SET ranking = (select  top from toptemp where userid = p_text);  
  return ranking; 
END;
//

DROP FUNCTION if exists top_learnby; 
//
DELIMITER //  
CREATE FUNCTION top_learnby(p_text varchar(50)) RETURNS varchar(50)
BEGIN
  DECLARE lastC  varchar(1);
  DECLARE ranking  varchar(50);
  DECLARE lastV varchar(50);
  DECLARE randkey varchar(50);
drop TEMPORARY  table if exists toptempx; 
CREATE temporary TABLE toptempx 
 SELECT @rn:=@rn+1 AS rank, userid, name ,  round(mark,1) mark,  round(@rn/total*100,1) top
FROM v_learnby_toplist_P2
  t1, (SELECT @rn:=0) t2 , users
  where userid=users.id;

SET ranking = (select  top from toptempx where userid = p_text);  
  return ranking; 
END;
//


DROP FUNCTION if exists typename; 
//
DELIMITER //  
CREATE FUNCTION typename(p_text varchar(50)) RETURNS varchar(50)
BEGIN
   IF p_text = 'c' THEN return 'cambridge'; END IF; 
   IF p_text = 'v' THEN return 'voice-dictation'; END IF; 
   IF p_text = 'e' THEN return 'example'; END IF; 
   IF p_text = 'f' THEN return 'fill-in-blank'; END IF; 
   IF p_text = 'q' THEN return 'quiz'; END IF; 
   IF p_text = 'd' THEN return 'text-dictation'; END IF;
   IF p_text = 'flash' THEN return 'flash'; END IF;
   return 'unknown'; 
END;
//