<?php
//新增活動
if (isset($_POST['news_id']) && isset($_POST['subject']))
{
  $news_id = $_POST['news_id'];
  $kind = 0;
  $start_date = $_POST['start_date'];
  $subject = $_POST['subject'];
  $content = $_POST['content'];
  $email   = $_POST['email'];
  $tel     = $_POST['tel'];
  $us_id   = $_SESSION['us_id'];
  $u_id    = $_SESSION['u_id'];
  if($_POST['result']=='')
  {
    $result = 0;
  }else
  {
    $result = 1;
  }
	$sql_insert_news = "<INSERT NEWS QUERY>";
  if($sql_insert_news_res = $conn->Execute($sql_insert_news))
  {
    echo "
          <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
          <script>
          alert('活動已上傳成功！');
          location.href='https://xxx.tw/index.php'
          </script>";
    exit();
  }
  
  //如果有上傳就執行insert sql


}



/*刪除附檔function 先確認有無 delete_file的POST request */
if (isset($_POST['delete_file']) && isset($_POST['file_news_id']) && isset($_POST['file_id'])) {
    $delete_file_name = $_POST['delete_file'];
    $news_id = $_POST['file_news_id'];
    $f_id = $_POST['file_id'];
    deletefile($delete_file_name,$news_id,$f_id);
}




/**
 * EX：上傳文件為upload_file_name
 * upload_file_name：未結案之研習清單.xlsx
 * tmp_name：/tmp/phpHjdbDc
 * type：.xlsx 
 * file_name：tmp_1615431318_未結案之研習清單.xlsx
 */
if (isset($_FILES['file'])) {
  CheckUploadFormat();
  $uploads_dir = '../file/news';
  foreach ($_FILES['file']['error'] as $key => $error)
  {
    if ($error == 'UPLOAD_ERR_OK') {
      $div = time();
      $upload_file_name = $_FILES['file']['name'][$key];
      $tmp_name = $_FILES['file']['tmp_name'][$key];
      $type = pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);;//附檔名
      $file_name = "tmp_" . time() . "_" . $_FILES['file']['name'][$key];//檔案儲存名稱變更為: tmp_時間戳_FileName
      if(move_uploaded_file($tmp_name, "$uploads_dir/$file_name"))//將上載的文件移動到新位置 
      {
        
        $sql_new_file_id = 'SELECT MAX(id)+1 FROM File_table';//選取最大值的編號並加一作為新檔案的
        $new_file_id_result = $conn->Execute($sql_new_file_id)->FetchRow();//取得建立活動附檔編號
        $new_file_id = $new_file_id_result['f_id'];
        $file_news_id = $_POST['file_news_id'];//eng_race_file的活動編號
        $file_path = "$uploads_dir/$file_name";
        $file_size = filesize($file_path);

        $sql_insert_news_file = '<INSERT FILE QUERY>';
        if($conn->Execute($sql_insert_news_file))
        {
          echo "<script>console.log(file_news_id)</script>";
          echo "<div class='$div'>";
          echo "<li><a href='"."$uploads_dir/$file_name"."' target='_BLANK' id='upload_file_name'>".$upload_file_name."</a>";
          echo "<input type='hidden' value='".$new_file_id."' name='new_file_id' id='new_file_id' />";
          echo "<input type='hidden' value='".$file_name."' name='delete_file_path' id='delete_file_path' />";
          echo "<input type='hidden' value='".$div."' name='div_class' id='div_class' />";
          echo " <input type='button' onclick=\"del_file()\"  name='delete_file' id='delete_file' value='刪除附檔'/></li>";
          echo "</div>";
        }

        
      }    
    }else
    {
      echo "<script>alert('File has something wrong! please try again!');</script>";
      header("https://xxx.tw/admin/index.php");
      exit();
    }
  }
}

/**function list */

//刪除附檔
function deletefile($delete_file_name,$news_id,$f_id)
{
    unlink("../file/news/$delete_file_name");
    /*$file_delete_sql = "DELETE FROM eng_race_file WHERE news_id = '$news_id' AND f_id = '$f_id'";
    $file_delete_res = $conn->Execute($file_delete_sql) or trigger_error('Upload file has something wrong!',E_USER_ERROR);*/
    echo "<script>alert('已刪除！');</script>";
    exit();
}

//確認附檔大小不超過10MB以及限制格式
function CheckUploadFormat()
{
  if (isset($_FILES['file'])) 
  {
    $errors = array();
    $maxsize = 1000000;
    $acceptable = array(
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel',
    );

    if (($_FILES['file']['size'] >= $maxsize) || ($_FILES["file"]["size"] == 0)) 
    {
        $errors[] = '檔案過大，最大10MB!';
    }

    if ((!in_array($_FILES['file']['type'], $acceptable)) && (!empty($_FILES["file"]["type"]))) 
    {
        $errors[] = '只限上傳PDF、JPEG、JPG、GIF、PNG、DOC、XLSX、XLS檔案';
    }

    if (!count($errors) === 0) 
    {
        foreach ($errors as $error) 
        {
            echo '<script>alert("' . $error . '");</script>';
        }
        die();
    }
}
}

?>
