<div class="col-xs-12 col-sm-offset-4 col-sm-5">
	<div class="row">
		<h3 id="header">Inställningar</h3>
	</div>
	<form action="?controller=home&action=updateSettings" method="POST" enctype="multipart/form-data">
		<input type="hidden" id="event_id" name="event_id">
		<input type="hidden" id="photo_id" name="photo_id">
		<div class="form-group row">
			<label for="date">Datum</label>
			<div class='input-group date' id='datetimepicker1'>
				<input required type='text' id="date" name="date" class="form-control" />
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-calendar"></span>
				</span>
			</div>
		</div>
		<div class="form-group row">
			<div class="checkbox">
				<label>
					<input name="standard" type="checkbox" value="1">
					<span id="standard">Använd som standard</span>
				</label>
			</div> 
		</div>
		<div class="form-group row">
			<label for="title">Titel</label>
			<input required type="text" class="form-control" id="title" name="title">
		</div>
		<div class="form-group row">
			<label for="description">Beskrivning</label>
			<textarea required id="description" name="description" class="form-control" rows="5"></textarea>
		</div>
		<div class="form-group row">
			<div class="col-xs-6">
				<label for="photo">Bild</label>
				<input type="file" class="form-control" id="photo" name="photo">
			</div>
			<div class="col-xs-6">
				<img id="photo-preview" class="preview-upload" src="#" alt="Photo of the day" />
			</div>
		</div>
		<div class="form-group row">
			<input type="submit" class="btn btn-primary" value="Spara" />
			<a href="#" class="pull-right btn btn-danger"  id="delete">Ta bort</a>
		</div>
	</form>
</div>
<div id="overlay">
	<div id="text">Laddar...</div>
</div>