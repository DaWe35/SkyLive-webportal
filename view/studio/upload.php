<style>
	.drop-area {
		border: 2px dashed #ccc;
		border-radius: 1.5rem;
		width: 90%;
		height: 70vh;
		margin: 0 auto;
		padding: 1.5rem;
		position: relative;
	}

	#drop.highlight {
		border-color: #0a0;
	}

	.button-area {
		margin-top: 1rem;
	}

	.button {
		display: inline-block;
		padding: 0.5rem auto;
		line-height: 1rem;
		background: #ccc;
		background: linear-gradient(#bfbfbf, #3f3f3f);
		cursor: pointer;
		border-radius: 0.5rem;
		border: 1px outset #3f3f3f;
		text-align: center;
	}

	.button-right {
		float: right;
	}

	#image {
		display: inline-block;
		vertical-align: middle;
		max-width: calc(100% - 9rem);
		overflow-x: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		margin-left: 0.5rem;
	}

	.clicked {
		background: linear-gradient(#7f7f7f, #ffffff, #7f7f7f);
		color: #777;
	}

	.mouseover {
		background: #ddd;
		background: linear-gradient(#ffffff, #7f7f7f);
	}

	#file-select, #thumbnail, #submit {
		display: none;
	}

	#upload-label {
		position: absolute;
		margin-right: 0.5rem;
		top: 1rem;
		color: #ccc;
	}

	#upload-list {
		width: 100%;
		height: calc(100% - 3rem);
		overflow-y: auto;
	}

	#upload-table {
		width: 100%;
		border-collapse: collapse;
		table-layout: fixed;
	}

	.list-row:hover {
		background: #777;
		cursor: pointer;
	}

	.name-cell, .title-cell {
		width: 50%;
		border-bottom: 1px solid #aaa;
		overflow-x: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.name-cell {
		padding-right: 1rem;
		color: #0a0;
	}

	.title-cell {
		color: #ccc;
	}

	.hidden-cell {
		display: none;
	}

	.button-area {
		width: 100%;
	}

	.progress-bar {
		width: 100%;
		height: 1rem;
		background: #ccc;
		border: 1px solid #0a0;
		border-radius: 0.5rem;
	}

	.progress {
		width: 0%;
		height: 100%;
		background: #0a0;
		background: linear-gradient(#0a0, #050);
		border-radius: 0.5rem;
	}

	.form-tab {
		border: 1px solid #ccc;
		border-radius: 0.5rem;
		background: #777;
		padding: 1rem;
		padding-bottom: 0;
		position: absolute;
		z-index: 100;
		top: 50%;
		left: 50%;
		transform: translate(calc(60px - 50%), -50%);
		min-width: 320px;
		max-width: 90vw;
		display: none;
	}

	.fade-in {
		animation: fadeIn ease 1s;
		-webkit-animation: fadeIn ease 1s;
		-moz-animation: fadeIn ease 1s;
		-o-animation: fadeIn ease 1s;
		-ms-animation: fadeIn ease 1s;
	}

	@keyframes fadeIn {
		0% {opacity: 0;}
		100% {opacity: 1;}
	}

	@-webkit-keyframes fadeIn {
		0% {opacity: 0;}
		100% {opacity: 1;}
	}

	@-moz-keyframes fadeIn {
		0% {opacity: 0;}
		100% {opacity: 1;}
	}

	@-o-keyframes fadeIn {
		0% {opacity: 0;}
		100% {opacity: 1;}
	}

	@-ms-keyframes fadeIn {
		0% {opacity: 0;}
		100% {opacity: 1;}
	}

	.form-tab textarea {
		resize: none;
	}

	.form-tab input, .form-tab label, .form-tab textarea, .drop-area label {
		font-size: 1rem;
	}

	#main h1 {
		margin-top: 0;
	}

	@media screen and (min-width: 900px) {

		.name-cell {
			width: 33%;
		}

		.title-cell {
			width: 67%;
		}
	}

	@media screen and (max-width: 600px) {

		.form-tab {
			transform: translate(-50%, -50%);
		}

		#main h1 {
			margin-top: 30px;
		}
	}

</style>

<div id="main">
	<header class="w3-container w3-padding-large w3-center text-white" id="home">
		<h1 class="w3-xxlarge">Upload your video</h1>
	</header>
	<div class="w3-padding-large">
		<div class="drop-area" id="drop">
			<div id="upload-label">
				<p>Upload multiple videos with the file dialog or by dragging and dropping videos into the box.</p>
				<p>After a video is uploaded, you can edit its metadata such as title, description, etc., by clicking on the table row.</p>
			</div>
			<div id="upload-list">
				<table id="upload-table"></table>
			</div>
			<div class="button-area">
				<input type="file" id="file-select" multiple accept="video/*" onchange="handleFiles(this.files)">
				<label class="button button-right" for="file-select" tabindex="1" onkeydown="keyDown(event, this)" onkeyup="keyUp(event, this)" onmouseover="mouseOver(this)" onmouseout="mouseOut(this)" onmousedown="mouseDown(this)" onmouseup="mouseUp(this)">Select file(s)</label>
			</div>
		</div>
	</div>
