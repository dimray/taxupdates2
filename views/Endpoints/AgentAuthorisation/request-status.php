   <p>Enter your client's postcode below and select whether you are the main or supporting agent. The postcode must
       match the UK postcode on record with HMRC.</p>


   <form class="generic-form" action="/agent-authorisation/get-status-of-relationship" method="GET">

       <div>
           <div class="form-input">
               <label for="postcode">Postcode</label>
               <input type="text" name="postcode" id="postcode">
           </div>

           <div class="form-input">
               <label for="agent_type">Agent type</label>
               <select name="agent_type" id="agent_type">
                   <option value="main" selected>main</option>
                   <option value="supporting">supporting</option>
               </select>
           </div>
       </div>

       <?php include ROOT_PATH . "views/shared/errors.php"; ?>

       <button type="submit" class="form-button">Submit</button>

   </form>

   <br>

   <p><a href="/agent-authorisation/request-new-authorisation">Request Authorisation</a></p>

   <p><a href="/clients/show-clients">Cancel</a></p>