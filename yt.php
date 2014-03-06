<?php
//to fetch the current youtube id & date using youtube rss feed
$id = NULL;

$username = 'yourchannel';

$xml = simplexml_load_file(sprintf('http://gdata.youtube.com/feeds/base/users/%s/uploads?alt=rss&v=2&orderby=published', $username));

if ( ! empty($xml->channel->item[0]->link) ) {
	parse_str(parse_url($xml->channel->item[0]->link, PHP_URL_QUERY), $url_query);
	if ( ! empty($url_query['v']) ) {
		$id = $url_query['v']; 
	}
}

if ( ! empty($xml->channel->item[0]->pubDate) ) {
	$d = $xml->channel->item[0]->pubDate; //youtube video date
	$d = date("Y-m-d",strtotime($d)); //get in YMD format
}

//Assuming you are running this script everyday! It will check if any youtube uploads are done for today
$today = date("Y-m-d");

if ($d == $today) {
	/* Refer http://rg3.github.io/youtube-dl/  for complete details of this application*/
	
	/* sudo pip install --upgrade youtube_dl  or download the youtube-dl binary from the above url and place it in cwd*/
	$cmd = 'youtube-dl -o "/TEMP/DIRECTORY/%(title)s.%(ext)s" --restrict-filenames http://www.youtube.com/watch?v='.$id; //change the dir
	exec($cmd);
	$cmd2 = 'youtube-dl --get-filename -o "%(title)s.%(ext)s" --restrict-filenames http://www.youtube.com/watch?v='.$id; 
	$s = exec($cmd2);
	$file = basename($s,".mp4");
	$file = $file.".mp3";
	exec('ffmpeg -i /source/directory/'.$s.' -ab 32k -y /destination/directory/'.$file); //change the path accordingly
} else {
	//echo "No Hangout-on-air today";
}
?>
