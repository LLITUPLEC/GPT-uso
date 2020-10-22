<?php
/* @var $this yii\web\View */

$this->title = 'Второе задание';
?>
<div>
    <h5>Очевидно, что для данной задачи нам потребуется временная таблица(хотя можно создать и постоянную)</h5>
    <pre>
DROP   //удаляем хранимую процеру, если она уже существует
PROCEDURE IF EXISTS create_temp_table;
DELIMITER
    //
CREATE PROCEDURE create_temp_table(
    IN begin_date DATE,
    IN end_date DATE
) DETERMINISTIC
BEGIN
    SET
        @last_date = begin_date ;
    DROP TABLE IF EXISTS
        temp_calendar ;
    CREATE TEMPORARY TABLE temp_calendar( //создаём временную таблицу (можно было и просто через #)
        DATE DATE NOT NULL,
        KEY days(DATE)
    ) ENGINE = innoDB ; WHILE @last_date <= end_date DO
    INSERT INTO temp_calendar(DATE)
VALUES(@last_date) ;
SET
    @last_date = DATE_ADD(@last_date, INTERVAL 1 DAY) ;
END WHILE ;
END //
CALL
    create_temp_table('2019-10-01', '2019-10-20') ;
SELECT
    temp_calendar.date,
    IF(
        day_price.price IS NULL,
        'цена по-умолчанию',
        'особая цена'
    ) AS price_type,
    IF(
        day_price.price IS NULL,
        (
        SELECT
            default_price.price
        FROM
            default_price
        WHERE
            default_price.id = 53
    ),
    day_price.price
    ) AS price
FROM
    temp_calendar
LEFT JOIN day_price ON(
        temp_calendar.`date` = day_price.`date` AND day_price.id = 53 AND day_price.date BETWEEN '2019-10-01' AND '2019-10-20'
    ),
    default_price
WHERE
    default_price.id = 53
ORDER BY
    temp_calendar.date ASC ;
    </pre>
</div>
