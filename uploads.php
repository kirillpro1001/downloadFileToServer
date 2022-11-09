<?php
if (!empty($_FILES['attachment'])) {
    $file = $_FILES['attachment'];

    $srcFileName = $file['name'];
    $filePath = $file['tmp_name'];
    $newFilePath = __DIR__ . '/uploads/' . $srcFileName;
    $extension = pathinfo($srcFileName, PATHINFO_EXTENSION);
    $allowedExtension = ['jpg','png','gif'];
    $fileWidth = 1280;
    $fileHeight = 720;

    $sizeImage = getimagesize($filePath);

    if ($sizeImage[0]>$fileHeight || $sizeImage[1] > $fileWidth) {
        $error = "Размер изображения выше разрешения 1280*720";
    }

    elseif ($file['error'] == UPLOAD_ERR_INI_SIZE) {
        $error = "Размер файла превышает допустимые размеры конфигурационного файла сервера";
    }

    elseif ($file['size']>8388608) {
        $error = "Размер файла больше 8 Мб";
    }

    elseif (!in_array($extension,$allowedExtension)) {
        $error = "Ошибка расширения загружаемого файла";
    }

    elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "Ошибка при загрузке файла!";
    }
    elseif (file_exists($newFilePath)) {
        $error = "Ошибка! Такой файл уже существует";
    }
    elseif (!move_uploaded_file($file['tmp_name'], $newFilePath)) {
        $error = 'Ошибка при загрузке файла';
    } else {
        $result = __DIR__.'/uploads/' . $srcFileName;
    }
}
?>
<html>
<head>
    <title>Загрузка файла</title>
</head>
<body>
<?php if (!empty($error)): ?>
    <?= $error ?>
<?php elseif (!empty($result)): ?>
    <?= $result ?>
<?php endif; ?>
<br>
<form action="uploads.php" method="post" enctype="multipart/form-data">
    <input type="file" name="attachment">
    <input type="submit">
</form>
</body>
</html>