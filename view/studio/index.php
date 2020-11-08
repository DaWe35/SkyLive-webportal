

<!-- Page Content -->
<div class="w3-padding-large" id="main">
  <!-- Header/Home -->
	<header class="w3-container w3-padding-32 w3-center text-white" id="home">
		<h1 class="w3-jumbo">SkyLive studio</h1>
		<!-- <p>Photographer and Web Designer.</p> -->
	</header>
	
	<!-- Section -->
	<div class="w3-content text-white" id="photos">
		<a type="button" class="btn btn-grey4 btn-outline-light btn-lg float-right" href="/studio/new-stream">+ Create a new stream</a>
		<a type="button" class="btn btn-grey4 btn-outline-light btn-lg float-right mr-3" href="/studio/upload">↑ Upload video</a>
		<h2 class="text-muted">My streams</h2>
		<br>


		<!-- Grid for photos -->
		<div class="w3-row-padding div-table" style="margin:0 -16px">
			<div class="video-body"><?php
				while ($row = $stmt_streams->fetch(PDO::FETCH_ASSOC)) { ?>
					<div class="video-row">
						<div class="video-cell">
							<a href="/player?s=<?= $row['streamid'] ?>">
								<img src="<?= image_print($row['streamid'], 600) ?>" alt="" />
							</a>
						</div>
						<div class="video-cell">
							<div class="btn-group edit-dots">
								<button type="button" class="btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									...
								</button>
								<div class="dropdown-menu dropdown-menu-right bg-dark">
									<a class="dropdown-item btn" href="/studio/new-stream?edit=<?= $row['streamid'] ?>">Edit</a>
								</div>
							</div>
							<a href="/player?s=<?= $row['streamid'] ?>">
								<h4><?= $row['title'] ?></h4>
							</a>
							<p><?= $row['description'] ?></p>
							<p>Token: 
								<span class="streams-token"><?= $row['token'] ?></span><span>...</span>
								<button class="btn btn-grey4 btn-sm" onclick="copy('<?= $row['token'] ?>')">Copy token</button> <?php
								if ($row['started'] == 1 && $row['finished'] == 0) { ?>
									<button class="btn btn-danger btn-sm" onclick="finish_stream('<?= $row['streamid'] ?>', this)">Finish livestream</button> <?php
								} ?>
							</p>
						</div>
						
					</div> <?php
					$inwhile = 1;
				}
				if (!isset($inwhile)) { ?>
					<div class="m-auto" style="width: 400px;">
						<h3 class="pt-5">Start your first stream:</h3>
						<ul style="padding-left: 14px;">
							<li>
								<a href="https://github.com/DaWe35/SkyLive-GUI/releases" class="text-white">Download SkyLive GUI</a>
							</li>
							<li>
								<a href="https://obsproject.com/hu/download" class="text-white">Download OBS</a> <small>*restreaming doesn't need OBS</small>
							</li>
							<li>
								<a href="https://github.com/DaWe35/SkyLive#setup-obs" class="text-white">Setup OBS</a>
							</li>
							<li>
								<a href="/studio/new-stream" class="text-white">Create a new stream</a>
							</li>
						</ul>
					</div> <?php
				}
				$stmt_streams = null; ?>
			</div>
		<!-- End table -->
		</div>
		
	<!-- End Section -->
	</div>

<!-- END PAGE CONTENT -->
</div>

<script>
function copy(text) {
    let input = document.createElement('input');
    input.setAttribute('value', text);
    document.body.appendChild(input);
    input.select();
    let result = document.execCommand('copy');
    document.body.removeChild(input);
    return result;
}

function finish_stream(streamid, elem) {
	elem.disabled = true;
	var request = $.ajax({
		url: "/studio/finish_stream",
		type: "POST",
		data: {streamid: streamid},
		dataType: "html"
	});

	request.done(function(msg) {
		if (msg == 'ok') {
			elem.remove();
			confetti({
				angle: randomInRange(55, 125),
				spread: randomInRange(50, 70),
				particleCount: randomInRange(50, 100),
				origin: { y: 0.6 }
			});
		} else {
			alert( "Request failed: " + msg );
		}
	});

	request.fail(function(jqXHR, textStatus) {
		alert( "Request failed: " + textStatus );
	});
}



function randomInRange(min, max) {
	return Math.random() * (max - min) + min;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.3.1/dist/confetti.browser.min.js"></script>