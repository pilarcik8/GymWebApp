document.addEventListener('click', function(e){
    if (!e.target.classList.contains('toggle-desc')) return;
    const id = e.target.dataset.id;
    const row = document.querySelector('.desc-row[data-id="' + id + '"]');
    if (!row) return;
    const isHidden = row.style.display === 'none' || row.style.display === '';
    row.style.display = isHidden ? 'table-row' : 'none';
    e.target.textContent = isHidden ? 'Skryť popis' : 'Popis';
});