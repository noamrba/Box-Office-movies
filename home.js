const productContainers = document.querySelectorAll('.movie');
productContainers.forEach(container => {
    container.addEventListener('click', function() {
        const movie_id = container.id.replace('movie', '');
        window.location.href = 'movie_details.php?id=' + movie_id;
    });
});