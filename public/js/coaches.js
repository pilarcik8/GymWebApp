document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.trainer-booking-form').forEach(function (form) {
        var startBtn = form.querySelector('.trainer-booking-start');
        var details  = form.querySelector('.trainer-booking-details');

        if (startBtn && details) {
            startBtn.addEventListener('click', function () {
                details.style.display = 'block';
                var initial = startBtn.closest('.trainer-booking-initial');
                if (initial) {
                    initial.style.display = 'none';
                }
            });
        }
    });
});

