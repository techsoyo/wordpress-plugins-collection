/**
 * FAQ Chatbot Admin JavaScript
 *
 * This file contains all the admin-side functionality for the FAQ chatbot.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const addNewFaqBtn = document.getElementById('add-new-faq');
    const faqForm = document.getElementById('faq-form');
    const faqTable = document.getElementById('faq-table');
    const faqSearchInput = document.getElementById('faq-search');
    const faqBulkActions = document.getElementById('bulk-action-selector-top');
    const faqBulkApply = document.getElementById('doaction');
    
    /**
     * Initialize the admin interface
     */
    function init() {
        if (addNewFaqBtn) {
            addNewFaqBtn.addEventListener('click', showFaqForm);
        }
        
        if (faqForm) {
            faqForm.addEventListener('submit', handleFaqSubmit);
        }
        
        if (faqTable) {
            setupTableActions();
        }
        
        if (faqSearchInput) {
            faqSearchInput.addEventListener('input', debounce(searchFaqs, 300));
        }
        
        if (faqBulkApply) {
            faqBulkApply.addEventListener('click', handleBulkActions);
        }
        
        // Setup visibility toggle for chatbot settings
        setupSettingsToggle();
    }
    
    /**
     * Show FAQ form for adding new entries
     */
    function showFaqForm() {
        faqForm.classList.remove('hidden');
        document.getElementById('faq-question').focus();
        addNewFaqBtn.textContent = 'Cancel';
        addNewFaqBtn.removeEventListener('click', showFaqForm);
        addNewFaqBtn.addEventListener('click', hideFaqForm);
    }
    
    /**
     * Hide FAQ form
     */
    function hideFaqForm() {
        faqForm.classList.add('hidden');
        faqForm.reset();
        addNewFaqBtn.textContent = 'Add New FAQ';
        addNewFaqBtn.removeEventListener('click', hideFaqForm);
        addNewFaqBtn.addEventListener('click', showFaqForm);
    }
    
    /**
     * Handle FAQ form submission
     * @param {Event} e - Form submit event
     */
    function handleFaqSubmit(e) {
        e.preventDefault();
        
        const question = document.getElementById('faq-question').value.trim();
        const answer = document.getElementById('faq-answer').value.trim();
        const faqId = document.getElementById('faq-id').value;
        const nonce = document.getElementById('faq_chatbot_nonce').value;
        
        if (!question || !answer) {
            alert('Please fill in both question and answer fields.');
            return;
        }
        
        // Show loading state
        const submitBtn = faqForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;
        
        // Create AJAX request
        const data = new FormData();
        data.append('action', 'faq_chatbot_save_faq');
        data.append('question', question);
        data.append('answer', answer);
        data.append('nonce', nonce);
        
        if (faqId) {
            data.append('id', faqId);
        }
        
        // Send request
        fetch(ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reset form and refresh table
                hideFaqForm();
                location.reload();
            } else {
                alert('Error: ' + data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        })
        .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    }
    
    /**
     * Setup FAQ table action buttons (edit/delete)
     */
    function setupTableActions() {
        // Edit buttons
        const editButtons = document.querySelectorAll('.faq-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                editFaq(id);
            });
        });
        
        // Delete buttons
        const deleteButtons = document.querySelectorAll('.faq-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                if (confirm('Are you sure you want to delete this FAQ?')) {
                    deleteFaq(id);
                }
            });
        });
        
        // Bulk selection
        const checkAll = document.getElementById('cb-select-all-1');
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[name="faq[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    }
    
    /**
     * Edit an existing FAQ
     * @param {string} id - FAQ ID
     */
    function editFaq(id) {
        // Create AJAX request
        const data = new FormData();
        data.append('action', 'faq_chatbot_get_faq');
        data.append('id', id);
        data.append('nonce', document.getElementById('faq_chatbot_nonce').value);
        
        // Send request
        fetch(ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show form with FAQ data
                document.getElementById('faq-question').value = data.data.question;
                document.getElementById('faq-answer').value = data.data.answer;
                document.getElementById('faq-id').value = data.data.id;
                showFaqForm();
                document.querySelector('#faq-form button[type="submit"]').textContent = 'Update FAQ';
            } else {
                alert('Error: ' + data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    
    /**
     * Delete an FAQ
     * @param {string} id - FAQ ID
     */
    function deleteFaq(id) {
        // Create AJAX request
        const data = new FormData();
        data.append('action', 'faq_chatbot_delete_faq');
        data.append('id', id);
        data.append('nonce', document.getElementById('faq_chatbot_nonce').value);
        
        // Send request
        fetch(ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh table
                location.reload();
            } else {
                alert('Error: ' + data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    
    /**
     * Search FAQs
     */
    function searchFaqs() {
        const searchTerm = faqSearchInput.value.trim().toLowerCase();
        const rows = faqTable.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const question = row.querySelector('td.column-question').textContent.toLowerCase();
            const answer = row.querySelector('td.column-answer').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    /**
     * Handle bulk actions (delete)
     * @param {Event} e - Click event
     */
    function handleBulkActions(e) {
        if (faqBulkActions.value !== 'delete') return;
        
        const selectedFaqs = document.querySelectorAll('input[name="faq[]"]:checked');
        if (selectedFaqs.length === 0) {
            alert('Please select at least one FAQ to delete.');
            e.preventDefault();
            return;
        }
        
        if (!confirm(`Are you sure you want to delete ${selectedFaqs.length} FAQs?`)) {
            e.preventDefault();
        }
    }
    
    /**
     * Setup visibility toggles for chatbot settings
     */
    function setupSettingsToggle() {
        const visibilityRadios = document.querySelectorAll('input[name="faq_chatbot_visibility"]');
        const pageSelection = document.getElementById('faq-chatbot-pages');
        
        if (visibilityRadios.length && pageSelection) {
            visibilityRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    pageSelection.style.display = this.value === 'specific_pages' ? 'block' : 'none';
                });
            });
        }
    }
    
    /**
     * Debounce function for search input
     * @param {Function} func - Function to debounce
     * @param {number} wait - Wait time in milliseconds
     * @return {Function} Debounced function
     */
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    
    // Initialize admin
    init();
});