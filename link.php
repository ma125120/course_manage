
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        embed {
            height:600px;
		width:100%;
        }
    </style>
</head>
<body>

<?php 
echo '<embed src="'.$_POST['info'].'" type="application/pdf;base64" />';
?>

</body>
</html>
