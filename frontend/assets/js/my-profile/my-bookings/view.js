/**
 * view.js
 *
 * Handles the Reservation Details independent view.
 * Uses fully delegated, DOM-tolerant approach to work
 * correctly with AJAX-loaded dashboard screens.
 *
 * Prefix: lef-mb-view-
 *
 * @package ListingEngineFrontend
 */

(function ($) {
    "use strict";

    /**
     * Helper: format a datetime string into a readable format.
     * @param {string} dateStr - MySQL datetime string
     * @returns {string}
     */
    function formatFullDate(dateStr) {
        if (!dateStr) return "-";
        const date = new Date(dateStr);
        if (isNaN(date)) return dateStr;
        return date.toLocaleDateString("en-US", {
            month: "long", day: "numeric", year: "numeric"
        }) + " " + date.toLocaleTimeString("en-US", {
            hour: "2-digit", minute: "2-digit"
        });
    }

    /**
     * Helper: format a date-only string.
     * @param {string} dateStr
     * @returns {string}
     */
    function formatSimpleDate(dateStr) {
        if (!dateStr) return "-";
        const date = new Date(dateStr);
        if (isNaN(date)) return dateStr;
        return date.toLocaleDateString("en-US", {
            month: "long", day: "numeric", year: "numeric"
        });
    }

    /**
     * Helper: capitalize the first letter.
     * @param {string} str
     * @returns {string}
     */
    function capitalize(str) {
        if (!str) return "";
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    /**
     * Show the detail view and populate with reservation data.
     * This is the primary exported function, called from my-bookings.js.
     *
     * @param {object} data - Reservation data object from AJAX response.
     */
    function show(data) {
        if (!data) return;

        const $panel     = $("#lef-my-book-panel");
        const $container = $("#lef-mb-view-detail-card");

        // Guard: ensure the container exists in the DOM
        if ($container.length === 0) {
            console.error("LEF View: #lef-mb-view-detail-card not found in DOM. Check view.php is included.");
            return;
        }

        // --- Populate Property Info ---
        $container.find("#lef-mb-view-prop-title").text(data.property_title || "N/A");
        $container.find("#lef-mb-view-updated-at").text(formatFullDate(data.updated_at));
        $container.find("#lef-mb-view-property-link").attr("href", data.property_url || "#");

        // --- Populate Status Badge ---
        $container.find("#lef-mb-view-status")
            .text(capitalize(data.status))
            .attr("data-status", data.status);

        // --- Populate Request Details ---
        $container.find("#lef-mb-view-res-number").text(data.reservation_number || "-");
        $container.find("#lef-mb-view-total-price").text(
            "₹" + parseFloat(data.total_price || 0).toLocaleString("en-IN", { minimumFractionDigits: 2 })
        );
        $container.find("#lef-mb-view-created-at").text(formatFullDate(data.created_at));

        // --- Parse reserve_date JSON ---
        try {
            const dates = typeof data.reserve_date === "string"
                ? JSON.parse(data.reserve_date)
                : data.reserve_date;
            $container.find("#lef-mb-view-check-in").text(formatSimpleDate(dates.check_in));
            $container.find("#lef-mb-view-check-out").text(formatSimpleDate(dates.check_out));
        } catch (e) {
            $container.find("#lef-mb-view-check-in").text("-");
            $container.find("#lef-mb-view-check-out").text("-");
        }

        // --- Parse total_guests JSON ---
        try {
            const guests = typeof data.total_guests === "string"
                ? JSON.parse(data.total_guests)
                : data.total_guests;
            const parts = [];
            if (guests.adults   > 0) parts.push(guests.adults   + " adults");
            if (guests.children > 0) parts.push(guests.children + " children");
            if (guests.infants  > 0) parts.push(guests.infants  + " infants");
            $container.find("#lef-mb-view-total-guests").text(parts.join(", ") || "1 Guest");
        } catch (e) {
            $container.find("#lef-mb-view-total-guests").text("1 Guest");
        }

        // --- Switch Views ---
        // Hide the list-screen children: header, nav, section (list+pagination)
        $panel.find("> header, > nav, > section").hide();
        // Show the detail card
        $container.show();

        // Scroll to top of panel
        $("html, body").animate({ scrollTop: $panel.offset().top - 80 }, 400);
    }

    /**
     * Hide the detail view and restore the list view.
     */
    function hide() {
        const $panel     = $("#lef-my-book-panel");
        const $container = $("#lef-mb-view-detail-card");

        $container.hide();
        $panel.find("> header, > nav, > section").fadeIn(250);
    }

    // --- Delegated "Back" button --- works even before DOM is built
    $(document).on("click", "#lef-mb-view-back-btn", function () {
        hide();
    });

    // --- Expose to window so my-bookings.js can call it ---
    window.LefMbView = {
        show: show,
        hide: hide
    };

})(jQuery);
