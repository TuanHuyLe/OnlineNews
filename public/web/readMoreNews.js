const readMore = {
    init: function () {
        readMore.loadData();
        readMore.event();
    },
    loadData: function (id) {
        $.ajax({
            url: `http://localhost:8000/api/news/${id}`,
            type: 'GET',
            success: res => {
                if (res.status === 200) {
                    console.log(res.data);
                }
            }
        });
    },
    event: function () {
        $('.read-more').off('click').on('click', function (e) {
            e.preventDefault();
            alert(1);
        })
    }
}

$(document).ready(function () {
    readMore.init();
});