</div>
<div id="form" class="form-tab fade-in">
	<form method="POST" enctype="multipart/form-data" id="form-data" onsubmit="saveChanges(); return false">
		<input type="hidden" id="userid" name="userid" required value="<?= $userid ?>">
		<input type="hidden" id="skylink" name="skylink" required>
		<p><input class="w3-input" type="text" placeholder="Title" id="title" name="title" required value="<?= $edit['title'] ?>" tabindex="1"></p>
		<p><textarea class="w3-input" placeholder="Description" id="description" name="description" tabindex="2"><?= $edit['description'] ?></textarea></p>
		<p>
			<input type="file" id="thumbnail" name="file" accept="image/*" onchange="image.innerText=this.files[0].name">
			<label class="button" for="thumbnail" tabindex="3" onkeydown="keyDown(event, this)" onkeyup="keyUp(event, this)" onmouseover="mouseOver(this)" onmouseout="mouseOut(this)" onmousedown="mouseDown(this)" onmouseup="mouseUp(this)">Thumbnail</label>
			<span id="image">None</span>
		</p>
		<p>Start time: 
			<input class="w3-input" type="datetime-local" id="scheule_time_local" onchange="change_timestamp()" required tabindex="4">
			<input type="hidden" id="scheule_time" name="scheule_time" required value="<?= $edit['scheule_time'] ?>">
		</p>
		<p class="w3-center">
			Visibility:<br>
			<input type="radio" id="public" name="visibility" value="public" onchange="toggle_private_notice()" required <?= $edit['visibility'] == 'public' ? 'checked' : '' ?>>
			<label for="public" tabindex="5" onkeyup="keyUp(event, this)">Public</label>
			<input type="radio" id="non-listed" name="visibility" value="non-listed" onchange="toggle_private_notice()" <?= $edit['visibility'] == 'non-listed' ? 'checked' : '' ?>>
			<label for="non-listed" tabindex="6" onkeyup="keyUp(event, this)">Non-listed</label>
			<input type="radio" id="private" name="visibility" value="private" onchange="toggle_private_notice()" <?= $edit['visibility'] == 'private' ? 'checked' : '' ?>>
			<label for="private" tabindex="7" onkeyup="keyUp(event, this)">Private</label><br>
			<small id="private_notice">Files on Skynet are public, be careful!</small>
		</p>
		<p class="text-center">
			<input type="submit" id="submit">
			<label id="submit-button" class="button" for="submit" tabindex="8" onkeydown="keyDown(event, this)" onkeyup="keyUp(event, this)" onmouseover="mouseOver(this)" onmouseout="mouseOut(this)" onmousedown="mouseDown(this)" onmouseup="mouseUp(this)"><i class="fa fa-save"></i>&nbsp;Save details</label>
		</p>
	</form>
</div>

