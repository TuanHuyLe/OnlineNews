const category = {
    init: function () {
        category.load();
    },
    load: function () {
        $.ajax({
            url: constants.url + constants.getCategory,
            type: 'GET',
            success: res => {
                if (res.status === 200) {
                    let data = res.data;
                    let html = '';
                    $.each(data, (i, value) => {
                        html += `<li><a class="category-news" id="${value.id}" data-code="${value.code}" href="${value.code}">${value.name}</a></li>`
                    });
                    $('#data-menu-category').html(html);
                } else {
                    $('#data-menu-category').html('<h3>Danh sách thể loại trống</h3>');
                    console.log(res.message);x``
                }
            },
            error: e => {
                console.log(e);
            }
        });
    }
};

$(document).ready(() => {
    category.init();
});
