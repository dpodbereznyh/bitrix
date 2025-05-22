$(document).ready(function () {
    $('.reviews-item__info-container').each(function () {
        var textContainer = $(this).find('.reviews-item__text');
        var textContainerComment = $(this).find('.reviews-item__text comment');
        var showMoreBtn = $(this).find('.reviews-item__text-more');
        var originalHeight = textContainerComment.height();
        if (originalHeight > 150) {
            textContainer.css('max-height', '150px');
            showMoreBtn.show();
        }

        showMoreBtn.click(function () {
            if (textContainer.css('max-height') === '150px') {
                textContainer.css('max-height', 'none');
                showMoreBtn.text('Скрыть');
            } else {
                textContainer.css('max-height', '150px');
                showMoreBtn.text('Показать больше');
            }
        });
    });
});
