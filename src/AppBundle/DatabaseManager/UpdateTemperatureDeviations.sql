UPDATE forecasts AS t1,
    (SELECT 
        SUM(t_dev_low) AS t_dev_low,
            SUM(t_dev_high) AS t_dev_high,
            id AS id
    FROM
        (SELECT 
        AVG(fc.temperature_low - t.temperature) AS t_dev_low,
            0 AS t_dev_high,
            fc.id
    FROM
        forecasts AS fc
    JOIN temperatures AS t ON (fc.city_id = t.city_id
        AND fc.provider_id = t.provider_id
        AND DATE_ADD(fc.forecast_date, INTERVAL fc.forecast_days DAY) = DATE(t.date))
    WHERE
        fc.forecast_date < '2016-04-01'
            AND DATE_FORMAT(t.date, '%H:%i:%s') NOT BETWEEN '09:00:00' AND '22:00:00'
    GROUP BY fc.id UNION ALL SELECT 
        0 AS t_dev_low,
            AVG(fc.temperature_high - t.temperature) AS t_dev_high,
            fc.id
    FROM
        forecasts AS fc
    JOIN temperatures AS t ON (fc.city_id = t.city_id
        AND fc.provider_id = t.provider_id
        AND DATE_ADD(fc.forecast_date, INTERVAL fc.forecast_days DAY) = DATE(t.date))
    WHERE
        fc.forecast_date < '2016-04-01'
            AND DATE_FORMAT(t.date, '%H:%i:%s') BETWEEN '09:00:00' AND '22:00:00'
    GROUP BY fc.id) AS union_table
    GROUP BY union_table.id) AS t2 
SET 
    t1.temperature_high_deviation = t2.t_dev_high,
    t1.temperature_low_deviation = t2.t_dev_low
WHERE
    t1.id = t2.id;

