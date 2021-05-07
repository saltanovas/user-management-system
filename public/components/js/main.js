$(document).ready(function () {
    $('tbody').on('click', 'tr', function (e) {
        let target = $(e.target);
        if (target.is('i') || target.is('button') || target.is('a') || target.is('li')) {
                event.stopPropagation();
            return;
        }
    });
    $('select').selectpicker();
});
