document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const mobileToggle = document.querySelector('.hf-mobile-toggle');
    const navWrapper = document.querySelector('.hf-nav-wrapper');
    if (mobileToggle && navWrapper) {
        mobileToggle.addEventListener('click', () => {
            navWrapper.classList.toggle('d-none');
            navWrapper.classList.toggle('d-flex');
            navWrapper.classList.toggle('flex-column');
            navWrapper.classList.toggle('position-absolute');
            navWrapper.classList.toggle('bg-dark');
            navWrapper.classList.toggle('w-100');
            navWrapper.classList.toggle('start-0');
            navWrapper.style.top = '100%';
            navWrapper.style.zIndex = '99';
            navWrapper.style.padding = '1rem';
        });
    }

    // 2. Avatar Dropdown Toggle
    const avatar = document.querySelector('.hf-avatar');
    const dropdownMenu = document.querySelector('.hf-dropdown-menu');
    if (avatar && dropdownMenu) {
        avatar.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('d-none');
        });
        document.addEventListener('click', () => {
            if (!dropdownMenu.classList.contains('d-none')) {
                dropdownMenu.classList.add('d-none');
            }
        });
    }

    // 3. Active Nav Link Highlighting
    const currentUrl = window.location.href;
    document.querySelectorAll('.hf-nav-links a, .hf-sidebar-link').forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add('active');
            link.style.color = 'var(--hf-accent)';
        }
    });

    // 4. Toast Notification System
    window.hf_showToast = function(message, type = 'success') {
        let container = document.querySelector('.hf-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'hf-toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `hf-toast ${type}`;
        
        let icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';
        if (type === 'warning') icon = 'exclamation-triangle';

        toast.innerHTML = `<i class="fas fa-${icon}"></i> <span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(50px)';
            toast.style.transition = 'all 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    };

    // 5. Delete Application Modal Confirmation
    const deleteBtns = document.querySelectorAll('.hf-delete-app-btn');
    if (deleteBtns.length > 0) {
        // Create modal
        const modalHtml = `
            <div class="hf-modal-overlay" id="hfDeleteModal">
                <div class="hf-modal-content text-center">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h3 class="mb-3">Are you sure?</h3>
                    <p class="text-muted mb-4">Do you really want to delete this application? This process cannot be undone.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button class="hf-btn hf-btn-secondary" id="hfCancelDelete">Cancel</button>
                        <button class="hf-btn hf-btn-danger" id="hfConfirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const modal = document.getElementById('hfDeleteModal');
        const btnCancel = document.getElementById('hfCancelDelete');
        const btnConfirm = document.getElementById('hfConfirmDelete');
        let deleteUrl = '';

        deleteBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                deleteUrl = btn.getAttribute('href');
                modal.classList.add('active');
            });
        });

        btnCancel.addEventListener('click', () => modal.classList.remove('active'));
        btnConfirm.addEventListener('click', () => {
            if (deleteUrl) window.location.href = deleteUrl;
        });
    }

    // 6. Resume Upload Preview
    const fileInput = document.querySelector('.hf-file-input');
    const uploadArea = document.querySelector('.hf-upload-area');
    if (fileInput && uploadArea) {
        const textSpan = uploadArea.querySelector('span');
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                textSpan.textContent = `Selected: ${e.target.files[0].name}`;
            }
        });
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('drag-over');
        });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                textSpan.textContent = `Selected: ${e.dataTransfer.files[0].name}`;
            }
        });
    }

    // 7. Form Validation
    const appForm = document.querySelector('.hf-app-form');
    if (appForm) {
        appForm.addEventListener('submit', (e) => {
            let isValid = true;
            
            // Clear old errors
            appForm.querySelectorAll('.hf-error-msg').forEach(el => el.remove());
            appForm.querySelectorAll('.hf-form-control').forEach(el => el.style.borderColor = 'var(--hf-secondary)');

            const showError = (input, msg) => {
                isValid = false;
                input.style.borderColor = 'var(--hf-danger)';
                const error = document.createElement('small');
                error.className = 'hf-error-msg text-danger d-block mt-1';
                error.textContent = msg;
                input.parentNode.appendChild(error);
            };

            // Required fields
            appForm.querySelectorAll('[required]').forEach(input => {
                if (!input.value.trim()) {
                    showError(input, 'This field is required.');
                }
            });

            // Date validation
            const appDate = appForm.querySelector('input[name="app_date"]');
            const intDate = appForm.querySelector('input[name="interview_date"]');
            if (appDate && intDate && appDate.value && intDate.value) {
                if (new Date(intDate.value) < new Date(appDate.value)) {
                    showError(intDate, 'Interview date cannot be before application date.');
                }
            }

            if (!isValid) e.preventDefault();
        });
    }

    // 8. Character Counter
    const notesTextarea = document.querySelector('textarea[name="personal_notes"]');
    if (notesTextarea) {
        const counter = document.createElement('small');
        counter.className = 'text-muted d-block text-end mt-1';
        counter.textContent = `${notesTextarea.value.length} / 500`;
        notesTextarea.parentNode.appendChild(counter);

        notesTextarea.addEventListener('input', () => {
            const len = notesTextarea.value.length;
            counter.textContent = `${len} / 500`;
            if (len > 500) {
                counter.classList.add('text-danger');
                counter.classList.remove('text-muted');
            } else {
                counter.classList.remove('text-danger');
                counter.classList.add('text-muted');
            }
        });
    }

    // 9. AJAX Search (debounce)
    const searchInput = document.querySelector('#hf-ajax-search');
    const resultsContainer = document.querySelector('#hf-search-results');
    let searchTimeout;

    if (searchInput && resultsContainer && typeof hfData !== 'undefined') {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const term = e.target.value.trim();

            if (term.length < 2) {
                resultsContainer.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                resultsContainer.innerHTML = '<div class="text-center py-3"><div class="hf-spinner mx-auto"></div></div>';
                
                const formData = new FormData();
                formData.append('action', 'hf_search_applications');
                formData.append('nonce', hfData.nonce);
                formData.append('term', term);

                fetch(hfData.ajaxurl, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        resultsContainer.innerHTML = data.data;
                    } else {
                        resultsContainer.innerHTML = `<p class="text-muted text-center">${data.data}</p>`;
                    }
                })
                .catch(() => {
                    resultsContainer.innerHTML = '<p class="text-danger text-center">Search failed.</p>';
                });
            }, 500);
        });
    }
});
