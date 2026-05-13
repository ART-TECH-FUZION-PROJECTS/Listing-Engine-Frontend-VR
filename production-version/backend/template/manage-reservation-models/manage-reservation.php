<?php

/**
 * Manage Reservations Template.
 *
 * @package ListingEngineFrontend
 */

if (! defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <!-- This hidden h2 and the empty notice container catch WordPress admin notices before they get moved into our custom header. -->
    <h2 class="lef-admin-notice-placeholder"></h2>


    <div id="lef-reserv-main-wrapper" class="lef-global-plugin-wrapper">
        <div class="lef-reserv-header">
            <div class="lef-reserv-title-wrap">
                <div class="lef-reserv-title-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <path d="M3 10h18"></path>
                        <path d="M8 14h.01"></path>
                        <path d="M12 14h.01"></path>
                        <path d="M16 14h.01"></path>
                        <path d="M8 18h.01"></path>
                        <path d="M12 18h.01"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="lef-reserv-title">Reservation</h1>
                    <p class="lef-reserv-subtitle">Manage reservation requests with clear status tracking.</p>
                </div>
            </div>

            <div class="lef-reserv-summary" aria-live="polite">
                Total Reservations
                <span class="lef-reserv-summary-count" id="lef-reserv-total-count">0</span>
            </div>
        </div>

        <section class="lef-reserv-search-section" aria-label="Reservation search">
            <div class="lef-reserv-search-box" id="lef-reserv-search-box">
                <span class="lef-reserv-search-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </span>
                <input class="lef-reserv-search-input" id="lef-reserv-search-input" type="search"
                    placeholder="Search by reservation number or property title..." autocomplete="off">
                <button class="lef-reserv-search-clear" id="lef-reserv-search-clear" type="button"
                    aria-label="Clear search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
        </section>

        <nav class="lef-reserv-tabs" aria-label="Reservation status tabs">
            <button class="lef-reserv-tab lef-reserv-tab-active" id="lef-reserv-tab-pending" type="button"
                data-lef-reserv-tab="pending" aria-pressed="true">
                Pending
                <span class="lef-reserv-tab-count" id="lef-reserv-count-pending">0</span>
            </button>
            <button class="lef-reserv-tab" id="lef-reserv-tab-completed" type="button"
                data-lef-reserv-tab="completed" aria-pressed="false">
                Completed
                <span class="lef-reserv-tab-count" id="lef-reserv-count-completed">0</span>
            </button>
            <button class="lef-reserv-tab" id="lef-reserv-tab-rejected" type="button"
                data-lef-reserv-tab="rejected" aria-pressed="false">
                Rejected
                <span class="lef-reserv-tab-count" id="lef-reserv-count-rejected">0</span>
            </button>
        </nav>

        <section class="lef-reserv-list-shell" aria-label="Reservation list">
            <div class="lef-reserv-list-head">
                <h2 class="lef-reserv-list-title" id="lef-reserv-list-title">Pending Reservations</h2>
                <div class="lef-reserv-bulk-actions">
                    <div class="lef-reserv-select-all-wrap">
                        <input type="checkbox" id="lef-reserv-select-all" class="lef-reserv-checkbox">
                        <label for="lef-reserv-select-all">Select All</label>
                    </div>

                    <!-- Status Change Group -->
                    <div id="lef-reserv-bulk-status-wrap" class="lef-reserv-bulk-status-wrap" style="display: none;">
                        <select id="lef-reserv-bulk-status-select" class="lef-reserv-bulk-select">
                            <option value="">Change Status</option>
                            <option value="pending">Move to Pending</option>
                            <option value="completed">Mark Completed</option>
                            <option value="rejected">Mark Rejected</option>
                        </select>
                        <button type="button" id="lef-reserv-bulk-status-apply" class="lef-reserv-bulk-btn lef-reserv-btn-primary">Apply</button>
                    </div>

                    <button type="button" id="lef-reserv-delete-selected" class="lef-reserv-bulk-btn lef-reserv-btn-danger" style="display:none;">Delete Selected (<span id="lef-reserv-selected-count">0</span>)</button>
                    <button type="button" id="lef-reserv-delete-all-rejected" class="lef-reserv-bulk-btn lef-reserv-btn-danger-outline" style="display:none;">Delete All Rejected</button>
                </div>
            </div>

            <div class="lef-reserv-card-list" id="lef-reserv-card-list"></div>

            <div class="lef-reserv-empty" id="lef-reserv-empty">
                <div class="lef-reserv-empty-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"></path>
                        <path d="M9 10h6"></path>
                        <path d="M9 14h4"></path>
                    </svg>
                </div>
                <p class="lef-reserv-empty-title">No reservations found</p>
                <p class="lef-reserv-empty-text">Try another status tab or search keyword.</p>
            </div>

            <div class="lef-reserv-pagination" id="lef-reserv-pagination">
                <span class="lef-reserv-pagination-text" id="lef-reserv-pagination-text">Showing 0 of 0</span>
                <div class="lef-reserv-pagination-controls" id="lef-reserv-pagination-controls"></div>
            </div>
        </section>
    </div>

</div>