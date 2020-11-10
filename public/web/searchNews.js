//#region search
const search = {
    init: () => {
        search.event();
    },
    loadData: (title) => {
        loadingAnimation.onShowLoading();
        $.ajax({
            url: constants.url + constants.search(title),
            type: 'GET',
            success: res => {
                if (res.status === 200) {
                    const data = res.data;
                    let html = '';
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
                                    <a href="news?id=${value.id}" class="mt-2 btn btn-primary btn-read-more"
                                    id="news-${value.id}" data-id="${value.id}">Đọc thêm
                                        &rarr;</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Ngày đăng ${formatted_date}
                                </div>
                            </div>
                        `;
                        $('.root').html(html);
                        // set content header
                        newsParams.content_header = `<h2>Kết quả tìm kiếm cho: ${res.other}<br></h2>
                                                     <h4>(${res.data.length} bài viết)</h4>`;
                        $('#content-header').html(newsParams.content_header);
                        history.pushState(null, null, 'search?title=' + res.other);
                    });
                } else {
                    newsParams.content_header = '';
                    $('#content-header').html(newsParams.content_header);
                    $('.root').html(`<h2>${res.message}</h2>`);
                    history.pushState(null, null, `search?title=${res.other}&message=${res.message}`);
                }
                $('#pagination').empty();
            },
            error: e => console.log(e)
        }).always(() => loadingAnimation.onHideLoading());
    },
    event: () => {
        $('#search_form').on('submit', function (e) {
            e.preventDefault();
            let data = $("#search_form :input").serializeArray();
            search.loadData(data[0].value);
        });
    }
}
//#endregion search

/**
 * init handling search
 * @Author LHTUAN (10/11/2020)
 */
$(document).ready(() => {
    search.init();
});
