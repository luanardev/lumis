
let loaderElement = $('#laravel-livewire-loader');
let loaderTimeout = null;

loaderElement.addClass('hide');

Livewire.hook('message.sent', () => {
    if (loaderTimeout == null) {
        loaderTimeout = setTimeout(() => {
            loaderElement.removeClass('hide');
            loaderElement.addClass('show');
        }, parseInt(loaderElement.dataset.showDelay));
    }
});

Livewire.hook('message.received', () => {
    if (loaderTimeout != null) {
        loaderElement.removeClass('show');
        loaderElement.addClass('hide');
        clearTimeout(loaderTimeout);
        loaderTimeout = null;
    }
})