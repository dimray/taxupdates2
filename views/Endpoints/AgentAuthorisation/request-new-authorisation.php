   <p>To request authorisation, enter the client's postcode and select whether you are the main or supporting agent.</p>

   <p>The postcode must match the UK postcode on record with HMRC. If your client does not have a UK postcode agent
       authorisation cannot be requested through MTD software.</p>

   <form class="generic-form" action="/agent-authorisation/create-new-authorisation" method="GET">
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

       <button class="form-button" type="submit">Submit</button>

   </form>



   <br>

   <p><a href="/agent-authorisation/request-status-of-relationship">View Relationship Status</a></p>

   <p><a href="/agent-authorisation/list-authorisation-requests">View Authorisation Requests</a></p>

   <p><a href="/clients/show-clients">Cancel</a></p>