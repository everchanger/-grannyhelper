<div class="col-xs-12 col-sm-offset-3 col-sm-6">
    <h4>Sign up</h4>
    <form action="?controller=user&action=register" method="POST" class="form-horizontal">
        <div class="form-group">
            <label for="register_email" class="control-label">Email</label>

                <input type="email" required class="form-control" name="email" placeholder="user@email.net" />

        </div>
        <div class="form-group">
            <label for="register_password1" class="control-label">Password</label>

                <input type="password" required class="form-control" name="password1" placeholder="Password" />

        </div>
        <div class="form-group">
            <label for="register_password2" class="control-label">Confirm password</label>

                <input type="password" required class="form-control" name="password2" placeholder="Confirm password" />

        </div>
        <div class="form-group">
            <div class=" col-xs-12">
                <input type="submit" class="btn btn-default" value="Register" />
            </div>
        </div>
    </form>
</div>