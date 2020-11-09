//#region Constant
/**
 * Attributes for pageable
 * @type {{limit: number, page: number}}
 * Author: LHTUAN (05/11/2020)
 */
const pageableParams = {
    page: 1,
    limit: 2
}

/**
 * Parameter for call ajax in home page news
 * @type {{content_header: string, category: string}}
 * Author: LHTUAN (05/11/2020)
 */
const newsParams = {
    category: 'all',
    content_header: 'Trang chủ'
}

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
//#endregion Constant

//#region Handling
/**
 * Handling home page call API using ajax jquery
 * @type {{init: news.init, loadData: news.loadData, paging: news.paging, event: news.event}}
 * Author: LHTUAN (05/11/2020)
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
     * Author: LHTUAN (05/11/2020)
     */
    loadData: function () {
        // get category in pathName
        let pathname = location.pathname;
        newsParams.category = pathname.replace(pathname.split(/\w+-\w+/)[0], '');

        // let url = new URL(location.href);
        // if (url.searchParams.has('page')) {
        //     pageableParams.page = url.searchParams.get('page');
        // } else {
        //     pageableParams.page = 1;
        // }

        // call api get news
        $.ajax({
            url: 'http://localhost:8000/api/v1/news',
            data: {
                page: pageableParams.page,
                limit: pageableParams.limit,
                category: newsParams.category
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
                                    <a href="/tintuconline/home/news${value.id}" class="read-more mt-2 btn btn-primary">Đọc thêm
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
                    if (res.total / pageableParams.limit > 1) {
                        news.paging(res.total, () => {
                            news.loadData();
                        });
                    } else {
                        $('#pagination').remove('li');
                    }
                }
            }
        });
    },

    /**
     * pagination using twbsPagiantion
     * Author: LHTUAN (05/11/2020)
     * @param totalRow
     * @param callback
     */
    paging: function (totalRow, callback) {
        const totalPage = Math.ceil(totalRow / pageableParams.limit);
        $('#pagination').twbsPagination({
            totalPages: totalPage,
            visiblePages: 5,
            onPageClick: (event, page) => {
                pageableParams.page = page;
                sessionStorage.setItem('page', page);
                history.pushState(null, null, '?page=' + page);
                setTimeout(callback, 100);
                window.scrollTo(0, 0);
            }
        });
    },

    /**
     * init event
     * Author: LHTUAN (05/11/2020)
     */
    event: function () {
        /**
         * catch event click category
         * Author: LHTUAN (05/11/2020)
         */
        $('.category-news').off('click').on('click', function (e) {
            e.preventDefault();
            $('#pagination').twbsPagination('destroy');
            let code = $(this).data('code');
            if (checkBrowserSupportSessionStorage()) {
                sessionStorage.setItem('url', code);
            }
            history.pushState(null, null, sessionStorage.getItem('url'));
            newsParams.category = code;
            pageableParams.page = 1;
            news.loadData();
            window.scrollTo(0, 0);
        })
    }
}
//#endregion Handling

/**
 * init handling
 * Author: LHTUAN (05/11/2020)
 */
$(document).ready(function () {
    news.init();
});
