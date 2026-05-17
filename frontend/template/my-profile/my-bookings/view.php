<?php

/**
 * View Booking Detail Template (Independent View).
 *
 * This template matches the provided mockup exactly and uses the 'lef-mb-view-' prefix.
 * It is toggled via JS within the My Bookings panel.
 *
 * @package ListingEngineFrontend
 */
?>
<div class="lef-mb-view-container" id="lef-mb-view-detail-card" style="display: none;">
    <!-- Top Nav: Back and Title -->
    <div class="lef-mb-view-header">
        <button type="button" class="lef-mb-view-back-btn" id="lef-mb-view-back-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
            Back
        </button>
        <h2 class="lef-mb-view-page-title">Reservation Details</h2>
        <span class="lef-mb-view-status-badge" id="lef-mb-view-status">-</span>
    </div>

    <!-- Property Summary Section -->
    <div class="lef-mb-view-property-card">
        <div class="lef-mb-view-prop-info">
            <p class="lef-mb-view-label">Property Name</p>
            <h3 class="lef-mb-view-value" id="lef-mb-view-prop-title">-</h3>
        </div>
        <div class="lef-mb-view-prop-meta">
            <p class="lef-mb-view-label">Last Updated</p>
            <p class="lef-mb-view-value" id="lef-mb-view-updated-at">-</p>
        </div>
        <div class="lef-mb-view-prop-action">
            <a href="#" target="_blank" class="lef-mb-view-btn-primary" id="lef-mb-view-property-link">View Property</a>
        </div>
    </div>


    <!-- Request Details Column -->
    <div class="lef-mb-view-details-card">
        <div class="lef-mb-view-card-header">
            <div class="lef-mb-view-icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <h4 class="lef-mb-view-card-title">Request Details</h4>
        </div>

        <div class="lef-mb-view-card-body">
            <div class="lef-mb-view-info-row">
                <span class="lef-mb-view-item-label">Reservation No</span>
                <span class="lef-mb-view-item-value" id="lef-mb-view-res-number">-</span>
            </div>
            <div class="lef-mb-view-info-row">
                <span class="lef-mb-view-item-label">Check-in</span>
                <span class="lef-mb-view-item-value" id="lef-mb-view-check-in">-</span>
            </div>
            <div class="lef-mb-view-info-row">
                <span class="lef-mb-view-item-label">Check-out</span>
                <span class="lef-mb-view-item-value" id="lef-mb-view-check-out">-</span>
            </div>
            <div class="lef-mb-view-info-row">
                <span class="lef-mb-view-item-label">Total Guests</span>
                <span class="lef-mb-view-item-value" id="lef-mb-view-total-guests">-</span>
            </div>
            <div class="lef-mb-view-info-row">
                <span class="lef-mb-view-item-label">Total Price</span>
                <span class="lef-mb-view-item-value" id="lef-mb-view-total-price">-</span>
            </div>
            <div class="lef-mb-view-info-row">
                <span class="lef-mb-view-item-label">Request Date</span>
                <span class="lef-mb-view-item-value" id="lef-mb-view-created-at">-</span>
            </div>
        </div>
    </div>

</div>