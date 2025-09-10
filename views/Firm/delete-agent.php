  <p>Please type in your password to confirm removal of <?= $name ?> from your firm.</p>

  <form class="generic-form" action="/firm/delete-agent" method="POST">

      <div>
          <input type="hidden" name="agent_user_id" value="<?= $agent_user_id ?>">



          <div class="form-input">
              <label for="password">Password</label>

              <input type="password" name="password" id="password">
          </div>

      </div>

      <?php include ROOT_PATH . "views/shared/errors.php"; ?>

      <button class="form-button" type="submit">Delete</button>

  </form>

  <p><a href="/firm/show-firm">Cancel</a></p>