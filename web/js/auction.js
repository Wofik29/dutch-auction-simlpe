function Auction() {
    this.items = window.items || [];
    this.ids = [];
    this.statuses = {
        1: 'draft',
        2: 'template',
        3: 'selling',
        4: 'sold',
        5: 'close',
    };

    this.tick = function (timestamp) {
        this.items.forEach(function (item, key, arr) {
            if (item.is_end) return;

            var now = Math.round(Date.now() / 1000);

            if (item.lastUpdate + (item.step_time) <= now) {
                var diff = now - (item.start_time);
                var steps = Math.round(diff / (item.step_time));
                item.current_price = Math.round(item.start_price - (steps * item.step_price));
                item.lastUpdate += item.step_time;
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

    this.update = function () {
        var items = auction.items;
        $.ajax({
            url: '/auction/updater',
            contentType: 'application/json',
            data: {
                ids: auction.ids || [],
            },
            //global: false,
            success: function (data) {
                if (data) {
                    data.forEach(function (item, key, arr) {
                        switch (item.action) {
                            case 'drop':
                                var delete_key = null;

                                for (var key in auction.items) {
                                    if (auction.items[key].id == item.id) {
                                        delete_key = key;
                                        var row = auction.items[key].priceColumn.closest('tr');
                                        row.remove();
                                        break;
                                    }
                                }
                                if (delete_key) {
                                    auction.items.splice(delete_key, 1);
                                    key = auction.ids.indexOf(Number(item.id));
                                    auction.ids.splice(key, 1);
                                }
                                break;
                            case 'new_item':
                                var new_item = item.item;
                                var now = Math.round(Date.now() / 1000);
                                var diff = now - (new_item.start_time);
                                var steps = Math.round(diff / (new_item.step_time));
                                new_item.lastUpdate = new_item.start_time + (steps * new_item.step_time);
                                $('#auction-items tbody').prepend(item.html);
                                new_item.priceColumn  = $('#auction-items table tr[data-key="' + new_item.id + '"] td[data-type="current-price"]');

                                new_item.priceColumn.html(new_item.current_price);
                                new_item.is_end = false;

                                auction.items.push(new_item);
                                auction.ids.push(new_item.id);
                                break;
                        }
                    })
                }
                setTimeout(auction.update, 1000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                setTimeout(auction.update, 300);
            }
        });
    }

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
            auction.ids.push(item.id);
        };

        this.items.forEach(setUpdate.bind(this));
        requestAnimationFrame(this.tick.bind(this));
        this.update();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    auction = new Auction();
    auction.run();
});