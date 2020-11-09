//#region Helper
const commonJS = {
    /**
     * Hàm format tiền tệ VND
     * @param {number} money
     * Author: LTQuan (30/09/2020)
     * */
    formatMoney: (money) => {
        return !money ? "" : (money + '').replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },

    /**
     * Hàm format tiền tệ VND
     * @param {number} money
     * Author: LTQUAN (20/10/2020)
     * */
    getMoneyInput: (money) => {
        return !money ? "" : parseFloat((money + '').replace(/\D/g, ""));
    },

    /**
     * Hàm format tiền tệ VND
     * @param {number} money
     * Author: LTQUAN (20/10/2020)
     * */
    formatCurrencySalary: (input) => {
        input.val(commonJS.formatMoney(input.val()));
    },

    /**
     * Hàm format Date
     * @param {string} date
     * Author: LTQuan
     */
    formatDate: (date) => {
        return !date ? "" : date.split("T")[0].split("-").reverse().join("/");
    },

    /**
     * Hàm format Address
     * @param {string} value
     * Author: LTQuan (30/09/2020)
     **/
    formatLimitString: (value, index = 20) => {
        return !value ? "" : value.length > index ? `${value.substr(0, index)}...` : value;
    },

    /**
     * Hàm format giá trị từ input
     * @param {any} value
     * @param {string} type
     * Author: LTQuan (30/09/2020)
     **/
    formatValue: (value, type) => {
        switch (type) {
            case formatField.NUMBER:
                value = !value ? null : parseFloat(value.replace(/\D/g, ""));
                break;
            case formatField.DATE:
                value = !value ? null : value;
                break;
            default:
                break;
        }
        return value;
    },

    /**
     * Hàm set giới tính
     * @param {number} gender
     * Author: LTQuan (01/10/2020)
     **/
    setGender: (gender) => {
        switch (gender) {
            case 1:
                gender = "Nữ";
                break;
            case 2:
                gender = "Nam";
                break;
            default:
                gender = "";
                break;
        }
        return gender;
    },

    /**
     * Hàm binding data từ đối tượng sang tr
     * @param {object} item
     * @returns {InnerHTML} trHtml
     * Author: LTQuan (27/09/2020)
     **/
    makeTrHtml: (item) => {
        // Lấy ra fieldName và format cho từng field
        let fields = $("#table-data thead tr:first th").toArray().map(item => {
            return {
                fieldName: $(item).attr('fieldname'),
                format: $(item).attr('format') || 'string'
            }
        });
        // Lấy keyId để gán cho thẻ tr
        let keyId = commonJS.getKeyId();
        let trHtml = $(`<tr></tr>`);
        // Gán keyId cho tr
        trHtml.data(keyId, item[keyId]);
        // Duyệt fields để binding value từ item
        fields.forEach(field => {
            let fieldName = field.fieldName;
            switch (field.format) {
                case formatField.STRING:
                    trHtml.append(`<td title='${item[fieldName] || ""}'>${item[fieldName] || ""}</td>`);
                    break;
                case formatField.NUMBER:
                    trHtml.append(`<td style='text-align: end' title='${commonJS.formatMoney(item[fieldName])}'>${commonJS.formatMoney(item[fieldName])}</td>`);
                    break;
                case formatField.DATE:
                    trHtml.append(`<td style='text-align: center' title='${commonJS.formatDate(item[fieldName])}'>${commonJS.formatDate(item[fieldName])}</td>`);
                    break;
                case formatField.LIMIT_STRING:
                    trHtml.append(`<td title='${item[fieldName]}'>${commonJS.formatLimitString(item[fieldName])}</td>`);
                    break;
                default:
                    break;
            }
        });
        return trHtml;
    },

    /**
     * Hàm lấy keyId cho Obj
     * Author: LTQUAN (03/10/2020)
     * @returns {string} keyId
     * */
    getKeyId: () => {
        return $(".grid table thead tr:first").attr('keyId');
    },

    /**
     * Hàm lấy id của obj
     * @returns {string} id
     * Author: LTQuan (25/09/2020)
     * */
    getId: () => {
        return $("table#table-data .row-selected").data(commonJS.getKeyId());
    },

    /**
     * Lấy danh sách id đucợ chọn từ các bản ghi
     * @returns {Array} ids
     * Author: LTQUAN (19/10/2020)
     * */
    getMultiId: () => {
        return $(".grid table#table-data .row-selected").toArray().map(row => $(row).data(commonJS.getKeyId()));
    },

    /**
     * Hàm load data lên thẻ select
     * @param {Object} selects
     * Author: LTQUAN (12/10/2020)
     * */
    makeSelectOptions: (selects) => {
        $.each(selects, (i, select) => {

            // Clear option và cập nhật lại
            let optionFirst = $(select).find("option:first");
            $(select).empty().append(optionFirst);

            // Lấy ra ID và Name phù hợp với tùy chọn select
            let id = $(select).data("id");
            let name = $(select).data("name");
            let url = api[$(select).data("url")];
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json'
            }).done(res => {
                res.Datas.forEach(item => {
                    $(select).append(`<option value="${item[id]}">${item[name]}</option>`);
                });
            });
        });
    },

    /**
     * Tạo chuỗi thông báo từ mảng messages
     * @param {Array} messages
     * @param {string} messageValid
     * @returns {string}
     * Author: LTQUAN (20/10/2020)
     * */
    makeMessage: (messages, messageValid) => {
        let messageError = '';
        if (messages.length > 1) {
            messages.forEach((message, i) => {
                // Nếu i là mesage cuối cùng trong mảng
                if (i == messages.length - 1) {
                    messageError = messageError.replace(/(, )$/, ' ');
                    messageError += `và ${message}`;
                }
                else
                    messageError += `${message}, `;
            });
        } else
            messageError = messages[0];
        return messageError + messageValid;
    }
}

