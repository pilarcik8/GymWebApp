document.addEventListener('DOMContentLoaded', function () {
    var overlay = document.getElementById('modal-overlay');
    var btn = document.getElementById('modal-close');
    if (!overlay) return;
    function closeModal() { overlay.style.display = 'none'; }
    overlay.addEventListener('click', function(e){
        if (e.target === overlay) closeModal();
    });
    if (btn) btn.addEventListener('click', closeModal);
    // close on Escape
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeModal(); });
});
