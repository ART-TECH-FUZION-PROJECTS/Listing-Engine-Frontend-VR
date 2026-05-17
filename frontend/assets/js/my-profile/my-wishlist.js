/**
 * my-wishlist.js
 * Handles wishlist interactions in the My Profile dashboard.
 * Refactored with prefix: lef-mp-my-wish
 *
 * @package ListingEngineFrontend
 */
(function($) {
    'use strict';

    function lef_mp_my_wish_init() {
        const $container = $('.lef-mp-my-wish-container');
        if (!$container.length) return;

        const $grid = $('#lef-mp-my-wish-grid');
        const $selectToggle = $('#lef-mp-my-wish-select-toggle');
        const $removeSelected = $('#lef-mp-my-wish-remove-selected');
        const $selectedCount = $('#lef-mp-my-wish-selected-count');

        // 1. Selection Mode Toggle
        $selectToggle.on('click', function() {
            const isActive = $(this).toggleClass('is-active').hasClass('is-active');
            $(this).text(isActive ? 'Cancel' : 'Select');
            
            $grid.toggleClass('lef-mp-my-wish-grid-selecting', isActive);
            $('.lef-mp-my-wish-checkbox-container').toggle(isActive);
            
            if (!isActive) {
                $('.lef-mp-my-wish-item-checkbox').prop('checked', false);
                updateSelectedUI();
            }
        });

        // 2. Checkbox Change
        $container.on('change', '.lef-mp-my-wish-item-checkbox', function() {
            updateSelectedUI();
        });

        function updateSelectedUI() {
            const count = $('.lef-mp-my-wish-item-checkbox:checked').length;
            $selectedCount.text(count);
            $removeSelected.toggle(count > 0);
        }

        // 3. Remove Selected
        $removeSelected.on('click', function() {
            const selectedIds = $('.lef-mp-my-wish-item-checkbox:checked').map(function() {
                return $(this).data('id');
            }).get();

            if (selectedIds.length === 0) return;

            if (!window.LEF_Confirm) {
                removeSelectedProperties(selectedIds);
                return;
            }

            $('#lef-confirm-yes').text('Remove Selected');
            $('#lef-confirm-no').text('Cancel');

            window.LEF_Confirm.open({
                title: 'Remove Selected Items',
                message: `Are you sure you want to remove ${selectedIds.length} properties from your wishlist?`
            }, (confirmed) => {
                if (confirmed) {
                    removeSelectedProperties(selectedIds);
                }
            });
        });

        /** AJAX: Remove specific list of properties */
        function removeSelectedProperties(ids) {
            $.ajax({
                url: lefMyProfileData.ajax_url,
                type: 'POST',
                data: {
                    action: 'lef_bulk_remove_wishlist',
                    property_ids: ids,
                    nonce: lefMyProfileData.nonce
                },
                success: function(res) {
                    if (res.success) {
                        if (window.LEF_Toast) LEF_Toast.show(res.data.message, 'success');
                        location.reload();
                    } else {
                        if (window.LEF_Toast) LEF_Toast.show(res.data.message || 'Error removing items', 'error');
                    }
                }
            });
        }

        // 4. Image Carousel Logic
        $container.on('click', '.lef-mp-my-wish-nav', function(e) {
            e.stopPropagation();
            const $btn = $(this);
            const $cardImageCont = $btn.closest('.lef-mp-my-wish-card-image-container');
            const images = JSON.parse($cardImageCont.attr('data-images'));
            let current = parseInt($cardImageCont.attr('data-current'));
            const direction = $btn.hasClass('lef-mp-my-wish-nav-next') ? 1 : -1;

            current = (current + direction + images.length) % images.length;
            
            $cardImageCont.attr('data-current', current);
            $cardImageCont.find('.lef-mp-my-wish-card-image').attr('src', images[current]);
        });

        // 5. Individual Removal
        $container.on('click', '.lef-mp-my-wish-remove-btn', function(e) {
            if ($grid.hasClass('lef-mp-my-wish-grid-selecting')) return; 
            e.stopPropagation();
            const $btn = $(this);
            const propertyId = $btn.data('id');
            const $card = $btn.closest('.lef-mp-my-wish-card');

            if (!window.LEF_Confirm) {
                removeProperty(propertyId, $card);
                return;
            }

            // Update confirmation button labels
            $('#lef-confirm-yes').text('Remove');
            $('#lef-confirm-no').text('Cancel');

            window.LEF_Confirm.open({
                title: 'Remove from Wishlist',
                message: 'Are you sure you want to remove this property from your wishlist?'
            }, (confirmed) => {
                if (confirmed) {
                    removeProperty(propertyId, $card);
                }
            });
        });

        // 6. Bulk Removal (Clear All)
        $container.on('click', '#lef-mp-my-wish-clear-all', function() {
            if (!window.LEF_Confirm) {
                clearWishlist();
                return;
            }

            // Update confirmation button labels
            $('#lef-confirm-yes').text('Clear All');
            $('#lef-confirm-no').text('Cancel');

            window.LEF_Confirm.open({
                title: 'Clear Wishlist',
                message: 'Are you sure you want to remove ALL properties from your wishlist?'
            }, (confirmed) => {
                if (confirmed) {
                    clearWishlist();
                }
            });
        });

        // 7. Card Click Redirect
        $container.on('click', '.lef-mp-my-wish-card', function() {
            if ($grid.hasClass('lef-mp-my-wish-grid-selecting')) return;
            const redirectUrl = $(this).attr('data-redirect');
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        });

        /** AJAX: Remove single property */
        function removeProperty(id, $card) {
            $.ajax({
                url: lefMyProfileData.ajax_url,
                type: 'POST',
                data: {
                    action: 'lef_remove_from_wishlist',
                    property_id: id,
                    nonce: lefMyProfileData.nonce
                },
                success: function(res) {
                    if (res.success) {
                        $card.fadeOut(300, function() {
                            $(this).remove();
                            if ($('.lef-mp-my-wish-card').length === 0) {
                                location.reload();
                            }
                        });
                        if (window.LEF_Toast) LEF_Toast.show(res.data.message, 'success');
                    } else {
                        if (window.LEF_Toast) LEF_Toast.show(res.data.message || 'Error removing item', 'error');
                    }
                },
                error: function() {
                    if (window.LEF_Toast) LEF_Toast.show('Network error.', 'error');
                }
            });
        }

        /** AJAX: Clear entire wishlist */
        function clearWishlist() {
            $.ajax({
                url: lefMyProfileData.ajax_url,
                type: 'POST',
                data: {
                    action: 'lef_bulk_remove_wishlist',
                    nonce: lefMyProfileData.nonce
                },
                success: function(res) {
                    if (res.success) {
                        if (window.LEF_Toast) LEF_Toast.show(res.data.message, 'success');
                        location.reload();
                    } else {
                        if (window.LEF_Toast) LEF_Toast.show(res.data.message || 'Error clearing wishlist', 'error');
                    }
                },
                error: function() {
                    if (window.LEF_Toast) LEF_Toast.show('Network error.', 'error');
                }
            });
        }
    }

    // Initialize when the screen is loaded via AJAX
    $(document).on('lef_sidebar_screen_loaded', function(e, screen) {
        if (screen === 'my-wishlist') {
            lef_mp_my_wish_init();
        }
    });

})(jQuery);
