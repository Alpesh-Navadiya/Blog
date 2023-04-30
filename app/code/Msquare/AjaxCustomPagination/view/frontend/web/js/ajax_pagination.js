require(
    ["jquery"],
    function($){
    $(document).on('click', 'a.ajax-page', function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            showLoader: true,
            success: function(response){
                $('.custom-ajax-data').html(response.content);
            }
        });
    });
    $(document).on('change', 'select.limiter-options.ajax-limiter-options', function(event){
        $.ajax({
            url: this.value,
            type: 'POST',
            showLoader: true,
            success: function(response){
                $('.custom-ajax-data').html(response.content);
            }
        });
    });
})
