
let loaderElement = $('#laravel-livewire-loader');
let loaderTimeout = null;

loaderElement.addClass('hide');

Livewire.hook('message.sent', function(){
    if (loaderTimeout == null) {
        loaderTimeout = setTimeout(function() {
            loaderElement.removeClass('hide');
            loaderElement.addClass('show');
        }, 1000);
    }
});

Livewire.hook('message.received', function(){

    if (loaderTimeout != null) {
        loaderElement.removeClass('show');
        loaderElement.addClass('hide');
        clearTimeout(loaderTimeout);
        loaderTimeout = null;
    }
});