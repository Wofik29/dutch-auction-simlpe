function Auction() {
    this.items = window.items || [];

    this.tick = function (timestamp) {
        this.items.forEach(function(item, key, arr) {
            if (item.is_end) return;

            var now = Math.round(Date.now() / 1000);

            if (item.lastUpdate + (item.step_time ) <= now) {
                var diff = now - (item.start_time);
                var steps = Math.round(diff / (item.step_time));
                item.current_price = Math.round(item.start_price - (steps * item.step_price));
                item.lastUpdate += item.step_time ;
                var row = item.priceColumn.closest('tr');
                if (item.current_price < item.end_price) {
                    item.current_price = item.end_price;
                    item.timerId = null;
                    item.is_end = true;
                    item.priceColumn.html(item.current_price);
                    row.addClass('price-end');
                } else {
                    item.priceColumn.html(item.current_price);
                    row.addClass('price-update');

                    setTimeout(function (row) {
                        row.removeClass('price-update');
                    }, 1000, row);
                }
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
            item.is_end = false;
        };

        this.items.forEach(setUpdate.bind(this));
        requestAnimationFrame(this.tick.bind(this));
    }
}

document.addEventListener('DOMContentLoaded', function () {
    auction = new Auction();
    auction.run();
});