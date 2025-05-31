<?php
if (isset($_FILES['excelFile']) && isset($_POST['email'])) {
    $to = $_POST['email'];
    $fileTmp = $_FILES['excelFile']['tmp_name'];
    $fileName = $_FILES['excelFile']['name'];

    $subject = "OTTO Store Data Excel Report";
    $message = "Please find the attached OTTO store report.";

    $content = chunk_split(base64_encode(file_get_contents($fileTmp)));
    $uid = md5(uniqid(time()));
    $filename = basename($fileName);
    $header = "From: OTTO Reports <no-reply@yourdomain.com>\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $nmessage = "--".$uid."\r\n";
    $nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $nmessage .= $message."\r\n\r\n";
    $nmessage .= "--".$uid."\r\n";
    $nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
    $nmessage .= "Content-Transfer-Encoding: base64\r\n";
    $nmessage .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $nmessage .= $content."\r\n\r\n";
    $nmessage .= "--".$uid."--";

    if (mail($to, $subject, $nmessage, $header)) {
        echo "Mail sent successfully";
    } else {
        echo "Mail sending failed";
    }
} else {
    echo "Invalid request";
}
?>
