/**
 * Review Management JS
 * Handles AJAX fetching, searching, tabs, and bulk actions.
 * Prefix: lef-manag-revi-
 */
(function($) {
    'use strict';

    // State Management
    let currentStatus = 'pending';
    let currentPage = 1;
    let searchTerm = '';
    let fetching = false;

    /**
     * Cache Selectors
     */
    const $cardList = $('#lef-manag-revi-card-list');
    const $totalCount = $('#lef-manag-revi-total-count');
    const $searchInput = $('#lef-manag-revi-search-input');
    const $searchBox = $('#lef-manag-revi-search-box');
    const $clearSearch = $('#lef-manag-revi-search-clear');
    const $emptyState = $('#lef-manag-revi-empty');
    const $pagination = $('#lef-manag-revi-pagination');
    const $paginationText = $('#lef-manag-revi-pagination-text');
    const $paginationControls = $('#lef-manag-revi-pagination-controls');
    const $listTitle = $('#lef-manag-revi-list-title');

    // Bulk Selectors
    const $selectAll = $('#lef-manag-revi-select-all');
    const $deleteSelected = $('#lef-manag-revi-delete-selected');
    const $deleteAllRejected = $('#lef-manag-revi-delete-all-rejected');
    const $selectedCount = $('#lef-manag-revi-selected-count');
    const $bulkStatusWrap = $('#lef-manag-revi-bulk-status-wrap');
    const $bulkStatusSelect = $('#lef-manag-revi-bulk-status-select');
    const $bulkStatusApply = $('#lef-manag-revi-bulk-status-apply');

    /**
     * Initialization
     */
    function init() {
        fetchData();

        // Bind Events
        $('.lef-manag-revi-tab').on('click', handleTabSwitch);
        $searchInput.on('input', handleSearch);
        $searchInput.on('focus', () => $searchBox.addClass('lef-manag-revi-search-focused'));
        $searchInput.on('blur', () => $searchBox.removeClass('lef-manag-revi-search-focused'));
        $clearSearch.on('click', clearSearch);
        $paginationControls.on('click', '.lef-manag-revi-page-btn', handlePagination);

        // Bulk Actions
        $selectAll.on('change', handleSelectAll);
        $cardList.on('change', '.lef-manag-revi-item-checkbox', updateBulkUI);
        $deleteSelected.on('click', () => handleBulkAction('delete', 'selected'));
        $deleteAllRejected.on('click', () => handleBulkAction('delete', 'all_rejected'));
        $bulkStatusApply.on('click', () => handleBulkAction('status'));
    }

    /**
     * Fetch Data via AJAX
     */
    function fetchData() {
        if (fetching) return;
        fetching = true;

        $cardList.css('opacity', '0.5');

        $.ajax({
            url: lefReviewData.ajax_url,
            type: 'POST',
            data: {
                action: 'lef_manag_revi_fetch_data',
                nonce: lefReviewData.nonce,
                status: currentStatus,
                search: searchTerm,
                page: currentPage
            },
            success: function(response) {
                if (response.success) {
                    renderDashboard(response.data);
                } else {
                    if (window.LEF_Toast) {
                        window.LEF_Toast.show(response.data.message || 'Failed to fetch reviews', 'error');
                    }
                }
            },
            error: function() {
                if (window.LEF_Toast) {
                    window.LEF_Toast.show('Server error. Please try again.', 'error');
                }
            },
            complete: function() {
                fetching = false;
                $cardList.css('opacity', '1');
            }
        });
    }

    /**
     * Render the Dashboard list and controls
     */
    function renderDashboard(data) {
        // Update Stats
        $totalCount.text(data.total_db);
        $('#lef-manag-revi-count-pending').text(data.counts.pending);
        $('#lef-manag-revi-count-approved').text(data.counts.approved);
        $('#lef-manag-revi-count-rejected').text(data.counts.rejected);

        // Update UI Text
        const statusLabel = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
        $listTitle.text(statusLabel + ' Reviews');

        // Handle Empty State
        if (data.items.length === 0) {
            $cardList.html('').hide();
            $emptyState.addClass('lef-manag-revi-empty-visible');
            $pagination.hide();
            updateBulkUI(); 
            return;
        }

        $emptyState.removeClass('lef-manag-revi-empty-visible');
        $cardList.show();
        $pagination.show();

        // Render Cards
        let cardsHtml = '';
        data.items.forEach((item, index) => {
            const serialNo = ((data.current_page - 1) * data.per_page) + index + 1;
            const stars = '★'.repeat(Math.round(item.rating)) + '☆'.repeat(5 - Math.round(item.rating));
            
            cardsHtml += `
            <article class="lef-manag-revi-card" data-id="${item.id}">
                <div class="lef-manag-revi-card-checkbox-wrap">
                    <input type="checkbox" class="lef-manag-revi-item-checkbox lef-manag-revi-checkbox" data-id="${item.id}">
                </div>
                <div class="lef-manag-revi-sno">${serialNo}</div>
                <div class="lef-manag-revi-card-info">
                    <div class="lef-manag-revi-field">
                        <span class="lef-manag-revi-field-label">Property</span>
                        <span class="lef-manag-revi-field-value" title="${item.property_title || 'N/A'}">${item.property_title || 'N/A'}</span>
                    </div>
                    <div class="lef-manag-revi-field">
                        <span class="lef-manag-revi-field-label">User</span>
                        <span class="lef-manag-revi-field-value" title="${item.user_name || 'N/A'}">${item.user_name || 'N/A'}</span>
                    </div>
                    <div class="lef-manag-revi-field">
                        <span class="lef-manag-revi-field-label">Rating</span>
                        <span class="lef-manag-revi-field-value" style="color: #ffb100;" title="${item.rating} / 5">${stars} (${item.rating})</span>
                    </div>
                    <div class="lef-manag-revi-field lef-manag-revi-field-status">
                        <span class="lef-manag-revi-field-label">Status</span>
                        <span class="lef-manag-revi-status-badge" data-lef-manag-revi-status="${item.status}">
                            ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                        </span>
                    </div>
                </div>
                <div class="lef-manag-revi-card-actions">
                    <a class="lef-manag-revi-view-btn" href="admin.php?page=lef-manage-reviews&action=view&id=${item.id}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        View
                    </a>
                </div>
            </article>`;
        });
        $cardList.html(cardsHtml);

        // Render Pagination
        const totalItems = data.total_matching;
        const totalPages = Math.ceil(totalItems / data.per_page);
        const from = ((data.current_page - 1) * data.per_page) + 1;
        const to = Math.min(data.current_page * data.per_page, totalItems);

        $paginationText.text(`Showing ${from}-${to} of ${totalItems}`);

        let pagHtml = '';
        pagHtml += `<button class="lef-manag-revi-page-btn" data-page="prev" ${data.current_page === 1 ? 'disabled' : ''}>&laquo;</button>`;
        for (let i = 1; i <= totalPages; i++) {
            pagHtml += `<button class="lef-manag-revi-page-btn ${i === data.current_page ? 'lef-manag-revi-page-active' : ''}" data-page="${i}">${i}</button>`;
        }
        pagHtml += `<button class="lef-manag-revi-page-btn" data-page="next" ${data.current_page === totalPages ? 'disabled' : ''}>&raquo;</button>`;
        $paginationControls.html(pagHtml);

        // Reset Selection on Render
        $selectAll.prop('checked', false);
        updateBulkUI();
    }

    /**
     * Event Handlers
     */
    function handleTabSwitch() {
        const $this = $(this);
        if ($this.hasClass('lef-manag-revi-tab-active')) return;

        $('.lef-manag-revi-tab').removeClass('lef-manag-revi-tab-active').attr('aria-pressed', 'false');
        $this.addClass('lef-manag-revi-tab-active').attr('aria-pressed', 'true');

        currentStatus = $this.data('lef-manag-revi-tab');
        currentPage = 1;
        fetchData();
    }

    function handleSearch() {
        const val = $(this).val();
        $clearSearch.toggleClass('lef-manag-revi-search-clear-visible', val.length > 0);

        if (val.length >= 2 || val.length === 0) {
            searchTerm = val;
            currentPage = 1;
            clearTimeout(window.lefReviewSearchTimer);
            window.lefReviewSearchTimer = setTimeout(fetchData, 400);
        }
    }

    function clearSearch() {
        $searchInput.val('');
        searchTerm = '';
        currentPage = 1;
        $clearSearch.removeClass('lef-manag-revi-search-clear-visible');
        fetchData();
    }

    function handlePagination() {
        const $this = $(this);
        const pageAction = $this.data('page');

        if (pageAction === 'prev') currentPage--;
        else if (pageAction === 'next') currentPage++;
        else currentPage = parseInt(pageAction);

        fetchData();
    }

    /**
     * Bulk Action Logic
     */
    function handleSelectAll() {
        const checked = $(this).prop('checked');
        $('.lef-manag-revi-item-checkbox').prop('checked', checked);
        updateBulkUI();
    }

    function updateBulkUI() {
        const selectedCount = $('.lef-manag-revi-item-checkbox:checked').length;
        const totalOnPage = $('.lef-manag-revi-item-checkbox').length;
        
        $selectedCount.text(selectedCount);
        
        $deleteSelected.toggle(selectedCount > 0);
        $bulkStatusWrap.toggle(selectedCount > 0);

        // "Delete All" visibility - ONLY for rejected
        $deleteAllRejected.toggle(currentStatus === 'rejected');

        $selectAll.prop('checked', selectedCount === totalOnPage && totalOnPage > 0);

        // Update status options based on tab
        $bulkStatusSelect.find('option').show();
        if (currentStatus === 'pending') {
            $bulkStatusSelect.find('option[value="pending"]').hide();
        } else if (currentStatus === 'approved') {
            $bulkStatusSelect.find('option[value="approved"]').hide();
        } else if (currentStatus === 'rejected') {
            $bulkStatusSelect.find('option[value="rejected"]').hide();
        }
    }

    function handleBulkAction(type, mode) {
        let ids = [];
        let message = '';
        let ajaxAction = 'lef_manag_revi_delete_reviews';
        let extraData = {};

        if (type === 'delete') {
            if (mode === 'selected') {
                ids = $('.lef-manag-revi-item-checkbox:checked').map(function() { return $(this).data('id'); }).get();
                if (!ids.length) return;
                message = `Are you sure you want to delete ${ids.length} selected reviews?`;
            } else if (mode === 'all_rejected') {
                message = 'Are you sure you want to delete ALL rejected reviews? This cannot be undone.';
            }
            extraData = { mode: mode, ids: ids };
        } else if (type === 'status') {
            const newStatus = $bulkStatusSelect.val();
            if (!newStatus) {
                if (window.LEF_Toast) {
                    window.LEF_Toast.show('Please select a status first.', 'error');
                }
                return;
            }
            ids = $('.lef-manag-revi-item-checkbox:checked').map(function() { return $(this).data('id'); }).get();
            if (!ids.length) return;
            message = `Are you sure you want to move ${ids.length} reviews to ${newStatus}?`;
            ajaxAction = 'lef_manag_revi_bulk_status_change';
            extraData = { status: newStatus, ids: ids };
        }

        if (!window.LEF_Confirm) {
            if (confirm(message)) executeBulkAction(ajaxAction, extraData);
            return;
        }

        window.LEF_Confirm.open({
            title: 'Confirm Action',
            message: message
        }, (confirmed) => {
            if (confirmed) executeBulkAction(ajaxAction, extraData);
        });
    }

    function executeBulkAction(action, data) {
        $.ajax({
            url: lefReviewData.ajax_url,
            type: 'POST',
            data: {
                action: action,
                nonce: lefReviewData.nonce,
                ...data
            },
            success: function(res) {
                if (res.success) {
                    if (window.LEF_Toast) {
                        window.LEF_Toast.show(res.data.message, 'success');
                    }
                    $bulkStatusSelect.val('');
                    fetchData();
                } else {
                    if (window.LEF_Toast) {
                        window.LEF_Toast.show(res.data.message || 'Action failed', 'error');
                    }
                }
            }
        });
    }

    $(document).ready(init);

})(jQuery);
