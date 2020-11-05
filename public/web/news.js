const newsParams = {
    page: 1,
    limit: 5,
    category: ''
}

const news = {
    init: function () {
        news.loadData();
    },
    loadData: function () {
        $.ajax({
            url: 'http://localhost:8000/api/v1/news',
            data: {
                page: newsParams.page,
                limit: newsParams.limit,
                category: newsParams.category
            },
            type: 'GET',
            success: res => {
                if (res.status === 200) {
                    let html = '';
                    const data = res.data;
                    $.each(data, (i, value) => {
                        let date = new Date(value.created_at);
                        let formatted_date = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear()
                        html +=
                            `
                            <div class="card mb-4">
                                <img class="card-img-top" src='${value.image}' alt="Card image cap">
                                <div class="card-body">
                                    <h2 class="card-title">${value.title}</h2>
                                    <div>${value.shortDescription}</div>
                                    <a href="/tintuconline/home/news${value.id}" class="mt-2 btn btn-primary">Đọc thêm
                                        &rarr;</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Ngày đăng ${formatted_date}
                                </div>
                            </div>
                        `;
                    });
                    console.log(res.total);
                    $('.root').html(html);
                    news.paging(res.total, () => {
                        news.loadData();
                    });
                }
            }
        });
    },
    paging: function (totalRow, callback) {
        const totalPage = Math.ceil(totalRow / newsParams.limit);
        $('#pagination').twbsPagination({
            totalPages: totalPage,
            visiblePages: 5,
            onPageClick: (event, page) => {
                newsParams.page = page;
                setTimeout(callback, 100);
                window.scrollTo(0, 0);
            }
        });
    }
}

$(document).ready(function () {
    news.init();
});
