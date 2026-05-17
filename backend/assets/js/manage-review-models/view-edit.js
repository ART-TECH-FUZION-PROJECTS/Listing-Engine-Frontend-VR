/**
 * Standalone Review Detail View JS
 * Handles status updates and UI feedback on the individual review page.
 * Prefix: lef-manag-revi-edit-
 */
(function ($) {
  "use strict";

  $(document).ready(function () {
    const $statusSelect = $("#lef-manag-revi-edit-status-select");
    const $statusBadge = $("#lef-manag-revi-edit-current-status");
    const $saveBtn = $("#lef-manag-revi-edit-save-btn");
    const $saveNote = $("#lef-manag-revi-edit-save-note");

    /**
     * Status Labels Map
     */
    const statusLabels = {
      pending: "Pending",
      approved: "Approved",
      rejected: "Rejected",
    };

    /**
     * Update the UI badge to reflect current selection
     */
    function updateStatusBadge() {
      const val = $statusSelect.val();
      $statusBadge.attr("data-lef-manag-revi-edit-status", val);
      $statusBadge.text(statusLabels[val] || val);
      $saveNote.removeClass("lef-manag-revi-edit-save-note-visible");
    }

    // Listen for selection changes
    $statusSelect.on("change", updateStatusBadge);

    /**
     * Save Status Change via AJAX
     */
    $saveBtn.on("click", function () {
      const id = $(this).data("id");
      const status = $statusSelect.val();

      if (!id) {
        if (window.LEF_Toast) {
          window.LEF_Toast.show("Invalid Review ID", "error");
        }
        return;
      }

      // Visual feedback
      $saveBtn
        .prop("disabled", true)
        .text("Saving...");
      $saveNote.removeClass("lef-manag-revi-edit-save-note-visible");

      $.ajax({
        url: lefReviewData.ajax_url,
        type: "POST",
        data: {
          action: "lef_manag_revi_update_status",
          nonce: lefReviewData.nonce,
          id: id,
          status: status,
        },
        success: function (response) {
          if (response.success) {
            updateStatusBadge();
            if (response.data && response.data.message) {
              $saveNote
                .addClass("lef-manag-revi-edit-save-note-visible")
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
          $saveBtn.prop("disabled", false)
            .html(`
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:16px;height:16px;">
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
