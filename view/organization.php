<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
	<div class="col-xs-12 content-header">
		<?php if($userOrganization == null): ?>
			<h3 id="header">Organization</h3>
		<?php else: ?>
			<h3 id="header"><?= $userOrganization->name . (isset($userOrganization->admin_of) ? ' (Admin)' : '(Member)' )?></h3>
		<?php endif; ?>
	</div>
	
	<?php if($userOrganization == null): ?>
	<div class="col-xs-12 content-wrapper">
		<h4>Create Organization</h4>
		<form action="?controller=organization&action=create" method="POST" enctype="multipart/form-data">
			<div class="form-group">
				<label for="organization">Organization name</label>
				<input type="text" class="form-control" name="name">
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Create" />
			</div>
		</form>
	</div>

	<div class="col-xs-12 content-wrapper">
		<h4>Join Organization</h4>
		<form action="?controller=organization&action=join" method="POST" enctype="multipart/form-data">
			<div class="form-group">
				<label for="organization">Organization</label>
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
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Join" />
			</div>
		</form>	
	</div>

	<?php else: ?>

	<?php if(isset($userOrganization->admin_of)): ?>
	<div class="col-xs-12 content-wrapper">
		<h4>User list</h4>
		<div class="col-xs-12 display-wrapper">
		<?php foreach($members as $member): ?>	
			<?php if($_SESSION['signed_in_user_id'] == $member->id) {
				continue;
			} ?>		
			<div class="col-xs-12 display-item">
				<div class="col-xs-12 col-sm-4 paragraf-wrapper">
					<p class="display-paragraf"><?=$member->email?></p>
				</div>
				<div class="col-xs-12 col-sm-8">
					<?php if(isset($member->isAdmin)): ?>
						<a href="?controller=organization&action=removeAdmin&user_id=<?=$member->id?>&organization_id=<?=$userOrganization->id?>" class="btn btn-danger pull-right-large">Remove administrator</a>
					<?php else: ?>
						<a href="?controller=organization&action=addAdmin&user_id=<?=$member->id?>&organization_id=<?=$userOrganization->id?>" class="btn btn-primary pull-right-large">Make administrator</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
		</div>
	</div>

	<div class="col-xs-12 content-wrapper">
		<h4>Create new display</h4>
		<form class="form-inline"action="?controller=display&action=create" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="organization_id" value="<?=$userOrganization->id?>">
			<div class="form-group">
				<label for="organization">Display name</label>
				<input type="text" class="form-control" name="name">
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="Create" />
			</div>
		</form>
	</div>

	<div class="col-xs-12 content-wrapper">
		<h4>Display list</h4>
		<?php if(count($displays) <= 0): ?>
		<div class="col-xs-12">
			<p>No displays assigned</p>
		</div>
		<?php else: ?>
		<?php foreach($displays as $display): ?>
		<div class="col-xs-12 display-wrapper">
			<div class="col-xs-12 display-item-header">
				<a href="?controller=display&action=show&id=<?=$display->id?>"><?=$display->name?></a>
			</div>
			<!-- list people who have access to this display here -->
			<?php if(count($members) <= 0): ?>
			
			<?php else: ?>
			<?php 
				$count = 0;
				foreach($members as $member) {
					foreach($member->displays as $memberDisplay) {
						if($memberDisplay->id == $display->id) {
							echo '<div class="col-xs-12 display-item">';
							echo '<div class="col-xs-12 col-sm-4 paragraf-wrapper">';
							echo '<p class="display-paragraf">'.$member->email.'</p>';
							echo '</div>';
							echo '<div class="col-xs-12 col-sm-8">';
							echo '<a href="?controller=display&action=removeUser&user_id='.$member->id.'&display_id='.$display->id.'" class="btn btn-danger pull-right-large">Remove access</a>';
							echo '</div>';
							echo '</div>';
							$count++;
						}
					}
				} 

				if($count == 0) {
					echo '<div class="col-xs-12">';
					echo '<p>No users assigned</p>';
					echo '</div>';
				}
			?>
			<?php endif; ?>
			<?php if($count != count($members)): ?>
			<div class="col-xs-12 btn-wrapper">
				<a href="#" class="btn btn-primary add_user_to_display" data-display-id="<?=$display->id?>" data-display-name="<?=$display->name?>">Add a user to this screen</a>
			</div>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>
		<?php endif; ?>
		
	</div>
	<?php endif; ?>
	<?php endif; ?>
</div>

<?php if(isset($userOrganization->admin_of)): ?>
<div id="addUserDisplay" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<form action="?controller=display&action=addUser" method="POST" enctype="multipart/form-data">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="modal-title" class="modal-title">Add user to display</h4>
				</div>
				<div class="modal-body">
					<p>Choose which user to add to this display.</p>
					<input type="hidden" name="display_id" id="display_id">

					<select class="form-control" name="user_id">
						<?php foreach($members as $member): ?>
						<option class="user-option" data-display="<?php
							foreach($member->displays as $display) {
								echo $display->id .',';
							}
						?>" value="<?=$member->id?>"><?=$member->email?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Add</button>
				</div>
			</div>
		</form>					
	</div>
</div>
<?php endif; ?>

<div id="overlay">
	<div id="text">Laddar...</div>
</div>