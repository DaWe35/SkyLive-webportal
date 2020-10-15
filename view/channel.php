<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script src="assets/popper.min.js"></script>
<script>
	// Transforms seconds to hh:mm:ss.
	function toHMS(seconds) {
		var s = seconds.toFixed(0);
		var result = ":";
		var ss = s % 60;
		if (ss >= 10) {result += ss;} else if (ss > 0) {result += "0" + ss;} else result += "00";
		s = ((s - ss) / 60).toFixed(0);
		ss = s % 60;
		if (s >= 60) {
			if (ss >= 10) {result = ":" + ss + result;} else if (ss > 0) {result = ":0" + ss + result;} else result = ":00" + result;
			s = ((s - ss) / 60).toFixed(0);
			result = s + result;
		} else result = s + result;
		return result;
	}

	function previewVideo(v) {
		document.getElementById("video" + v.id.slice(3)).play();
	}

	function stopPreview(v) {
		document.getElementById("video" + v.id.slice(3)).pause();
	}

	function displayDuration(v) {
		document.getElementById("duration" + v.id.slice(5)).innerHTML = toHMS(v.duration);
		document.getElementById("duration" + v.id.slice(5)).style.display = 'inline-block';
	}

	function submitForm(b) {
		var form = document.getElementById('form');
		if (b.name == 'numperpage') {
			var n = document.getElementById('numperpage');
			n.value = b.value;
		} else if (b.name == 'page') {
			var c = document.getElementById('page');
			if (b.value == 'Prev') {
				c.value = parseInt(c.value, 10) - 1;
			} else if (b.value == 'Next') {
				c.value = parseInt(c.value, 10) + 1;
			} else {
				c.value = b.value;
			}
		} else if (b.name == 'user') {
			var u = document.getElementById('user');
			u.value = b.value;
		}
		form.submit();
	}
</script>

<style>
	.wrap {
		min-height: 100vh;
		background: #092756;
		background: -moz-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%, rgba(138,114,76,0) 40%), -moz-linear-gradient(top, rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), -moz-linear-gradient(-45deg, #251314 0%, #092756 100%);
		background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%, rgba(138,114,76,0) 40%), -webkit-linear-gradient(top, rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), -webkit-linear-gradient(-45deg, #251314 0%, #092756 100%);
		background: -o-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%, rgba(138,114,76,0) 40%), -o-linear-gradient(top, rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), -o-linear-gradient(-45deg, #251314 0%, #092756 100%);
		background: -ms-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%, rgba(138,114,76,0) 40%), -ms-linear-gradient(top, rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), -ms-linear-gradient(-45deg, #251314 0%, #092756 100%);
		background: radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%, rgba(138,114,76,0) 40%), linear-gradient(top, rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), linear-gradient(-45deg, #251314 0%, #092756 100%);
	}

	.video-container {
		position: relative;
	}

	.duration {
		position: absolute;
		display: none;
		bottom: 0.6rem;
		right: 1.2rem;
		font-size: 0.75em;
		font-weight: bold;
		background-color: rgba(0,0,0,0.5);
		padding: 0.1em 0.5em;
		transition: opacity 0.5s ease;
	}

	.preview {
		position: absolute;
		display: inline-block;
		top: calc(50% - 0.75em);
		left: calc(50% - 0.75em);
		font-size: 1.5em;
		opacity: 0;
		transition: opacity 0.5s ease;
		cursor: pointer;
	}

	.video-container:hover {
		cursor: pointer;
	}

	.video-container:hover .duration {
		opacity: 0;
	}

	.video-container:hover .preview {
		opacity: 0.7;
	}

	.title, .user, .title:hover, .user:hover  {
		color: #aaa;
		font-size: 0.8em;
		text-decoration: none;
	}

	.title {
		font-weight: bold;
	}

	.user:hover {
		cursor: pointer;
	}

	body .bg-gradient {
		background: rgb(112,36,89);
		background: linear-gradient(121deg, rgba(112, 36, 89, 0.8) 0%, rgba(0, 0, 0, 0) 90%); 
	}
</style>




<a href="<?= URL ?>" class="logo position-absolute"><button class="play mini-logo-play"></button>&nbsp; SkyLive</a>
<header class="bg-gradient pt-5 pb-2">
	<div class="container h-100">
		<div class="row h-100 align-items-center">
			<div class="col-lg-9">
				<h1 class="display-4 text-white font-weight-bold mt-3">
					<?= $userid != 0 ? $names[0] : 'all videos on Skylive' ?>
				</h1>
				<!-- <p class="lead mb-5 text-white-50">channel<br> -->
			</div>
			<div href="register" class="position-absolute text-white" style="top: 5px; right: 15px;">
				<a class="text-white" href="register">Get beta access</a> | 
				<a class="text-white" href="login">Login</a>
			</div>
		</div>
	</div>
