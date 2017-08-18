<div class="col-xs-12 col-sm-offset-4 col-sm-5">
	<div class="row">
		<h3 id="header">Sign in</h3>
	</div>
    <form action="?controller=user&action=login" method="POST">
        	<div class="form-group row">
          	    <input type="email" class="form-control" placeholder="user@mail.net" name="email" id="email">
        	</div>
			<div class="form-group row">
          	    <input type="password" class="form-control" placeholder="Password" name="password" id="user-password">
        	</div>
            <div class="form-group row">
        	    <button type="submit" class="btn btn-primary">Sign in</button>
            </div>
    </form>
</div>
<div id="overlay">
	<div id="text">Laddar...</div>
</div>