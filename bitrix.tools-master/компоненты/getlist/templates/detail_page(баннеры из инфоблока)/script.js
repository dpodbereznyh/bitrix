document.addEventListener('DOMContentLoaded', () => {
    var pictureType2 = document.querySelector(
        '.layout-item--type-4 .layout-item__picture'
    );
    var textType2 = document.querySelector(
        '.layout-item--type-4 .layout-item__text-container'
    );
    if (typeof pictureType2 != null && typeof textType2 != null) {
        $(window).resize(function () {
            if (document.documentElement.clientWidth < 992) {
                var pictureType2Height = pictureType2.offsetHeight - 25;
                textType2.style['margin-top'] = pictureType2Height + 'px';
            } else {
                textType2.style['margin-top'] = '0';
            }
        });
    }
});
