(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modal-add-credit');
        if (!modal) return;

        const modalId = document.getElementById('modal-account-id');
        const modalMessage = document.getElementById('modal-message');
        const amountInput = document.getElementById('modal-amount');
        const cancelBtn = document.getElementById('modal-button-cancel');

        function openModal(id, name) {
            if (modalId) modalId.value = id || '';
            if (modalMessage) modalMessage.textContent = 'zákazníkovy ' + (name || '');
            if (amountInput) amountInput.value = '';
            modal.classList.add('active');
            if (amountInput) amountInput.focus();
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.open-add-credit').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name') || '';
                openModal(id, name);
            });
        });

        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        // zavri ak kliknes mimo modal
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    });

    // AJAX filtrovanie zákazníkov podľa mena/emailu
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('customer-search');
        const tableDiv = document.getElementById('div-table');

        if (!searchInput || !tableDiv) return;

        // Reaguj na zmenu textu v inpute
        searchInput.addEventListener('input', function () {
            const query = this.value;

            // počkáme 300 ms
            clearTimeout(window.searchDebounce);
            window.searchDebounce = setTimeout(function () {
                fetchCustomers(query);
            }, 300);
        });

        // Zavolá server a načíta prefiltrovaný zoznam zákazníkov podľa `query`
        function fetchCustomers(query) {
            const xhr = new XMLHttpRequest();

            // aktuálna cesta bez parametrov
            const basePath = window.location.pathname.split('?')[0] || '/';
            // parametre pre náš router: controller = reception, action = index a filter = q
            const url = basePath + '?c=reception&a=index&q=' + encodeURIComponent(query);

            // inicializácia GET požiadavky (asynchrónne)
            xhr.open('GET', url, true);
            // označíme požiadavku ako AJAX
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            // Spracovanie odpovede zo servera
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        updateTable(xhr.responseText);
                    } else {
                        console.error('Error fetching customers:', xhr.status, xhr.statusText);
                    }
                }
            };

            // Odošli požiadavku na server
            xhr.send();
        }

        function updateTable(html) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newDiv = doc.getElementById('div-table');

            if (newDiv && tableDiv) {
                tableDiv.innerHTML = newDiv.innerHTML;
            } else if (tableDiv) {
                tableDiv.innerHTML = html;
            }
        }
    });
})();
