document.addEventListener('DOMContentLoaded', function() {
    var toggles = document.querySelectorAll('.table-toggle');
    toggles.forEach(function(btn) {
        var card = btn.closest('.table-card');
        if (!card) return;
        var icon = btn.querySelector('i');
        if (!icon) return;
        var isCollapsed = card.classList.contains('collapsed');
        if (isCollapsed) {
            icon.classList.remove('bi-chevron-double-down');
            icon.classList.add('bi-chevron-double-right');
        } else {
            icon.classList.remove('bi-chevron-double-right');
            icon.classList.add('bi-chevron-double-down');
        }
        btn.setAttribute('aria-expanded', (!isCollapsed).toString());

        btn.addEventListener('click', function() {
            var card = btn.closest('.table-card');
            if (!card) return;
            var icon = btn.querySelector('i');
            if (!icon) return;
            var nowCollapsed = card.classList.toggle('collapsed');
            if (nowCollapsed) {
                icon.classList.remove('bi-chevron-double-down');
                icon.classList.add('bi-chevron-double-right');
            } else {
                icon.classList.remove('bi-chevron-double-right');
                icon.classList.add('bi-chevron-double-down');
            }
            btn.setAttribute('aria-expanded', (!nowCollapsed).toString());
        });
    });
});

