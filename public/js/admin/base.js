class Base {
    constructor(url, message) {
        this.url = url;
        this.message = message;
        this.page = 1;
        this.pageSize = 10;
        this.initEvents();
        this.fetchData();
        this.initKeyCode();
        this.paging();
    }

    /**
     * Hàm khởi tạo event cho các element
     * Author: LTQUAN (06/11/2020)
     */
    initEvents() {
        $('#btn-add').click(() => this.onAdd());
        $('#btn-edit').click(() => this.onLoadObject('PUT'));
        $('#btn-delete').click(() => this.onCheckSelectedRow());
        $('#btn-save').click(this.onSave.bind(this));
        $('#btn-seach').click((e) => {
            e.preventDefault();
            this.onFilter();
        });
        $('#btn-refresh').click((e) => {
            e.preventDefault();
            this.onRefresh();
        });
        this.onShowLoading();
        toastr.options = {
            "debug": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "fadeIn": 300,
            "fadeOut": 1000,
            "timeOut": 5000,
            "extendedTimeOut": 1000
        }
        this.initValidate();
        $("#tbl-data").on('click', 'tr', (e) => {
            this.onChangeTrSelected(e);
            this.onDisabledBtn();
        });
        this.fetchDataComponent();
    }

    //#region Event keyCode

    /**
     * Khởi tạo sự kiện tổ hợp phím
     * Author: LTQUAN (19/10/2020)
     * */
    initKeyCode() {
        let self = this;
        $(document).keyup(function (event) {
            if (event.keyCode === 27 || (event.key.toLowerCase() === 'q' && (event.ctrlKey || event.metaKey))) {
                event.preventDefault();
                if ($(".modal").is(":visible"))
                    self.onHideDialog();
            }
        });
        $(document).keydown(function (event) {
            if (event.keyCode === 83 && (event.ctrlKey || event.metaKey)) {
                event.preventDefault();
                if ($(".modal").is(":visible"))
                    self.onSave();
            }
        });
    }

    /**
     * Hàm gán sự kiện form dialog
     * Author: LTQUAN (07/10/2020)
     * */
    initTabEvents() {
        $(".dialog-detail .btn-cancel").on('keydown', function (e) {
            let code = e.keyCode || e.which;
            if (event.shiftKey && code === 9) {
                $(this).focus();
            } else if (code === 9) {
                $(".left-paging ul li select").focus();
            }
        });
        $("#form-data input:first").on('keydown', function (e) {
            let code = e.keyCode || e.which;
            if (e.shiftKey && code === 9) {
                $('.dialog button:first').focus();
            }
        });
        $("#form-data input[type='checkbox']").keypress(function (e) {
            if ((e.keyCode ? e.keyCode : e.which) === 13) {
                $(this).trigger('click');
            }
        });
    }

    //#endregion

    //#region Paging

    /**
     * Xử lý phân trang
     * Author: LTQUAN (13/10/2020)
     * */
    paging() {
        let self = this;
        $("#btn-page-last").click(() => this.setPagable());
        $("#btn-page-first").click(() => this.setPagable(null, false));
        $("#btn-page-prev").click(() => this.setPagable(-1, false));
        $("#btn-page-next").click(() => this.setPagable(1));
        $("#btn-page-refresh, #btn-reload").click(function () {
            self.page = 1;
            self.setCurrentResult();
            self.fetchData();
        });
        $("#page-size").change(() => this.onChangedLimitRecord());
        let isNumberPage = true;

        // Event nhập số trang
        $("#current-page").on({
            focusout: function () {
                let page = parseInt($(this).val());
                // Check nếu chưa enter trong thẻ input thì focusout sẽ load trang đã nhập trong input
                if (page !== self.page && page <= self.totalPage) {
                    self.onChangeInputCurrentPage(self, page);
                }
            },
            keypress: function (e) {
                // Chỉ cho phép nhập các kí tự số
                if (e.keyCode === 13 && isNumberPage) {
                    self.onChangeInputCurrentPage(self, parseInt($(this).val()));
                }
                if ((e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode === 13) {
                    isNumberPage = true;
                } else {
                    isNumberPage = false;
                    e.preventDefault();
                }
            },
            keyup: function (e) {
                if (isNumberPage) {
                    let page = parseInt($(this).val());
                    // Kiểm tra nếu page nhập trong input khác không và lớn hơn tổng page hiện tại không
                    if (!page || page > self.totalPage) {
                        isNumberPage = false;
                        toastr.info("Số trang không hợp lệ");
                    }
                } else
                    e.preventDefault();
            }
        });
    }

    /**
     * Set lại page và load data mới khi thay đổi số trang ô input
     * @param {Object} self
     * @param {number} page
     * Author: LTQUAN (21/10/2020)
     */
    onChangeInputCurrentPage(self, page) {
        self.page = page;
        self.setCurrentResult();
        self.fetchData();
        $("#btn-replication, #btn-edit, #btn-delete").attr("disabled", true);
    }

    /**
     * Thay đổi số lượng bản ghi trên 1 trang
     * Author: LTQUAN (14/10/2020)
     * */
    onChangedLimitRecord() {
        this.pageSize = parseInt($("#page-size").val());
        this.page = 1;
        this.setCurrentResult();
        this.fetchData();
    }

    /**
     * Kiểm tra giới hạn total page/Gán giá trị page hiện tại
     * @param {number} sub: giá trị current page
     * @param {boolean} isNext: trường hợp tăng/giảm: true/false
     * Author: LTQUAN (13/10/2020)
     */
    setPagable(sub, isNext = true) {
        // Nếu đang tăng page
        if (isNext) {
            if (this.page === this.totalPage) {
                toastr.info(message.pagable.LAST_PAGE);
            } else {
                this.setCurrentPage(sub);
            }
        } else {
            if (this.page === 1) {
                toastr.info(message.pagable.FIRST_PAGE);
            } else {
                this.setCurrentPage(sub, false);
            }
        }
    }

    /**
     * Xét vị trí các bản ghi
     * Author: LTQUAN (21/10/2020)
     * */
    setCurrentResult() {
        let offset = (this.page - 1) * this.pageSize;
        let limit = offset + this.pageSize;
        if (offset === 0) {
            offset = 1;
        }
        if (limit > this.totalRecord) {
            limit = this.totalRecord;
        }
        $(".right-paging span:first").text(`${offset} - ${limit}`);
    }

    /**
     * Tăng giảm current page và fetch data theo current page
     * @param {number} sub
     * @param {boolean} isNext
     * Author: LTQUAN (13/10/2020)
     */
    setCurrentPage(sub, isNext = true) {
        if (sub) {
            this.page += sub;
        } else {
            // Set trang cuối cùng/trang đầu tiên
            this.page = isNext ? this.totalPage : 1;
        }
        this.setCurrentResult();
        $("#current-page").val(this.page);
        this.fetchData();
    }

    //#endregion

    //#region Save obj

    /**
     * Mở form thêm mới
     * Author: LTQUAN (07/11/2020)
     */
    onAdd() {
        this.method = 'POST';
        this.refreshFormDialog();
        this.onShowDialog();
    }

    /**
     * Hàm fetch obj từ server theo row-selected
     * Author: LTQuan (28/09/2020)
     * */
    onLoadObject(method) {
        this.method = 'GET';
        let rowsCounts = $('.row-selected').length
        if (!rowsCounts || rowsCounts !== 1) {
            toastr.info(this.message.EDIT_NONE);
        } else {
            this.onShowLoading();
            $.ajax({
                url: `${this.url}/${commonJS.getId()}`,
                method: this.method,
                dataType: 'json',
            }).done(res => {
                this.setInputDialog(res);
                this.onShowDialog();
                this.method = method;
            }).fail(err => {
                console.log(err);
            }).always(() => {
                this.onHideLoading();
            });
        }
    }

    /**
     * Hàm set input dialog với obj được fetch về từ server
     * @param {object} obj
     * @returns {void}
     * Author: LTQuan (28/09/2020)
     * */
    setInputDialog(obj) {
        this.refreshFormDialog();
        // Lấy ra name của các thẻ input trong form
        // Name của các thẻ input phải giống với các field của object
        let x = $("#form-data").serializeArray();
        x.forEach(item => {
            // Lấy ra thẻ input với name tương ứng
            let element = $('#form-data').find(`input[name='${item.name}'], textarea[name='${item.name}'], select[name='${item.name}']`);

            // Kiểm tra kiểu của các thẻ input để binding dữ liệu phù hợp
            let type = element.attr('typeInput');
            try {
                // Gán giá trị cho thẻ input
                switch (type) {
                    case typeInput.DATE:
                        element.val(obj[item.name].split('T')[0]);
                        break;
                    case typeInput.NUMBER:
                        element.val(commonJS.formatMoney(obj[item.name]));
                        break;
                    case typeInput.SELECT:
                        element.val(obj[item.name]);
                        break;
                    default:
                        element.val(obj[item.name]);
                        break;
                }
            }
            catch (e) { }
        });
    }

    /**
     * Hàm thực hiện thêm/sửa obj
     * Author: LTQuan (27/09/2020)
     * EditBy: LTQUAN (21/10/2020)
     * Description: Sửa lại để check thêm hoặc thêm & sửa
     * */
    onSave() {
        if ($('#form-data').valid()) {
            this.onShowLoading();
            let self = this;
            // Sử dụng reduce để chuyển đổi array sinh ra từ hàm serializeArray thành object
            let obj = $("#form-data").serializeArray().reduce((result, item) => {
                let format = $(`#form-data input[name='${item.name}']`).attr("format");
                if (!format) {
                    format = $(`#form-data select[name='${item.name}']`).attr("format");
                }
                // Return các properties trong current value và nạp thuộc tính mới
                return {...result, [item.name]: commonJS.formatValue(item.value, format)};
            }, {[commonJS.getKeyId()]: self.method === 'PUT' ? commonJS.getId() : undefined}); // Giá trị khởi tạo của result
            // Thực hiện gọi req đến server
            $.ajax({
                url: this.url,
                method: this.method,
                data: JSON.stringify(obj),
                contentType: 'application/json',
                dataType: 'json',
            }).done((res) => {
                let messageStatus;
                if (res.errorCode === 201) {
                    messageStatus = this.message.ADD_SUCCESS;
                } else if (res.errorCode === 200) {
                    messageStatus = this.message.EDIT_SUCCESS;
                } else {
                    messageStatus = this.message.NOT_EXISTS;
                }

                this.fetchData();

                this.onHideDialog();

                toastr.success(messageStatus);

                // Disabled btn-replication
                // $("#btn-replication, #btn-edit").attr("disabled", true);
            }).fail(err => {
                const messageError = err.responseJSON.message;
                toastr.warning(messageError);
            }).always(() => {
                this.onHideLoading();
            });
        }
    }

    //#endregion

    //#region LOAD DATA

    /**
     * Hàm fetch data từ server
     * Author: LTQuan (26/09/2020)
     * */
    fetchData() {
        this.onShowLoading();
        this.method = 'GET';
        let request = {
            page: this.page,
            pageSize: this.pageSize,
            filter: $('#seach').val()
        }
        $.ajax({
            url: this.url,
            data: request,
            method: this.method,
            dataType: 'json'
        }).done(res => {
            // Set lại thông số phân trang
            this.totalRecord = res.totalRecord;
            this.totalPage = Math.ceil(this.totalRecord / this.pageSize);
            $("#total-page").text(`trên ${this.totalPage}`);
            if (this.page === 1)
                $(".right-paging span:first").text(`1 - ${this.pageSize > this.totalRecord ? this.totalRecord : this.pageSize}`);
            $(".right-paging span:last").text(this.totalRecord);
            $("#current-page").val(this.page);

            // Load data
            this.loadData(res.data);
        }).fail(err => {
            console.log(err);
        }).always(() => {
            this.onHideLoading();

            // Disabled btn toolbar
            this.onDisabledBtn();
        });
    }

    /**
     * Hàm fetch data cho từng view từ server
     * Author: LTQuan (14/10/2020)
     * */
    fetchDataComponent() {
    }

    /**
     * Hàm nạp data khi fetch về từ server
     * Author: LTQuan (26/09/2020)
     * @param {Array} data
     */
    loadData(data) {
        $("#tbl-data").empty();
        data.forEach((item) => {
            try {
                $('#tbl-data').append(commonJS.makeTrHtml(item));
            } catch (e) {
                console.log(e);
            }
        });
        // Disabled các btn nhân bản, sửa xóa khi load mới các bản ghi
        // $("#btn-replication, #btn-edit, #btn-delete").attr("disabled", true);
    }

    /**
     * Filter
     * Author: LTQUAN (09/11/2020)
     */
    onFilter(){
        this.page = 1;
        this.fetchData();
    }

    /**
     * Refresh
     * Author: LTQUAN (09/11/2020)
     */
    onRefresh(){
        this.page = 1;
        this.pageSize = 10;
        $('#seach').val('');
        $('#page-size').val('10');
        this.fetchData();
    }

    //#endregion LOAD DATA

    //#region Show/hide

    /**
     * Show loadding
     * Author: LTQUAN (06/11/2020)
     */
    onShowLoading() {
        $("#loading, .loading-display").show();
    }

    /**
     * Hide Loadding
     * Author: LTQUAN (06/11/2020)
     */
    onHideLoading() {
        $("#loading, .loading-display").hide();
    }

    /**
     * Show dialog
     * Author: LTQUAN (07/11/2020)
     */
    onShowDialog() {
        $('#form-data input:first').focus();
        $('#display-dialog').trigger('click');
    }

    /**
     * Hide dialog
     * Author: LTQUAN (07/11/2020)
     */
    onHideDialog() {
        $('#btn-cancel').trigger('click');
        this.refreshFormDialog();
    }

    //#endregion

    /**
     * Refresh form
     * Author: LTQUAN (08/11/2020)
     */
    refreshFormDialog() {
        $('#form-data').find('select, input, textarea').val('').removeClass('error').removeAttr('title');
    }

    /**
     * Validate
     * Author: LTQUAN (07/11/2020)
     */
    initValidate() {
    }

    //#region On change control

    /**
     * Hàm chọn row khi click
     * Author: LTQuan (26/09/2020)
     */
    onChangeTrSelected(e) {
        let target = e.currentTarget;
        if (e.ctrlKey) {
            if ($(target).hasClass('row-selected')) {
                $(target).removeClass('row-selected');
            } else {
                $(target).addClass('row-selected');
            }
        } else {
            if ($(target).hasClass('row-selected')) {
                $(target).removeClass('row-selected');
            } else {
                $(target).siblings().removeClass('row-selected');
                $(target).addClass('row-selected');
            }
        }
    }

    /**
     * Ẩn hiện btn
     * Author: LTQUAN (21/10/2020)
     * */
    onDisabledBtn() {
        let ids = commonJS.getMultiId();
        // Check btn delete
        if (ids && ids.length > 0)
            $("#btn-delete").attr("disabled", false);
        else
            $("#btn-delete").attr("disabled", true);

        // Check btn edit
        if (ids && ids.length === 1)
            $("#btn-replication, #btn-edit").attr("disabled", false);
        else
            $("#btn-replication, #btn-edit").attr("disabled", true);
    }

    //#endregion

    //#region Delete

    /**
     * Hàm check row selected
     * Author: LTQuan (27/09/2020)
     * */
    onCheckSelectedRow() {
        if (!$('.row-selected').length) {
            toastr.info(this.message.DELETE_NONE);
        } else {
            this.method = 'DELETE';
            this.ids = commonJS.getMultiId();
            let message;
            if (this.ids.length === 1)
                message = this.message.COMFIRM_DELETE;
            else
                message = this.message.COMFIRM_MULTI_DELETE;
            this.onDeleteRow(message);
        }
    }

    /**
     * Hàm xóa row selected
     * @param {string} content
     * Author: LTQuan (02/10/2020)
     * */
    onDeleteRow(content) {
        let self = this;
        Swal.fire({
            title: content,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.value) {
                self.onShowLoading();
                $.ajax({
                    url: self.url,
                    method: self.method,
                    data: JSON.stringify(self.ids),
                    contentType: 'application/json',
                    dataType: 'json'
                }).done((res) => {
                    this.fetchData();
                    if (res.errorCode === 200) {
                        toastr.success(this.message.DELETE_SUCCESS);
                    }
                }).fail((err) => {
                    if(err.status === 403){
                        toastr.warning('Bạn không có quyền xóa bản ghi này!');
                    }else {
                        toastr.error(message.ERROR);
                    }
                }).always(() => {
                    self.onHideLoading();
                });
            }
        });
    }

    //#endregion

}
