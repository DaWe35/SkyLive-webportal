<!-- Page Content -->
<div class="w3-padding-large" id="main">

  <!-- Contact Section -->
	<div class="w3-padding-64 w3-content w3-text-grey" id="contact"> <?php
		if ($edit['format'] == 'hls' || $edit['format'] == '') { ?>
			<p class="text-center">Upload live event details, generate stream token:</p> <?php
		} ?>

		<form method="POST" class="new-stream-form" enctype="multipart/form-data">
			<p><input class="w3-input" type="text" placeholder="Title" name="title" required value="<?= $edit['title'] ?>"></p>
			<p><textarea class="w3-input" type="text" placeholder="Description" name="description"><?= $edit['description'] ?></textarea></p>
			<p>Thumbnail: <input class="w3-input" type="file" placeholder="Thumbnail" name="file"></p> <?php

			if ($edit['format'] == 'hls' || $edit['format'] == '') { ?>
				<p>Start time: 
					<input class="w3-input" type="datetime-local" id="scheule_time_local" onchange="change_timestamp()" required>
					<input type="hidden" id="scheule_time" name="scheule_time" required value="<?= $edit['scheule_time'] ?>">
				</p> <?php
			} ?>

			<p>
				Visibility:
				<input type="radio" id="public" name="visibility" value="public" onchange="toggle_private_notice()" required <?= $edit['visibility'] == 'public' ? 'checked' : '' ?>>
				<label for="public">Public</label>
				<input type="radio" id="non-listed" name="visibility" value="non-listed" onchange="toggle_private_notice()" <?= $edit['visibility'] == 'non-listed' ? 'checked' : '' ?>>
				<label for="non-listed">Non-listed</label>
				<input type="radio" id="private" name="visibility" value="private" onchange="toggle_private_notice()" <?= $edit['visibility'] == 'private' ? 'checked' : '' ?>>
				<label for="private">Private</label>
				<small id="private_notice">Files on Skynet are public, be careful!</small>
			</p>
			<input type="hidden" name="edit_id" required value="<?= isset($edit_id) ? $edit_id : '' ?>">
			<input type="hidden" name="format" required value="hls">
			<p class="text-center">
				<button class="btn btn-grey4 btn-outline-light btn-lg" type="submit">
					<i class="fa fa-save"></i> Save details
				</button>
			</p>
		</form>
	<!-- End Contact Section -->
	</div>

<!-- END PAGE CONTENT -->
</div>

<script>
	scheule_time_intval = parseInt(document.getElementById('scheule_time').value)
	if (scheule_time_intval > 0) {
		var scheule = new Date(scheule_time_intval * 1000);
		scheule.setMinutes(scheule.getMinutes() - scheule.getTimezoneOffset());
		document.getElementById('scheule_time_local').value = scheule.toISOString().slice(0,16);
	} else {
		var now = new Date();
		now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
		document.getElementById('scheule_time_local').value = now.toISOString().slice(0,16);
		change_timestamp()
	}

	function change_timestamp() {
		let scheule_time_local = Date.parse(document.getElementById('scheule_time_local').value) / 1000;
		let scheule_time = scheule_time_local;
		document.getElementById('scheule_time').value = scheule_time;
	}

	function toggle_private_notice() {
		if ($('input#private').is(':checked')) {
			$('#private_notice').css('display', 'initial')
		} else {			
			$('#private_notice').css('display', 'none')
		}
	}
</script>