/**
 * Standalone Reservation Detail View JS
 * Handles status updates and UI feedback on the individual reservation page.
 */
(function ($) {
  "use strict";

  $(document).ready(function () {
    const $statusSelect = $("#lef-reserv-edit-status-select");
    const $statusBadge = $("#lef-reserv-edit-current-status");
    const $saveBtn = $("#lef-reserv-edit-save-btn");
    const $saveNote = $("#lef-reserv-edit-save-note");

    /**
     * Status Labels Map
     */
    const statusLabels = {
      pending: "Pending",
      completed: "Completed",
      rejected: "Rejected",
    };

    /**
     * Update the UI badge to reflect current selection
     */
    function lefReservUpdateStatusBadge() {
      const val = $statusSelect.val();
      $statusBadge.attr("data-lef-reserv-edit-status", val);
      $statusBadge.text(statusLabels[val] || val);
      $saveNote.removeClass("lef-reserv-edit-save-note-visible");
    }

    // Listen for selection changes
    $statusSelect.on("change", lefReservUpdateStatusBadge);

    /**
     * Save Status Change via AJAX
     */
    $saveBtn.on("click", function () {
      const id = $(this).data("id");
      const status = $statusSelect.val();

      if (!id) {
        lefToaster("error", "Invalid Reservation ID");
        return;
      }

      // Visual feedback
      $saveBtn
        .prop("disabled", true)
        .addClass("lef-reserv-btn-loading")
        .text("Saving...");
      $saveNote.removeClass("lef-reserv-edit-save-note-visible");

      $.ajax({
        url: lefReservData.ajax_url,
        type: "POST",
        data: {
          action: "lef_reserv_update_status",
          nonce: lefReservData.nonce,
          id: id,
          status: status,
        },
        success: function (response) {
          if (response.success) {
            lefReservUpdateStatusBadge();
            if (response.data && response.data.message) {
              $saveNote
                .addClass("lef-reserv-edit-save-note-visible")
                .text(response.data.message);
              if (window.LEF_Toast) {
                window.LEF_Toast.show(response.data.message, "success");
              }
            }
          } else {
            const errorMsg =
              response.data && response.data.message
                ? response.data.message
                : "Update failed";
            if (window.LEF_Toast) {
              window.LEF_Toast.show(errorMsg, "error");
            }
          }
        },
        error: function () {
          if (window.LEF_Toast) {
            window.LEF_Toast.show("Server error. Please try again.", "error");
          }
        },

        complete: function () {
          $saveBtn.prop("disabled", false).removeClass("lef-reserv-btn-loading")
            .html(`
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <path d="M17 21v-8H7v8"></path>
                            <path d="M7 3v5h8"></path>
                        </svg>
                        Save
                    `);
        },
      });
    });
  });
})(jQuery);