<script>
	//definitions
	const dropArea = document.getElementById('drop');
	const uploadLabel = document.getElementById('upload-label');
	const uploadTable = document.getElementById('upload-table');
	const mainFrame = document.getElementById('main');
	const editForm = document.getElementById('form');
	var clickStarted = false;

	document.onmousedown = function(ev) {
		if ((ev.target == editForm) || isDescendant(editForm, ev.target)) {
			clickStarted = true;
		} else {
			clickStarted = false;
		}
	}

	document.onmouseup = function(ev) {
		if ((ev.target != editForm) && !isDescendant(editForm, ev.target) && !clickStarted) {
			formHide();
		}
	}

	function isDescendant(parent, child) {
		let node = child.parentNode;
		while (node != null) {
			if (node == parent) {
				return true;
			}
			node = node.parentNode;
		}
		return false;
	}

	//drag and drop functionality
	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		dropArea.addEventListener(eventName, preventDefaults, false)
	});
	['dragenter', 'dragover'].forEach(eventName => {
		dropArea.addEventListener(eventName, highlight, false)
	});
	['dragleave', 'drop'].forEach(eventName => {
		dropArea.addEventListener(eventName, unhighlight, false)
	});
	dropArea.addEventListener('drop', handleDrop, false);

	function preventDefaults(ev) {
		ev.preventDefault();
		ev.stopPropagation();
	}

	function highlight(ev) {
		dropArea.classList.add('highlight');
	}

	function unhighlight(ev) {
		dropArea.classList.remove('highlight');
	}

	function handleDrop(ev) {
		handleFiles(ev.dataTransfer.files);
	}

	//uploading to Skynet
	const skynetUrl = 'https://siasky.net/skynet/skyfile'; //this should be replaced with a link to desired portal
	const requestInterval = 500; //interval in ms between subsequent requests to prevent HTTP 429 error

	function handleFiles(files) {
		if (files.length != 0) {
			uploadLabel.style.display = 'none';
			for (let i = 0; i < files.length; i++) {
				setTimeout(uploadVideo, requestInterval * i, files[i], i + uploadTable.childElementCount);
			};
			document.getElementById('file-select').value = '';
		}
	}

	function uploadVideo(file, i) {
		let fd = new FormData();
		fd.append('file', file, file.name);
		let row = createTableRow(file.name, i);
		uploadTable.appendChild(row);
		let req = new XMLHttpRequest();
		req.open('POST', skynetUrl, true);
		req.upload.onprogress = function(ev) {
			updateProgress(i, (ev.loaded * 90.0 / ev.total) || 90, '');
		};
		req.onreadystatechange = function() {
			if ((this.readyState == 4) && (this.status == 200)) {
				updateProgress(i, 100, JSON.parse(this.responseText).skylink);
			}
		};
		req.send(fd);
	}

	//populating upload list
	function createTableRow(fileName, index) {
		set_scheule_time();
		let row = document.createElement('tr');
		row.className = 'list-row';
		row.id = 'row' + index;
		let userid = document.createElement('td');
		userid.className = 'hidden-cell';
		userid.innerText = <?= $userid ?>;
		row.appendChild(userid);
		let skylink = document.createElement('td');
		skylink.className = 'hidden-cell';
		row.appendChild(skylink);
		let name = document.createElement('td');
		name.className = 'name-cell';
		name.id = 'nam' + index;
		name.innerText = fileName;
		row.appendChild(name);
		let title = document.createElement('td');
		title.className = 'title-cell';
		title.id = 'ttl' + index;
		title.innerHTML = '<div class="progress-bar"><div id="bar' + index + '" class="progress"></div></div>';
		row.appendChild(title);
		let desc = document.createElement('td');
		desc.className = 'hidden-cell';
		row.appendChild(desc);
		let stime = document.createElement('td');
		stime.className = 'hidden-cell';
		stime.innerText = document.getElementById('scheule_time').value;
		row.appendChild(stime);
		let stimeLocal = document.createElement('td');
		stimeLocal.className = 'hidden-cell';
		stimeLocal.innerText = document.getElementById('scheule_time_local').value;
		row.appendChild(stimeLocal);
		let vis = document.createElement('td');
		vis.className = 'hidden-cell';
		vis.innerText = 'public';
		row.appendChild(vis);
		let thumb = document.createElement('td');
		thumb.className = 'hidden-cell';
		row.appendChild(thumb);
		return row;
	}

	//tracking upload progress
	function updateProgress(fileNo, percent, skylink) {
		document.getElementById('bar' + fileNo).style.width = percent + '%';
		if (percent == 100) {
			uploadTable.childNodes[fileNo].childNodes[1].innerText = skylink;
			//make the progress bar disappear 0.5s after the upload is complete
			setTimeout(function() {
				let s = uploadTable.childNodes[fileNo].childNodes[2].innerText;
				if ((s.slice(-4) == '.mp4') || (s.slice(-4) == '.wmv')) {
					uploadTable.childNodes[fileNo].childNodes[3].innerText = s.slice(0, s.length - 4);
				} else {
					uploadTable.childNodes[fileNo].childNodes[3].innerText = s;
				};
				uploadTable.childNodes[fileNo].onclick = formShow;
				sendData(fileNo, false);
			}, 500);

		}
	}

	//show and hide the form
	function formShow(ev) {
		let i = parseInt(ev.target.id.slice(3));
		document.getElementById('userid').value = uploadTable.childNodes[i].childNodes[0].innerText;
		document.getElementById('skylink').value = uploadTable.childNodes[i].childNodes[1].innerText;
		document.getElementById('title').value = uploadTable.childNodes[i].childNodes[3].innerText;
		document.getElementById('description').value = uploadTable.childNodes[i].childNodes[4].innerText;
		document.getElementById('scheule_time').value = uploadTable.childNodes[i].childNodes[5].innerText;
		document.getElementById('scheule_time_local').value = uploadTable.childNodes[i].childNodes[6].innerText;
		let v = uploadTable.childNodes[i].childNodes[7].innerText;
		switch (v) {
			case 'public':
				document.getElementById('public').checked = true;
				break;
			case 'non-listed':
				document.getElementById('non-listed').checked = true;
				break;
			case 'private':
				document.getElementById('private').checked = true;
				break;
			default:
				document.getElementById('public').checked = true;
		};
		document.getElementById('thumbnail').filename = uploadTable.childNodes[i].childNodes[8].innerText;
		document.getElementById('image').innerText = uploadTable.childNodes[i].childNodes[8].innerText;
		mainFrame.style.opacity = '0.3';
		editForm.style.display = 'block';
	}

	function formHide() {
		mainFrame.style.opacity = '1';
		editForm.style.display = 'none';
		document.getElementById('form-data').reset();
		document.getElementById('submit-button').innerHTML = '<i class="fa fa-save"></i>&nbsp;Save details';
	}

	function saveChanges() {
		let row = 0;
		while (uploadTable.childNodes[row].childNodes[1].innerText != document.getElementById('skylink').value) {
			row++;
		};
		uploadTable.childNodes[row].childNodes[3].innerText = document.getElementById('title').value;
		uploadTable.childNodes[row].childNodes[4].innerText = document.getElementById('description').value;
		uploadTable.childNodes[row].childNodes[5].innerText = document.getElementById('scheule_time').value;
		uploadTable.childNodes[row].childNodes[6].innerText = document.getElementById('scheule_time_local').value;
		if (document.getElementById('public').checked) {
			uploadTable.childNodes[row].childNodes[7].innerText = 'public';
		} else if (document.getElementById('non-listed').checked) {
			uploadTable.childNodes[row].childNodes[7].innerText = 'non-listed';
		} else if (document.getElementById('private').checked) {
			uploadTable.childNodes[row].childNodes[7].innerText = 'private';
		};
		if (document.getElementById('thumbnail').files.length != 0) {
			uploadTable.childNodes[row].childNodes[8].innerText = document.getElementById('thumbnail').files[0].name;
		}
		sendData(row, true);
	}

	//send form data to the server
	//this function may be called more than one time: once a video is uploaded and a skylink is received, and every time user edits the metadata
	function sendData(row, thumbnail) {
		let req = new XMLHttpRequest();
		req.open('POST', 'post/studio/upload.php', true); //PLS CHECK THE URL!!!
		let fd = new FormData();
		fd.append('userid', uploadTable.childNodes[row].childNodes[0].innerText);
		fd.append('skylink', uploadTable.childNodes[row].childNodes[1].innerText);
		fd.append('title', uploadTable.childNodes[row].childNodes[3].innerText);
		fd.append('description', uploadTable.childNodes[row].childNodes[4].innerText);
		fd.append('scheule_time', uploadTable.childNodes[row].childNodes[5].innerText);
		fd.append('visibility', uploadTable.childNodes[row].childNodes[7].innerText);
		if (thumbnail && (document.getElementById('thumbnail').files.length != 0)) {
			fd.append('thumbnail', document.getElementById('thumbnail').files[0]);
		};
		req.send(fd);
		if (thumbnail) {
			document.getElementById('submit-button').innerHTML = '<i class="fas fa-check" style="color: #0a0"></i>&nbsp;Changes saved!';
			setTimeout(formHide, 1000);
		};
	}

	//keyboard and mouse events
	function keyDown(ev, el) {
		if ((ev.key == ' ') || (ev.key == 'Enter')) {
			el.classList.add('clicked');
		}
	}

	function keyUp(ev, el) {
		if ((ev.key == ' ') || (ev.key == 'Enter')) {
			el.classList.remove('clicked');
			document.getElementById(el.getAttribute('for')).click();
		}
	}

	function mouseDown(b) {
		b.classList.remove('mouseover');
		b.classList.add('clicked');
	}

	function mouseUp(b) {
		b.classList.remove('clicked');
	}

	function mouseOver(b) {
		b.classList.add('mouseover');
	}

	function mouseOut(b) {
		b.classList.remove('mouseover');
	}

	//time manipulations
	function get_scheule_time() {
		scheule_time_intval = parseInt(document.getElementById('scheule_time').value)
		if (scheule_time_intval > 0) {
			var scheule = new Date(scheule_time_intval * 1000);
			scheule.setMinutes(scheule.getMinutes() - scheule.getTimezoneOffset());
			document.getElementById('scheule_time_local').value = scheule.toISOString().slice(0,16);
		} else {
			var now = new Date();
			now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
			document.getElementById('scheule_time_local').value = now.toISOString().slice(0,16);
			change_timestamp();
		}
	}

	function set_scheule_time() {
		var now = new Date();
		now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
		document.getElementById('scheule_time_local').value = now.toISOString().slice(0,16);
		change_timestamp();
	}

	function change_timestamp() {
		let scheule_time_local = Date.parse(document.getElementById('scheule_time_local').value) / 1000;
		let scheule_time = scheule_time_local;
		document.getElementById('scheule_time').value = scheule_time;
	}

	//display warning if 'private' is selected
	function toggle_private_notice() {
		if ($('input#private').is(':checked')) {
			$('#private_notice').css('display', 'initial')
		} else {			
			$('#private_notice').css('display', 'none')
		}
	}
</script>
