 <div id="countdown-msg">
     <p>HMRC is still preparing your tax calculation, please wait <span id="countdown" data-start="7">7</span>
         seconds...</p>

 </div>

 <form action="/individual-calculations/retrieve-calculation">
     <input type="hidden" name="calculation_id" value="<?= $calculation_id ?>">

     <button class="button" id="countdown-button" disabled=false>View Calculation</button>

 </form>

 <?php $include_countdown_script = true; ?>