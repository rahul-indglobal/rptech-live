define([
    "jquery"
], function ($){
    return function (widget) {
        widget.prototype._initialize =function(){
                this.next_data_cache = "";
                this.pagesLoaded = [];
                this._initPagesCount();
                this.disabled = 1;
                var isValidConfiguration = this._validate();
                if (!isValidConfiguration) {
                    $('.amscroll-navbar').each(function(){
                        this.parentElement.removeChild(this);
                    })
                    return;
                }
                this.disabled = 0;
                this.type = this.options['actionMode'];
                this._preloadPages();
                this._hideToolbars();
                var self = this;
                $(window).scroll(function() {
                    self._initPaginator();
                });
                setTimeout(function(){
                    self._initPaginator();
                }, 7000);
                this._initProgressBar();
            };
    }
});