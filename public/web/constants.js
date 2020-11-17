//#region Constant
/**
 * Attributes for pageable
 * @type {{limit: number, page: number}}
 * @Author LHTUAN (05/11/2020)
 */
const pageableParams = {
    page: 1,
    limit: 2
}

/**
 * Parameter for call ajax in home page news
 * @type {{content_header: string, code: string}}
 * @Author LHTUAN (05/11/2020)
 */
const newsParams = {
    code: this.home,
    content_header: 'Trang chủ'
}

/**
 * Scroll position
 * @type {{X: number, Y: number}}
 * @Author LHTUAN (10/11/2020)
 */
const scrollPos = {
    X: 0,
    Y: 0
}

const constants = {
    home: 'home',
    position: 'position',
    readMore: 'readMore',
    code: 'code',
    page: 'page',
    url: 'http://localhost:8000/api/web/v1',
    getAll: '/news',
    getOne: id => `/news/id/${id}`,
    search: title => `/news/search?title=${title}`
}

//#endregion Constant
