 <?php if ($agent_admin): ?>

     <p>You are the Admin for your firm. Delete your account <a href="/firm/view-firm">here</a>.</p>


 <?php else: ?>

     <form class="generic-form" action="/profile/delete-profile" method="POST">

         <p>Are you sure you want to delete your account? All data held by this application will be immediately deleted and
             cannot be retrieved.</p>

         <p>To delete your account, enter your password then click on 'Delete My Account'.</p>

         <?php include ROOT_PATH . "views/shared/errors.php"; ?>

         <div class="form-input">
             <label for="password">Password</label>
             <input type="password" name="password" id="password">
         </div>


         <button class="form-button delete-button">Delete My Account</button>

     </form>

 <?php endif; ?>

 <?php $include_scroll_to_errors_script = true; ?>