<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
	<div class="row">
		<h3 id="header">Display settings</h3>
	</div>
	<?php if(count($displays) <= 0): ?>
	<div class="col-xs-12 content-wrapper">
		<p>You do not have access to any displays yet! 
		<?php if($inOrganization): ?>
		Once an administrator adds an display for you, you will be able to configure it here.</p>
		<?php else: ?>
		You should join or create an organization to get started!</p>
		<a href="?controller=organization&action=show" class="btn btn-primary">Organizations</a>
		<?php endif; ?>
	
	</div>
	<?php else: ?>
	<form action="?controller=home&action=updateSettings" method="POST" enctype="multipart/form-data">
		<input type="hidden" id="event_id" name="event_id">
		<input type="hidden" id="photo_id" name="photo_id">
		<div class="form-group front-form-group row">
			<label for="display">Display</label>
			<select required class="col-xs-4 form-control selectpicker" multiple id="display" name="display_ids[]">
			<?php $first_id = 0; foreach($displays as $display): ?>
				<option <?php if($first_id == 0) {echo 'selected';$first_id = $display->id;} ?>  value="<?=$display->id?>"><?=$display->name?></option>
			<?php endforeach; ?>
			</select>
			<div class="col-xs-12" id="display_link">
				<a target="_blank" href="?controller=display&action=show&id=<?=$first_id?>" id="show_screen">Show display</a>
			</div>
		</div>
		<div class="form-group front-form-group row">
			<label for="date">Datum</label>
			<div class='input-group date' id='datetimepicker1'>
				<input required type='text' id="date" name="date" class="form-control" />
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>
		<div class="form-group front-form-group row">
			<div class="checkbox">
				<label>
					<input name="standard" type="checkbox" value="1">
					<span id="standard">Använd som standard</span>
				</label>
			</div> 
		</div>
		<div class="form-group front-form-group row hidden">
			<label for="title">Titel</label>
			<input type="text" class="form-control" id="title" name="title">
		</div>
		<div class="form-group front-form-group row">
			<label for="description">Beskrivning</label>
			<textarea required id="description" name="description" class="form-control" rows="5"></textarea>
		</div>
		<div class="form-group front-form-group row">
			<div class="col-xs-6">
				<label for="photo">Bild</label>
				<input type="file" class="form-control" id="photo" name="photo">
			</div>
			<div class="col-xs-6">
				<img id="photo-preview" class="preview-upload" src="#" alt="Photo of the day" />
			</div>
		</div>
		<div class="form-group front-form-group row">
			<input type="submit" class="btn btn-primary" value="Spara" />
			<a href="#" class="pull-right btn btn-danger"  id="delete">Ta bort</a>
		</div>
	</form>
	<?php endif; ?>
</div>
<div id="overlay">
	<div id="text">Laddar...</div>
</div>