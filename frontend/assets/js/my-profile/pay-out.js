/**
 * pay-out.js
 *
 * Handles Payout Details form validation and AJAX submission.
 * Prefix: lef-payout-
 */

(function ($) {
  "use strict";

  const LefPayout = {
    init() {
      this.cacheDOM();
      this.bindEvents();
    },

    cacheDOM() {
      this.$panel = $("#lef-payout-panel");
      this.$form = $("#lef-payout-form");
      this.$submitBtn = this.$form.find('.lef-payout-save-btn');
    },

    bindEvents() {
      // Delegate form submission
      $(document).on("submit", "#lef-payout-form", this.handleSubmit.bind(this));
    },

    handleSubmit(e) {
      e.preventDefault();

      // Clear previous errors
      this.$panel.find(".lef-payout-field-error").removeClass("is-visible").text("");

      const formData = {
        action: "lef_save_payout_details",
        nonce: lefMyProfileData.nonce,
        holder_name: $("#lef-payout-holder-name").val().trim(),
        ifsc: $("#lef-payout-ifsc-code").val().trim(),
        bank_name: $("#lef-payout-bank-name").val().trim(),
        account_no: $("#lef-payout-account-number").val().trim(),
        upi_id: $("#lef-payout-upi-id").val().trim(),
      };

      // 1. Precise Validation
      let errors = 0;
      const requiredFields = [
        { id: "holder_name", inputId: "holder-name", msg: "Bank holder name is required." },
        { id: "ifsc", inputId: "ifsc-code", msg: "IFSC code is required for bank transfers." },
        { id: "bank_name", inputId: "bank-name", msg: "Please specify the bank name." },
        { id: "account_no", inputId: "account-number", msg: "Account number is required." },
      ];

      requiredFields.forEach((field) => {
        if (!formData[field.id]) {
          this.showError(field.inputId, field.msg);
          errors++;
        }
      });

      if (errors > 0) {
        if (window.LEF_Toast) {
          window.LEF_Toast.show("Please correct the errors in the form.", "error");
        }
        return;
      }

      // 2. Visual Feedback (Loading state)
      this.setLoading(true);

      // 3. AJAX Submission
      $.ajax({
        url: lefMyProfileData.ajax_url,
        type: "POST",
        data: formData,
        success: (response) => {
          if (response.success) {
            if (window.LEF_Toast) {
              window.LEF_Toast.show(response.data.message || "Payout details saved successfully.", "success");
            }
          } else {
            if (window.LEF_Toast) {
              window.LEF_Toast.show(response.data.message || "Failed to save details.", "error");
            }
          }
        },
        error: () => {
          if (window.LEF_Toast) {
            window.LEF_Toast.show("Server error. Please try again later.", "error");
          }
        },
        complete: () => {
          this.setLoading(false);
        },
      });
    },

    setLoading(isLoading) {
      if (isLoading) {
        this.$submitBtn.prop("disabled", true).css("opacity", "0.7").text("Saving...");
      } else {
        this.$submitBtn.prop("disabled", false).css("opacity", "1").html(`
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <path d="M17 21v-8H7v8"></path>
                        <path d="M7 3v5h8"></path>
                    </svg>
                    Save Details
                `);
      }
    },

    showError(inputId, msg) {
      const $field = $(`#lef-payout-${inputId}`).closest(".lef-payout-field");
      $field.find(".lef-payout-field-error").addClass("is-visible").text(msg);
    },
  };

  // Initialize on document ready
  $(document).ready(function () {
    LefPayout.init();
  });
})(jQuery);
