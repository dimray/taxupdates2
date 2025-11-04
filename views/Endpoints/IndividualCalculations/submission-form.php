   <form class="generic-form hmrc-connection" action="/individual-calculations/submit-final-declaration" method="POST">

       <p>Before you submit the information displayed here in response to your notice to file from HM Revenue &
           Customs, you must read and agree to the following statement:</p>

       <?php if ($_SESSION['user_role'] === "individual"): ?>

           <p>I declare that the information and self-assessment I have filed are (taken together)
               correct and complete to the
               best of my knowledge. I understand that I may have to pay financial penalties and face prosecution if I give
               false
               information.</p>


       <?php elseif ($_SESSION['user_role'] === "agent"): ?>

           <p>My client has received a copy of all the
               information being filed and
               approved the information as
               being correct and complete to the best of their knowledge and belief. My client understands that they may
               have
               to pay financial penalties and face prosecution if they give false information.</p>

       <?php endif; ?>

       <input type="hidden" name="calculation_id" value="<?= $calculation_id ?>">

       <input type="hidden" name="calculation_type" value="<?= $calculation_type ?>">


       <div class="inline-checkbox">
           <input type="checkbox" id="confirm_submit" name="confirm_submit" value="true" required>
           <label for="confirm_submit">I confirm the above statement is correct.</label>

       </div>

       <?php include ROOT_PATH . 'views/shared/errors.php'; ?>

       <button type="submit" class="form-button">Submit to HMRC</button>

   </form>


   <?php $include_scroll_to_errors_script = true; ?>