const newsParam = {}

const news = {
    init: function () {
        news.load();
    },
    load: function () {
        $.ajax({
            url: 'http://localhost:8000/api/v1/news',
            type: 'GET',
            success: res => {
                let html = '';
                $.each(res, (i, value) => {
                    let date = new Date(value.created_at);
                    let formatted_date = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear()
                    html +=
                        `
                            <div class="card mb-4">
                                <img class="card-img-top" src='${value.image}' alt="Card image cap">
                                <div class="card-body">
                                    <h2 class="card-title">${value.title}</h2>
                                    <div>${value.shortDescription}</div>
                                    <a href="{{route('home.news', ['id' => ${value.id}])}}" class="mt-2 btn btn-primary">Đọc thêm
                                        &rarr;</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Ngày đăng ${formatted_date}
                                </div>
                            </div>
                        `;
                });
                $('.root').html(html);
            }
        });
    }
}

$(document).ready(function () {
    news.init();
});
