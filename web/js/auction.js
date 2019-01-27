function Auction() {
    this.items = window.items || [];
    this.timers = {};
    this.columns = {};

    this.tick = function (item) {
        var now = Math.round(Date.now() / 1000);
        var diff = now - item.start_time;
        var steps = Math.round(diff / item.step_time);
        var currentPrice = item.start_price - (steps * item.step_price);

        if (currentPrice < item.end_price) {
            currentPrice = item.end_price;
            this.timers[item.id] = null;
        } else {
            timerId = setTimeout(this.tick.bind(this), item.step_time * 1000, item);
            this.timers[item.id] = timerId;
        }

        this.columns[item.id].html(currentPrice);
        var row = this.columns[item.id].closest('tr');
        row.addClass('price-update');
        setTimeout(function (row) {
            row.removeClass('price-update');
        }, 1000, row);
    };

    this.run = function () {

        var setUpdate = function (item, key, data) {

            var priceColumn = $('#auction-items table tr[data-key="'+ item.id +'"] td[data-type="current-price"]');

            var now = Math.round(Date.now() / 1000);
            var diff = now - item.start_time;
            var steps = Math.round(diff / item.step_time);
            var lastUpdate = item.start_time + (steps * item.step_time);

            var nextUpdate = now - lastUpdate;

            var timerId = setTimeout(this.tick.bind(this), nextUpdate * 1000, item);
            this.timers[item.id] = timerId;
            this.columns[item.id] = priceColumn;
        };

        this.items.forEach(setUpdate.bind(this));
    }
}

document.addEventListener('DOMContentLoaded', function () {
    auction = new Auction();
    auction.run();
});