//#endregion Helper

//#region CONST

/**
 * Constaint url
 * Author: LTQuan (26/09/2020)
 * */
const api = {
    CATEGORY_API: '/api/v1/categories'
}

/**
 * Constaint format field
 * Author: LTQuan (27/09/2020)
 * */
const formatField = {
    NUMBER: 'number',
    DATE: 'date',
    STRING: 'string',
    LIMIT_STRING: 'limit_string',
    GENDER: 'gender',
}

/**
 * Constant type input
 * Author: LTQuan (28/09/2020)
 * */
const typeInput = {
    SELECT: 'select',
    RADIO: 'radio',
    CHECKBOX: 'checkbox',
    DATE: 'date',
    TEXT: 'text',
    EMAIL: 'email',
    NUMBER: 'number'
}

/**
 * Constant message
 * Author: LTQuan (28/09/2020)
 * */
const message = {
    customer: {
        ADD_SUCCESS: 'Thêm mới khách hàng thành công!',
        EDIT_NONE: 'Vui lòng chọn một khách hàng bất kì để sửa!',
        EDIT_SUCCESS: 'Cập nhật thành công!',
        COMFIRM_DELETE: 'Bạn có chắc chắn xóa khách hàng này?',
        DELETE_NONE: 'Vui lòng chọn khách hàng để thực hiện xóa!',
        DELETE_SUCCESS: 'Xóa thành công!',
        NOT_EXISTS: 'Khách hàng này không còn tồn tại trong hệ thống!'
    },
    employee: {
        ADD_SUCCESS: 'Thêm mới nhân viên thành công!',
        EDIT_NONE: 'Vui lòng chọn một nhân viên bất kì để sửa!',
        EDIT_SUCCESS: 'Cập nhật thành công!',
        COMFIRM_DELETE: 'Bạn có chắc chắn xóa nhân viên này không?',
        COMFIRM_MULTI_DELETE: 'Bạn có chắc chắn xóa những nhân viên đã chọn không?',
        DELETE_NONE: 'Vui lòng chọn nhân viên bất kì để thực hiện xóa!',
        DELETE_SUCCESS: 'Xóa thành công!',
        NOT_EXISTS: 'Nhân viên này không còn tồn tại trong hệ thống!'
    },
    ERROR: 'Có lỗi xảy ra, vui lòng kiểm tra lại!',
    pagable: {
        LAST_PAGE: 'bạn đang ở trang cuối cùng',
        FIRST_PAGE: 'bạn đang ở trang đầu tiên'
    },
    INVALID: ' không hợp lệ',
    BLANK: ' không được bỏ trống'
}

/**
 * Constant icon type
 * Author: LTQuan (02/10/2020)
 * */
const iconType = {
    ICON_ANSWER: 'icon-answer',
    ICON_INFOR: 'icon-infor',
    ICON_WARNING: 'icon-warning'
}

//#endregion CONST
