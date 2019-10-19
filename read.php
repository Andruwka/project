<?php 
    include_once __DIR__.'/vendor/autoload.php';
    //include_once("lib/QrReader.php");

    $allowed_filetypes = array('.jpg','.gif','.bmp','.png'); // Допустимые типы файлов
    $max_filesize = 10*524288; // Максимальный размер файла в байтах (в данном случае он равен 0.5 Мб).
    $filename = $_FILES['pic']['name']; // В переменную $filename заносим имя файла (включая расширение).
    $upload_path = './tmp/'; // Папка, куда будут загружаться файлы .
    $ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // В переменную $ext заносим расширение загруженного файла.

    if(!in_array($ext,$allowed_filetypes)) // Сверяем полученное расширение со списком допутимых расширений. 
    die('Данный тип файла не поддерживается.');
    
    if(filesize($_FILES['pic']['tmp_name']) > $max_filesize) // Проверим размер загруженного файла.
    die('Фаил слишком большой.');
    
    if(!is_writable($upload_path)) // Проверяем, доступна ли на запись папка.
    die('Невозможно загрузить фаил в папку. Установите права доступа - 777.');

        // Загружаем фаил в указанную папку.
    if(move_uploaded_file($_FILES['pic']['tmp_name'],$upload_path . $filename))
    {
        echo 'Ваш фаил успешно загружен ';
        echo '<br><br>';
        echo '<img src="' . $upload_path . $filename . '" width="300" >';
        $qrcode = new QrReader($upload_path.$filename);
        $text = $qrcode -> text();
        echo $text;
    } else {
        echo 'При загрузке возникли ошибки. Попробуйте ещё раз.';
    }
?>