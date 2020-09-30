<?php
    function getPhrase($language) {
        try {
            global $connection;
            $query = "Select 
                A.seqNo,
                A.book, 
                (CASE WHEN '$language' = 'ko' THEN B.fullname_ko ELSE B.fullname_en END) as bookTitle,
                A.chapter,
                A.versesText verses,
                (CASE WHEN '$language' = 'ko' THEN C.content ELSE D.content END) as content
            from 
                (Select
                    bible_phrase_seq_no seqNo,
                    book, 
                    chapter, 
                    verses versesText,
                    SUBSTRING_INDEX(SUBSTRING_INDEX(tbBiblePhrase.verses, '-', numbers.n), '-', -1) verses
              from
                (select 1 n union all
                 select 2 union all select 3 union all
                 select 4 union all select 5) numbers INNER JOIN (select * from tbBiblePhrase where active = 'y' order by rand() limit 1) tbBiblePhrase
                on CHAR_LENGTH(tbBiblePhrase.verses)
                   -CHAR_LENGTH(REPLACE(tbBiblePhrase.verses, '-', ''))>=numbers.n-1
                    
              order by
                verses, n) A
            join bibleBooks B
                on A.book = B.short
            join bible_korHRV C 
                on B.number = C.book
            join bible_kjv D
                on B.number = D.book
                and C.chapter = A.chapter
                and C.verse = A.verses
                and D.chapter = C.chapter
                and D.verse = C.verse";
            $result = mysqli_query($connection, $query);

            if($result == false) {
                echo "error: " . mysqli_error($connection);
            }
            return $result;
        }
        catch(PDOException $ex) {
            return "Fail : ".$ex->getMessage()."<br>";
        }
    }

?>