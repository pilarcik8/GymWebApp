document.addEventListener('DOMContentLoaded', function () {
    function showModal(title, htmlContent) {
        var modal = document.getElementById('desc-modal');
        if (!modal) return;
        var titleEl = document.getElementById('desc-modal-title');
        var bodyEl = document.getElementById('desc-modal-body');
        if (titleEl) titleEl.textContent = title;
        if (bodyEl) bodyEl.innerHTML = htmlContent;
        modal.classList.remove('d-none');
        modal.setAttribute('aria-hidden', 'false');
    }

    function hideModal() {
        var modal = document.getElementById('desc-modal');
        if (!modal) return;
        var bodyEl = document.getElementById('desc-modal-body');
        var titleEl = document.getElementById('desc-modal-title');
        modal.classList.add('d-none');
        modal.setAttribute('aria-hidden', 'true');
        if (bodyEl) bodyEl.innerHTML = '';
        if (titleEl) titleEl.textContent = '';
    }

    var buttons = Array.from(document.getElementsByClassName('show-desc'));
    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var desc = btn.getAttribute('data-desc') || '';
            var title = btn.getAttribute('data-title') || '';
            showModal(title, desc);
        });
    });

    var closeBtn = document.getElementById('desc-modal-close');
    if (closeBtn) closeBtn.addEventListener('click', hideModal);

    var backdrop = document.querySelector('.desc-modal-backdrop');
    if (backdrop) backdrop.addEventListener('click', hideModal);

    // close on escape
    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape') hideModal();
    });
});
