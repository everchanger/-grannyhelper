<div class="col-xs-12 col-sm-offset-4 col-sm-5">
	<div class="row">
		<h3 id="header">Organization</h3>
	</div>
	
	<?php if(!$inOrganization): ?>
	<form action="?controller=organization&action=create" method="POST" enctype="multipart/form-data">
		<div class="form-group row">
			<label for="organization">Create organization</label>
			<input type="text" class="form-control" name="name">
		</div>
		<div class="form-group row">
			<input type="submit" class="btn btn-primary" value="Create" />
		</div>
	</form>

	<form action="?controller=organization&action=join" method="POST" enctype="multipart/form-data">
		<div class="form-group row">
			<label for="organization">Join Organization</label>
			<select class="form-control" name="organization" <?=count($organizations) <= 0 ? 'disabled' : ''?> >
			<?php if(count($organizations) <= 0): ?>
				<option value=''><?='No organizations availible'?></option>
			<?php else: ?>
			<?php foreach($organizations as $organization): ?>
				<option value='<?=$organization->id?>'><?=$organization->name?></option>
			<?php endforeach; ?>
			<?php endif; ?>
			</select>
		</div>
		<div class="form-group row">
			<input type="submit" class="btn btn-primary" value="Join" />
		</div>
	</form>	
	<?php endif; ?>
</div>
<div id="overlay">
	<div id="text">Laddar...</div>
</div>