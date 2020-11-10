//#region Handling
/**
 * #Handling read more info single news
 * @type {{init: readMore.init, loadData: readMore.loadData, event: readMore.event}}
 * @Author LHTUAN (08/11/2020)
 */
const readMore = {
    /**
     * call event read more
     */
    init: function () {
        readMore.event();
    },

    /**
     * load data info single news
     * @param id
     * @Author: LHTUAN (08/11/2020)
     */
    loadData: function (id) {
        loadingAnimation.onShowLoading();
        $.ajax({
            url: `http://localhost:8000/api/v1/news/${id}`,
            type: 'GET',
            success: res => {
                if (res.status === 200) {
                    let html = '';
                    const data = res.data;

                    let code = sessionStorage.getItem(constants.code);
                    let page = sessionStorage.getItem(constants.page);

                    // display info single news
                    let date = new Date(data.created_at);
                    let formatted_date = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear()
                    html +=
                        `
                            <h1 class="mt-4">${data.title}</h1>
                            <hr>
                            <p>Ngày đăng ${formatted_date}</p>
                            <hr>
                            <img class="img-fluid rounded" src="${data.image}" alt="">
                            <hr>
                            <p>${data.content}</p>
                            <a class="btn btn-primary mb-5" id="btn-back" href="/tintuconline/${code}?page=${page}">Quay lại</a>
                        `;
                    $('.root').html(html);
                }
            },
            error: e => {
                console.log(e);
            }
        }).always(() => loadingAnimation.onHideLoading());
    },

    /**
     * init event
     * @Author LHTUAN (08/11/2020)
     */
    event: function () {
        /**
         * catch event click button 'Đọc thêm'
         * @Author LHTUAN (08/11/2020)
         */
        $(document).on('click', '.btn-read-more', function (e) {
            e.preventDefault();
            history.pushState(null, null, 'news?id=' + $(this).data('id'));
            sessionStorage.setItem(constants.readMore, 'true');
            $('#content-header').html('');
            $('#pagination').empty();
            readMore.loadData($(this).data('id'));

            let posY = $(document).scrollTop();
            sessionStorage.setItem(constants.position, posY);
            window.scrollTo(0, 0);
        });

        /**
         * catch event click button 'Quay lại'
         * @Author LHTUAN (10/11/2020)
         */
        $(document).on('click', '#btn-back', function (e) {
            e.preventDefault();
            let code = sessionStorage.getItem(constants.code);
            let page = sessionStorage.getItem(constants.page);
            newsParams.code = code;
            pageableParams.page = parseInt(page);
            sessionStorage.setItem(constants.readMore, 'false');
            history.pushState(null, null, `${code}?page=${page}`);
            $('#pagination').twbsPagination('destroy');
            news.loadData();
            window.scrollTo(0, parseInt(sessionStorage.getItem(constants.position)));
        });
    },

}
//#endregion Handling

/**
 * init handling
 * @Author LHTUAN (08/11/2020)
 */
$(document).ready(function () {
    readMore.init();
});
