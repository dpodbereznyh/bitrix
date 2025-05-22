$(document).ready(function() {
    $('.modal_vacancy .call__form').on('submit', function(event) {
        event.preventDefault(); // Предотвращаем стандартное поведение формы

        // Собираем данные формы
        var formData = new FormData(this);

        // Отправляем AJAX-запрос
        $.ajax({
            url: '/local/include/ajax/form_vacancy.php', // Укажите URL для обработки формы
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Обработка успешного ответа
                $('.modal_vacancy .modal__content').html(response); // Вставляем HTML-сообщение в модальное окно
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Обработка ошибки
                alert('Произошла ошибка при отправке формы. Пожалуйста, попробуйте еще раз.');
                console.error(textStatus, errorThrown); // Выводим ошибку в консоль для отладки
            }
        });
    });
});