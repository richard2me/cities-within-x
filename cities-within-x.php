<?php

/*

Cities Within X
Find zip codes within x miles of a given zip code

This script assumes that you have an SQL table
called "zip_coords", which contains these columns:
- zipcode
- city
- state
- latitude
- longitude

This should do the trick:
http://www.populardata.com/zipcode_database.html

*/

$zip = 94550; // "find nearby this zip code"
$radius = 15; // "search radius (miles)"
$maxresults = 10; // maximum number of results you'd like

$sql = "SELECT * FROM
	(SELECT o.zipcode, o.city, o.state,
		(3956 * (2 * ASIN(SQRT(
		POWER(SIN(((z.latitude-o.latitude)*0.017453293)/2),2) +
		COS(z.latitude*0.017453293) *
		COS(o.latitude*0.017453293) *
		POWER(SIN(((z.longitude-o.longitude)*0.017453293)/2),2)
		)))) AS distance
	FROM zip_coords z,
		zip_coords o,
		zip_coords a
	WHERE z.zipcode = ".(int)$zip." AND z.zipcode = a.zipcode AND
		(3956 * (2 * ASIN(SQRT(
		POWER(SIN(((z.latitude-o.latitude)*0.017453293)/2),2) +
		COS(z.latitude*0.017453293) *
		COS(o.latitude*0.017453293) *
		POWER(SIN(((z.longitude-o.longitude)*0.017453293)/2),2)
		)))) <= ".(int)$radius."
	ORDER BY distance)
	ORDER BY distance ASC LIMIT 0,".(int)$maxresults;

?>