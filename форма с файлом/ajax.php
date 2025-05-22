<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

if (!empty($_POST['form-name']) && !empty($_POST['form-tel'])) {

    $data = [
        "NAME" => htmlspecialchars($_POST['form-name']),
        "PHONE" => htmlspecialchars($_POST['form-tel']),
        "EMAIL" => !empty($_POST['form-email']) ? htmlspecialchars($_POST['form-email']) : '', // Проверка на пустоту
        "MESSAGE" => !empty($_POST['form-mess']) ? htmlspecialchars($_POST['form-mess']) : '', // Проверка на пустоту
    ];

    // Обработка файла, если он был загружен
    $fileId = null; // Идентификатор файла
    if (!empty($_FILES['form-file']['name'])) {
        // Сохраняем файл и получаем его ID
        $fileId = CFile::SaveFile($_FILES['form-file'], 'vacancy'); // Замените 'your_folder' на нужную папку
    }

    // Отправка события
    $eventResult = CEvent::Send("FORM_VACANCY", 's1', $data, "N", "55", $fileId ? [$fileId] : []); // Передаем ID файла, если он есть

    // Проверка результата отправки
    if ($eventResult) {
        echo '<h3 class="white">Спасибо! Ваше сообщение отправлено, Скоро с Вами свяжется оператор</h3>';
    } else {
        echo '<h3 class="white">Произошла ошибка при отправке сообщения.</h3>';
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, заполните все обязательные поля.']);
}