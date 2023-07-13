<script>
$(document).ready(function() { 
    $('#demo12').click(function() { 
       $.growlUITopCenter('<h1>Growl Notification</h1>', '<h2>Have a nice day!</h2>'); 
   }); 
   
     $('#button').click(function() { 
       $.growlUITopRight('<h1>Growl Notification</h1>', '<h2>Have a nice day!</h2>'); 
   }); 
   
   
    $('#button2').click(function() { 
       $.growlUICenter('<h1>Growl Notification</h1>', '<h2>Have a nice day!</h2>'); 
	  
   });  
});
</script>
<p>
  <input type="button" name="button" id="demo12" value="growlUITopCenter" />
</p>
<p>
  <textarea name="textarea" id="textarea" cols="80" rows="13" wrap="soft">
 <script>
$(document).ready(function() { 
    $('#demo12').click(function() { 
       $.growlUITopCenter('<h1>Growl Notification</h1>', '<h2>Have a nice day!</h2>'); 
   }); 
});
</script>
 <input type="button" name="button" id="demo12" value="Top Center" />
  </textarea>
  <br />
  <br />
  <input type="submit" name="button2" id="button" value="growlUITopRight" />
  <br />
  <textarea name="textarea2" id="textarea2" cols="80" rows="13" wrap="soft">
<script>
$(document).ready(function() { 
   $('#button').click(function() { 
       $.growlUITopRight('<h1>Growl Notification</h1>', '<h2>Have a nice day!</h2>'); 
   }); 
});
</script>
 <input type="submit" name="button2" id="button" value="Top Right" />
  </textarea>
  <br />
  <br />
  <input type="button" name="button3" id="button2" value="Center" />
  <br />
  <textarea name="textarea3" id="textarea3" cols="80" rows="13" wrap="soft">
 <script>
$(document).ready(function() { 
    $('#button2').click(function() { 
       $.growlUICenter('<h1>Growl Notification</h1>', '<h2>Have a nice day!</h2>'); 
	  
   }); 
});
</script>
<input type="button" name="button3" id="button2" value="Center" />
  </textarea>
</p>
