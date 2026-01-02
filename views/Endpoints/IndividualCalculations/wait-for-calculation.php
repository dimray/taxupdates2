 <div id="countdown-msg">
     <p>HMRC is still preparing your tax calculation, please wait <span id="countdown" data-start="7">7</span>
         seconds...</p>

 </div>

 <form class="hmrc-connection" action="/individual-calculations/retrieve-calculation">

     <input type="hidden" name="calculation_id" value="<?= $calculation_id ?>">

     <button class="button hidden" id="countdown-button" disabled=false>View Calculation</button>

 </form>

 <?php $include_countdown_script = true; ?>