</header>


<form id="form" class="container-fluid" method="get" action="/channel">
	<input id="user" type="hidden" name="user" value="<?= $userid ?>"></input>
	<input id="numperpage" type="hidden" name="numperpage" value="<?= $thumbs_per_page ?>"></input>
	<input id="page" type="hidden" name="page" value="<?= $page ?>"></input>
	<div class="row mt-4 mb-1">
		<div class="d-flex col-1">
			<div class="dropdown">
				<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">Num per page</button>
				<div class="dropdown-menu">
					<button type="button" name="numperpage" onclick="submitForm(this)" class="dropdown-item btn-sm <?= $thumbs_per_page == $thumbs[0] ? 'active' : '' ?>" value="<?= $thumbs[0] ?>"><?= $thumbs[0] ?></button>
					<button type="button" name="numperpage" onclick="submitForm(this)" class="dropdown-item btn-sm <?= $thumbs_per_page == $thumbs[1] ? 'active' : '' ?>" value="<?= $thumbs[1] ?>"><?= $thumbs[1] ?></button>
					<button type="button" name="numperpage" onclick="submitForm(this)" class="dropdown-item btn-sm <?= $thumbs_per_page == $thumbs[2] ? 'active' : '' ?>" value="<?= $thumbs[2] ?>"><?= $thumbs[2] ?></button>
				</div>
			</div>
		</div>
		<div class="d-flex col-11 justify-content-end">
			<div class="btn-group btn-group-sm">
				<button type="button" name="page" onclick="submitForm(this)" class="btn btn-secondary" <?= $page == 1 ? 'disabled' : '' ?> value="Prev">Prev</button>
				<?php for ($i = 0; $i < $num_videos / $thumbs_per_page; $i++) { ?>
					<button type="button" name="page" onclick="submitForm(this)" class="btn btn-secondary <?= $i == $page - 1 ? 'active' : '' ?>" value="<?= $i + 1 ?>"><?= $i + 1 ?></button>
				<?php } ?>
				<button type="button" name="page" onclick="submitForm(this)" class="btn btn-secondary" <?= $page == ceil($num_videos / $thumbs_per_page) ? 'disabled' : '' ?> value="Next">Next</button>
			</div>
		</div>
	</div>
</form>
<div class="container-fluid">
	<div class="row no-gutters">
		<?php for ($row = 0; $row < count($stream); $row++) { ?>
			<div class="col-sm-6 col-md-4 col-lg-3">
				<div class="row">
					<div class="col-12 mt-3">
						<a href="/player?s=<?= $stream[$row]['streamid'] ?>">
							<div class="video-container col-12" onmouseover="previewVideo(this)" onmouseout="stopPreview(this)" id="div<?= $row ?>">
								<video class="rounded-lg" muted onloadedmetadata="displayDuration(this)" poster="<?= '/thumbnails/' . $stream[$row]['streamid'] . '_300.jpg' ?>" id="video<?= $row ?>">
									Your browser does not support video.
								</video>
								<span class="play preview"></span>
								<span class="duration rounded-lg text-white" id="duration<?= $row ?>"></span>
							</div>
						</a>
					</div>
				</div>
				<div class="row mt-1">
					<div class="pl-4 col-3">
						<img class="img-fluid rounded-circle" src="<?= $avatars[$row] ?>" alt="">
					</div>
					<div class="pl-0 pr-4 col-9">
						<a class="title mb-0" href="/player?s=<?= $stream[$row]['streamid'] ?>"><?= $stream[$row]['title'] ?></a><br>
						<a class="user mb-2" href="/channel?user=<?= $ids[$row] ?>"><?= $names[$row] ?></a>
					</div>
				</div>
			</div>
			<script>
				var video = document.getElementById('<?= "video" . $row ?>');
				if (Hls.isSupported()) {
					var hls = new Hls();
					hls.loadSource('/stream.m3u8?streamid=<?= $stream[$row]["streamid"] ?>');
					hls.attachMedia(video);
				}
				else if (video.canPlayType('application/vnd.apple.mpegurl')) {
					video.src = '/stream.m3u8?streamid=<?= $stream[$row]["streamid"] ?>';
				}
			</script>
		<?php } ?>
	</div>
</div>