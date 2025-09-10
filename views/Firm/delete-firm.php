 <p>Agent Reference: <?= $arn ?></p>

 <p>Please type in your password to confirm you want to delete your firm. Your account will be deleted along with the
     firm, as an Agent account cannot exist without a firm.</p>

 <form class="generic-form" action="/firm/delete-firm" method="POST">

     <div>

         <div class="form-input">
             <label for="password">Password</label>
             <input type="password" name="password" id="password">
         </div>

     </div>

     <?php include ROOT_PATH . "views/shared/errors.php"; ?>

     <button class="form-button" type="submit">Delete Firm</button>

 </form>

 <br>

 <p><a href="/firm/show-firm">Cancel</a></p>