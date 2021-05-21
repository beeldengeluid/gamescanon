<?php

	error_reporting(-1);

	/*
	prerequisites: markdown template

	parse json
	create markdown file for each item
	*/

	function slugify($text)
	{
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, '-');

	  // remove duplicate -
	  $text = preg_replace('~-+~', '-', $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) {
	    return 'n-a';
	  }

	  return $text;
	}

	$json = file_get_contents('data.json');
	$data_array = json_decode($json, TRUE);

	foreach ($data_array['events'] as $item) {

		$title = $item['text']['headline'];

		$file_name = slugify($title);

		$file_md = $file_name.'.markdown';
		$file_jpg = str_replace('-', '_', $file_name).'.jpg';

		$date_year = $item['start_date']['year'];
		$date_month = $item['start_date']['month'];

		$date = $date_year.'-'.str_pad($date_month, 2, "0", STR_PAD_LEFT).'-01';

		// echo $date;
		// echo '<br>';

		$contents  = '---'.PHP_EOL;
		$contents .= 'title: '.$title.PHP_EOL;
		$contents .= 'date: '.$date.PHP_EOL;
		$contents .= 'developer: '.PHP_EOL;
		$contents .= 'platform: '.PHP_EOL;
		$contents .= 'image: '.$file_jpg.PHP_EOL;
		$contents .= 'url: https://nl.wikipedia.org/wiki/'.str_replace(' ', '_', $title).PHP_EOL;
		$contents .= 'description: '.PHP_EOL;
		$contents .= '---'.PHP_EOL;

		$fp = fopen('_games/'.$file_md, 'w+');

		fwrite($fp, $contents);
		fclose($fp);

		// echo nl2br($contents);

		/*
		---
		title: Tempo Typen
		date: 1984-01-01
		developer: Radarsoft
		platform: Commodore 64, MSX
		image: tempo_typen.jpg
		url: https://nl.wikipedia.org/wiki/Tempo_Typen
		description: Dit is een bekend educatief spel van één van de eerste grote Nederlandse gamebedrijven in die tijd. De game is zelfs internationaal uitgegeven.
		---

		*/
	}

?>