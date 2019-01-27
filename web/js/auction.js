function Auction() {
    this.items = window.items || [];

    this.tick = function (timestamp) {
        this.items.forEach(function(item, key, arr) {

            var now = Math.round(Date.now() / 1000);

            if (item.lastUpdate + (item.step_time ) <= now) {
                var diff = now - (item.start_time);
                var steps = Math.round(diff / (item.step_time));
                item.current_price = Math.round(item.start_price - (steps * item.step_price));
                item.lastUpdate += item.step_time ;
                if (item.current_price < item.end_price) {
                    item.current_price = item.end_price;
                    item.timerId = null;
                }

                item.priceColumn.html(item.current_price);
                var row = item.priceColumn.closest('tr');
                row.addClass('price-update');

                setTimeout(function (row) {
                    row.removeClass('price-update');
                }, 1000, row);

            }
        })
        requestAnimationFrame(this.tick.bind(this));
    };

    this.run = function () {

        var setUpdate = function (item, key, data) {
            var priceColumn = $('#auction-items table tr[data-key="' + item.id + '"] td[data-type="current-price"]');

            var now = Math.round(Date.now() / 1000);
            var diff = now - (item.start_time);
            var steps = Math.round(diff / (item.step_time));
            item.lastUpdate = item.start_time + (steps * item.step_time);
            item.priceColumn = priceColumn;
            item.priceColumn.html(item.current_price);
        };

        this.items.forEach(setUpdate.bind(this));
        requestAnimationFrame(this.tick.bind(this));
    }
}

document.addEventListener('DOMContentLoaded', function () {
    auction = new Auction();
    auction.run();
});