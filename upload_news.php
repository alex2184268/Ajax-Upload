<form name="news_form" action="uploadFile.php" method="post" enctype="multipart/form-data">
<!--php_self -->
<fieldset>
<legend>Add news</legend>
<ol>
    <input hidden type="text" name="news_id" id="news_id" value="<? echo $new_news_id?>">
    <li>
		<label>Date :</label>
		<input name="start_date" type="text" id="start_date" size="15" value=""/><input type="reset" value="..." onclick="return showCalendar('start_date','y-m-d');" required/>
    </li>
    
		<li>
		<label>Title :</label>
    <input name="subject" type="text" id="subject" size="40" maxlength="50" value="" required/>
    </li>
    
    <li>
    <label>Content:</label>
    <textarea name="news_content" cols="40" rows="5" id="news_content" required></textarea>
    </li>
    
    <li>
    <label>Email :</label>
    <input name="email" type="email" id="email" size="40" maxlength="40" value="" required/>
    </li>
    
    <li>
    <label>Tel :</label>
    <input name="tel" type="text" id="tel" size="20" maxlength="20" value=""  required/>
    </li>
<fieldset class="submit">

<input name="result" id="result" type="checkbox" value="1" />Result?
<!--若為比賽成績公告勾選回傳1-->
	<input type="hidden" name="d_action" value="news_insert" />
  <input type="submit" value="確定送出">
</fieldset>
</form>
    <li>
    <label>File :</label>
    <input type="file" name="file" id="file" multiple>
    <div id="resultArea"></div>
    </fieldset>
    </li>
</ol>
<script>
$(document).ready(function(){
	$("#file").on("change", function(){
		var input = $(this);
		var inputLength = input[0].files.length; //No of files selected
		var file;
		var formData = new FormData();
    var file_news_id = $("#news_id").val();

		for (var i = 0; i < inputLength; i++) {
			file = input[0].files[i];
			formData.append( 'file[]', file);
      formData.append( 'file_news_id', file_news_id);
		}
		//send POST request to uploadFile.php
		$.ajax({
			url: "uploadFile.php",
			type: "POST",
			data: formData,
					processData: false,
					contentType: false,
					beforeSend: function(){
						$(".loading").show();
					}
		}).done(function( data ) {
			$(".loading").hide();
			$("#resultArea").append(data);
			input.val('');
		});
	});
});
/** 
uploadFile.php的刪除處理
*/ 
function del_file()
{
  var status = confirm("Are you sure delete this file？");  
  if(status==true)
  {
    //delete element
    var delete_file = $("#delete_file_path").val();
    var div_class = $("#div_class").val();
    var file_news_id = $("#news_id").val();
    var file_id = $("#new_file_id").val();
    $.ajax({
			url:"uploadFile.php",
      type:"POST",
      data:{
				delete_file:delete_file,
        file_news_id:file_news_id,
        file_id:file_id,
				},
      success(html){
				console.log(delete_file);
        $( "." + div_class).remove();
        }
    });
  }
 }
</script>
<?

include ('foot.php');//include copyright and tel
?>



