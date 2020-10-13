<?php

function print_stream($stream, $portal) {
	global $db;
	
	echo "#EXTM3U\n";
	echo "#EXT-X-VERSION:3\n";
	echo "#EXT-X-TARGETDURATION:10\n";
	echo "#EXT-X-MEDIA-SEQUENCE:0\n";
	echo "#EXT-X-PLAYLIST-TYPE:VOD\n";
	
	if ($stream['finished'] == 0) {
		print_intro($portal);
	}
	
	$stmt = $db->prepare("SELECT `id`, `streamid`, `length`, `skylink`, `is_first_chunk` FROM `chunks` WHERE streamid = ? AND resolution = ? ORDER BY id ASC");
	if (!$stmt->execute([$stream['streamid'], $_GET['resolution']])) {
		exit('Database error');
	}
	$start_chunk = true;
	while ($chunk = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if ($chunk['is_first_chunk'] == 1 && $start_chunk == false) {
			echo "#EXT-X-DISCONTINUITY\n";
		}
		echo "#EXTINF:{$chunk['length']},\n";
		echo "{$portal}/{$chunk['skylink']}/\n";
		$start_chunk = false;
	}
	$stmt = null;
}


function print_loop_stream($videos, $stream, $portal) {
	global $db;
	// check $videos is_numeric()
	foreach ($videos as $key => $value) {
		if (!is_numeric($value)) {
			exit('ERROR: Non-numeric values passed to print_loop_stream():' . $key  . ' => ' . $value);
		}
	}
	$videos_imp = implode(",", $videos);
	// this code is secure here because I checked all items in $videos_imp are numbers. Do not use anywhere else!
	$stmt = $db->prepare("SELECT length, skylink, is_first_chunk FROM `chunks` WHERE streamid IN (" . $videos_imp . ") AND resolution = ? ORDER BY FIELD(streamid,".$videos_imp."), id ASC");
	if (!$stmt->execute([$_GET['resolution']])) {
		exit('Database error');
	}
	$chunks = $stmt->fetchAll();
	$stmt = null;
	$videos_len_sum = 0;
	$total_chunk_number = 0;
	foreach($chunks as $chunk) {
		$videos_len_sum += $chunk['length'];
		$total_chunk_number += 1;
	}


	$scheule_time = intval($stream['scheule_time']);
	$current_time = time();
	$time_diff = $current_time - $scheule_time;
	$video_position = $time_diff % $videos_len_sum;
	$video_fully_played = intval($time_diff / $videos_len_sum);
	$chunks_fully_played = $video_fully_played * $total_chunk_number;

	$sum_counter = 0;
	$chunk_printed = 0;

	$start_chunk = true;
	foreach($chunks as $chunk) {
		$sum_counter += $chunk['length'];
		if ($sum_counter > $video_position) {
			if ($chunk_printed === 0) {
				if ($video_position >= 0) {
					$chunks_fully_played += 3;
				}
				echo "#EXTM3U\n";
				echo "#EXT-X-VERSION:3\n";
				echo "#EXT-X-TARGETDURATION:10\n";
				echo "#EXT-X-MEDIA-SEQUENCE:" . $chunks_fully_played . "\n";
				echo "#EXT-X-PLAYLIST-TYPE:VOD\n";
			}

			if ($video_position < 0) {
				print_intro($portal);
				return true;
			} else {
				if ($chunk['is_first_chunk'] == 1 && $start_chunk == false) {
					echo "#EXT-X-DISCONTINUITY\n";
				}
				echo "#EXTINF:{$chunk['length']},\n";
				echo "{$portal}/{$chunk['skylink']}/\n";
				
				$chunk_printed += 1;
			}
		}
		$start_chunk = false;
		if ($chunk_printed == 3) {
			return true;
		}
		
		$chunks_fully_played += 1;
	}
	
	echo "loooop\n";
	echo $video_position;
}

function print_intro($portal) {
	echo "#EXTINF:10.000000,\n";
    echo $portal . "/AABa67V-0McynqzloU6CBvHECTY-R8mm6SaB1Smr2pkU1g/\n";
    echo "#EXTINF:10.000000,\n";
    echo $portal . "/_B2H5ZepchMfdYj1BLjlkZqsGUEhXEA-rxi--80i-1mEGA/\n";
    echo "#EXTINF:4.000000,\n";
    echo $portal . "/PAO6OSO_yznBInoTofjORkLxBwxIRuPMzg3C3QwFaDcjKg/\n";
    echo "#EXT-X-DISCONTINUITY\n";
}