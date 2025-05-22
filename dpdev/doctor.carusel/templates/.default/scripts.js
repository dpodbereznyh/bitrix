document.addEventListener('DOMContentLoaded', function() {
    const arrPageTitles = document.getElementsByTagName('h2');
    const jacorList = document.getElementById('anhor-list');

    function createJacorMenu(items) {
        const ul = document.createElement('ul');
        Array.from(items).forEach(elem => {
            elem.setAttribute("id", elem.innerText)
            let jacor = document.createElement('a');
            jacor.href = "#" + elem.innerText;
            jacor.innerText = elem.innerText;
            jacorList.append(jacor);
        });
    }

    createJacorMenu(arrPageTitles);

    // Плавный скроллинг по якорям
    function smoothScroll(target, duration) {
        const offset = 100;
        const targetPosition = target.getBoundingClientRect().top + window.scrollY - offset;
        const startPosition = window.scrollY;
        const distance = targetPosition - startPosition;
        let startTime = null;

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = ease(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animation);
        }

        function ease(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }

        requestAnimationFrame(animation);
    }

    // Добавляем обработчики событий для ссылок
    document.querySelectorAll('#anhor-list a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault(); // Предотвращаем стандартное поведение ссылки
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            smoothScroll(targetElement, 1000); // Параметр 1000 - это длительность анимации в миллисекундах
        });
    });
    console.log('ok')
});