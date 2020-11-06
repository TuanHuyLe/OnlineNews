class Base{
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

    //#region Event keyCode

    /**
     * Khởi tạo sự kiện tổ hợp phím
     * Author: LTQUAN (19/10/2020)
     * */
    initKeyCode() {
        let self = this;
        $(document).keyup(function (event) {
            if (event.keyCode == 27 || (event.key.toLowerCase() == 'q' && (event.ctrlKey || event.metaKey))) {
                event.preventDefault();
                if ($(".dialog-detail").is(":visible"))
                    self.onHideDialog();
            }
        });
        $(document).keydown(function (event) {
            if (event.ctrlKey && event.keyCode == 83 && event.shiftKey) {
                event.preventDefault();
                if ($(".dialog-detail").is(":visible"))
                    self.onSave(false);
            } else if (event.keyCode == 83 && (event.ctrlKey || event.metaKey)) {
                event.preventDefault();
                if ($(".dialog-detail").is(":visible"))
                    self.onSave(true);
            }
        });
    }

    /**
     * Hàm gán sự kiện form dialog
     * Author: LTQUAN (07/10/2020)
     * */
    initTabEvents() {
        $(".dialog-detail .btn-cancel").on('keydown', function (e) {
            var code = e.keyCode || e.which;
            if (event.shiftKey && code == 9) {
                $(this).focus();
            } else if (code === 9) {
                $(".left-paging ul li select").focus();
            }
        });
        $("#form-data input:first").on('keydown', function (e) {
            var code = e.keyCode || e.which;
            if (event.shiftKey && code == 9) {
                $('.dialog button:first').focus();
            }
        });
        $("#form-data input[type='checkbox']").keypress(function (e) {
            if ((e.keyCode ? e.keyCode : e.which) == 13) {
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
                if (e.keyCode == 13 && isNumberPage) {
                    self.onChangeInputCurrentPage(self, parseInt($(this).val()));
                }
                if ((e.keyCode >= 48 && e.keyCode <= 57) || e.keyCode == 13) {
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
                        notification.warning("Số trang không hợp lệ");
                    }
                }
                else
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

    /**
     * Hàm khởi tạo event cho các element
     * Author: LTQUAN (06/11/2020)
     */
    initEvents(){
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
    }

    //#region LOAD DATA

    /**
     * Hàm fetch data từ server
     * Author: LTQuan (26/09/2020)
     * */
    fetchData() {
        this.onShowLoading();
        this.method = 'GET';
        $.ajax({
            url: this.url,
            data: {
                page: this.page,
                pageSize: this.pageSize
            },
            method: this.method,
            dataType: 'json'
        }).done(res => {
            // Set lại thông số phân trang
            this.totalRecord = res.totalRecord;
            this.totalPage = Math.ceil(this.totalRecord / this.pageSize);
            $("#total-page").text(`trên ${this.totalPage}`);
            $(".right-paging span:first").text(`1 - ${this.pageSize > this.totalRecord ? this.totalRecord : this.pageSize}`);
            $(".right-paging span:last").text(this.totalRecord);
            $("#current-page").val(this.page);

            // Load data
            this.loadData(res.datas);
        }).fail(err => {
            console.log(err);
        }).always(() => {
            this.onHideLoading();
        });
    }

    /**
     * Hàm fetch data cho từng view từ server
     * Author: LTQuan (14/10/2020)
     * */
    fetchDataComponent() { }

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

    //#endregion LOAD DATA

    /**
     * Show loadding
     * Author: LTQUAN (06/11/2020)
     */
    onShowLoading(){
        $("#loading, #loading-display").show();
    }

    /**
     * Hide Loadding
     * Author: LTQUAN (06/11/2020)
     */
    onHideLoading(){
        $("#loading, #loading-display").hide();
    }
}
