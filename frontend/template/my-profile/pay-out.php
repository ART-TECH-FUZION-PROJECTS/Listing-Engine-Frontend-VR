<?php

/**
 * Payout Details Sub-template.
 *
 * Handles display and update of bank/UPI information.
 *
 * @package ListingEngineFrontend
 */

if (! defined('ABSPATH')) {
    exit;
}

$user_id = get_current_user_id();

// Fetch current bank details
$bank_json = get_user_meta($user_id, 'bank_details', true);
$payout    = ! empty($bank_json) ? json_decode($bank_json, true) : array();

// Ensure arrays keys exist to avoid warnings
$holder_name = isset($payout['holder_name']) ? $payout['holder_name'] : '';
$ifsc        = isset($payout['ifsc']) ? $payout['ifsc'] : '';
$bank_name   = isset($payout['bank_name']) ? $payout['bank_name'] : '';
$account_no  = isset($payout['account_no']) ? $payout['account_no'] : '';
$upi_id      = isset($payout['upi_id']) ? $payout['upi_id'] : '';
?>

<div class="lef-payout-wrapper" id="lef-payout-panel">
    <header class="lef-payout-head lef-payout-p-m-b-common">
        <div>
            <h2 class="lef-payout-title" id="lef-payout-title">Payout Details</h2>
            <p class="lef-payout-subtitle">Add your bank and UPI details for receiving payouts.</p>
        </div>
        <span class="lef-payout-badge">Bank Information</span>
    </header>

    <div class="lef-payout-body">
        <div class="lef-payout-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 16v-4"></path>
                <path d="M12 8h.01"></path>
            </svg>
            <span>Please make sure your bank details are correct before saving to ensure seamless transfers.</span>
        </div>

        <form id="lef-payout-form" class="lef-payout-grid" novalidate>
            <!-- Bank Holder Name -->
            <div class="lef-payout-field">
                <label class="lef-payout-label" for="lef-payout-holder-name">Bank Holder Name</label>
                <input class="lef-payout-input" id="lef-payout-holder-name" name="holder_name" type="text"
                    placeholder="Enter bank holder name" value="<?php echo esc_attr($holder_name); ?>" required>
                <div class="lef-payout-field-error" id="lef-payout-holder-name-error"></div>
            </div>

            <!-- IFSC Code -->
            <div class="lef-payout-field">
                <label class="lef-payout-label" for="lef-payout-ifsc-code">IFSC Code</label>
                <input class="lef-payout-input" id="lef-payout-ifsc-code" name="ifsc" type="text"
                    placeholder="Enter IFSC code" value="<?php echo esc_attr($ifsc); ?>" required>
                <div class="lef-payout-field-error" id="lef-payout-ifsc-code-error"></div>
            </div>

            <!-- Bank Name -->
            <div class="lef-payout-field">
                <label class="lef-payout-label" for="lef-payout-bank-name">Bank Name</label>
                <input class="lef-payout-input" id="lef-payout-bank-name" name="bank_name" type="text"
                    placeholder="Enter bank name" value="<?php echo esc_attr($bank_name); ?>" required>
                <div class="lef-payout-field-error" id="lef-payout-bank-name-error"></div>
            </div>

            <!-- Account Number -->
            <div class="lef-payout-field">
                <label class="lef-payout-label" for="lef-payout-account-number">Bank Account Number</label>
                <input class="lef-payout-input" id="lef-payout-account-number" name="account_no" type="text"
                    placeholder="Enter bank account number" value="<?php echo esc_attr($account_no); ?>" inputmode="numeric" required>
                <div class="lef-payout-field-error" id="lef-payout-account-number-error"></div>
            </div>

            <!-- UPI ID -->
            <div class="lef-payout-field lef-payout-field-full">
                <label class="lef-payout-label" for="lef-payout-upi-id">UPI ID (Optional)</label>
                <input class="lef-payout-input" id="lef-payout-upi-id" name="upi_id" type="text"
                    placeholder="Enter UPI ID (e.g. username@bank)" value="<?php echo esc_attr($upi_id); ?>">
                <div class="lef-payout-field-error" id="lef-payout-upi-id-error"></div>
            </div>
        </form>
    </div>
    <div class="lef-payout-actions">
        <button class="lef-payout-save-btn" type="submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                <path d="M17 21v-8H7v8"></path>
                <path d="M7 3v5h8"></path>
            </svg>
            Save Details
        </button>
    </div>
</div>
<?php
// End of file
