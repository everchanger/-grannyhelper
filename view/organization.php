<div class="col-xs-12 col-sm-offset-4 col-sm-5">
	<div class="row">
		<h3 id="header">Organization</h3>
	</div>
	
	<?php if($userOrganization == null): ?>
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
			<select class="form-control" name="organization_id" <?=count($organizations) <= 0 ? 'disabled' : ''?> >
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
	<?php else: ?>
	<div class="row">
		<h4><?= (isset($userOrganization->admin_of) ? 'Admin of ' : 'Member of ' ).$userOrganization->name?></h4>
	</div>
	<?php if(isset($userOrganization->admin_of)): ?>
	<div class="row">
		<form action="?controller=display&action=create" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="organization_id" value="<?=$userOrganization->id?>">
			<div class="form-group row">
				<label for="organization">Create display</label>
				<input type="text" class="form-control" name="name">
			</div>
			<div class="form-group row">
				<input type="submit" class="btn btn-primary" value="Create" />
			</div>
		</form>
	</div>

	<div class="row">
		<?php if(count($displays) <= 0): ?>
		<p>No displays assigned</p>
		<?php else: ?>
			<ul>
			<?php foreach($displays as $display): ?>
				<li><?=$display->name?></li>
				<!-- list people who have access to this display here -->

				<a href="#" class="btn btn-primary">Add a user to this screen</a>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php endif; ?>
	<?php endif; ?>
</div>
<div id="overlay">
	<div id="text">Laddar...</div>
</div>