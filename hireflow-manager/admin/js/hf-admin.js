jQuery(document).ready(function($) {

    // Simple tab switching logic for settings page
    $('.hf-nav-tabs a').on('click', function(e) {
        e.preventDefault();
        
        $('.hf-nav-tabs a').removeClass('nav-tab-active');
        $('.hf-tab-content').removeClass('active');
        
        $(this).addClass('nav-tab-active');
        $($(this).attr('href')).addClass('active');
        
        // update URL hash so reload keeps the tab open
        if (history.pushState) {
            history.pushState(null, null, $(this).attr('href'));
        }
    });
    
    // Auto-open tab from URL hash on load
    if (window.location.hash) {
        $('.hf-nav-tabs a[href="' + window.location.hash + '"]').click();
    }

    $('.hf-confirm-action').on('click', function(e) {
        e.preventDefault();
        
        let action = $(this).data('action');
        let msg = action === 'delete' ? 'Are you absolutely sure you want to DELETE ALL DATA? This cannot be undone.' : 'Reset settings to defaults?';
        
        if (confirm(msg)) {
            // TODO: write the ajax for this later, just alert for now
            alert('Action confirmed (not yet implemented).');
        }
    });

    $('#hf-test-email').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let spinner = $('#hf-test-email-spinner');
        
        spinner.addClass('is-active');
        btn.prop('disabled', true);
        
        // fake delay for now
        setTimeout(function() {
            spinner.removeClass('is-active');
            btn.prop('disabled', false);
            alert('Test email logic not hooked up yet!');
        }, 1000);
    });

});
