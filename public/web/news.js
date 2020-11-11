//#region Handling
/**
 * Check browser support session storage
 * Author: LHTUAN (05/11/2020)
 * @returns {boolean}
 */
const checkBrowserSupportSessionStorage = () => {
    if (typeof (Storage) !== 'undefined') {
        return true;
    }
    console.log('Sorry, your browser does not support Web Storage...');
    return false;
}

/**
 * #Handling home page call API using ajax jquery
 * @type {{init: news.init, loadData: news.loadData, paging: news.paging, event: news.event}}
 * @Author LHTUAN (05/11/2020)
 */
const news = {
    /**
     * load data and init event
     */
    init: function () {
        news.loadData();
        news.event();
    },

    /**
     * load data show on home page
     * @Author LHTUAN (05/11/2020)
     */
    loadData: function () {
        // get code in pathName
        let pathname = location.pathname;
        newsParams.code = pathname.replace(pathname.split(/\w+-\w+/)[0], '');

        // call api get news
        loadingAnimation.onShowLoading();
        $.ajax({
            url: constants.url + constants.getAll,
            data: {
                page: pageableParams.page,
                limit: pageableParams.limit,
                category: newsParams.code
            },
            type: 'GET',
            success: res => {
                if (res.status === 200) {
                    let html = '';
                    const data = res.data;

                    // browse data news and display on home page
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
                    });
                    $('.root').html(html);

                    // set content header
                    if (res.other != null) {
                        newsParams.content_header = 'Thể loại: ' + res.other.name;
                    } else {
                        newsParams.content_header = 'Trang chủ';
                    }
                    $('#content-header').html(newsParams.content_header);
                    // set paginate
                    news.paging(res.total, () => news.loadData());
                } else {
                    $('.root').html('<h2>Dữ liệu trống</h2><br><p>' + res.message + '</p>');
                }
            }
        }).always(() => loadingAnimation.onHideLoading());
    },

    /**
     * pagination using twbsPagiantion
     * @Author LHTUAN (05/11/2020)
     * @param totalRow
     * @param callback
     */
    paging: (totalRow, callback) => {
        const totalPage = Math.ceil(totalRow / pageableParams.limit);
        $('#pagination').twbsPagination({
            totalPages: totalPage,
            visiblePages: 5,
            hideOnlyOnePage: true,
            onPageClick: (event, page) => {
                pageableParams.page = page;
                sessionStorage.setItem(constants.page, page);
                history.pushState(null, null, '?page=' + page);
                setTimeout(callback, 100);
                window.scrollTo(0, 0);
            }
        });
    },

    /**
     * init event
     * @Author LHTUAN (05/11/2020)
     */
    event: function () {
        /**
         * catch event click category
         * @Author LHTUAN (05/11/2020)
         */
        $('.category-news').off('click').on('click', function (e) {
            e.preventDefault();
            $('#pagination').twbsPagination('destroy');
            let code = $(this).data(constants.code);
            if (checkBrowserSupportSessionStorage()) {
                sessionStorage.setItem(constants.code, code);
                sessionStorage.setItem(constants.readMore, 'false');
            }
            history.pushState(null, null, sessionStorage.getItem(constants.code));
            newsParams.code = code;
            pageableParams.page = 1;
            news.loadData();
            window.scrollTo(0, 0);
        });

        /**
         * catch event click link home
         * @Author LHTUAN (09/11/2020)
         */
        $('.home').off('click').on('click', function (e) {
            e.preventDefault();
            newsParams.code = constants.home;
            newsParams.content_header = 'Trang chủ';
            sessionStorage.setItem(constants.code, constants.home);
            sessionStorage.setItem(constants.readMore, 'false');
            sessionStorage.setItem(constants.position, '0');
            history.pushState(null, null, constants.home);
            $('#pagination').twbsPagination('destroy');
            news.loadData();
            window.scrollTo(0, 0);
        });
    }
}
//#endregion Handling

/**
 * init handling
 * @Author LHTUAN (05/11/2020)
 */
$(document).ready(() => {
    news.init();
});
