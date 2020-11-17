$(document).ready(function () {
    const user = new User();
});

class User extends Base {
    constructor() {
        super(api.USER_API, message.categories);
    }

    initEvents() {
        super.initEvents();
        $('#form-data select#role-id').select2({
            placeholder: 'Chọn vai trò',
            // allowClear: true
        });

    }

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

            obj['role_ids'] = $('#role-id').val();

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

    setInputDialog(obj) {
        super.setInputDialog(obj);
        $('#role-id').val(obj['role_ids']).change();
    }

    //#region Validate sử dụng lib validation

    /**
     * Khởi tạo validate employee
     * Author: LTQuan (01/10/2020)
     * */
    initValidate() {
        // Custom rule email valid
        $.validator.addMethod("validEmail", function (value, element) {
            return this.optional(element) || /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
        }, 'Please enter a valid email address.');
        $("#form-data").validate({
            onfocusout: function (element) {
                if (element.tagName === "TEXTAREA" || (element.tagName === "INPUT" && element.type !== "password")) {
                    element.value = $.trim(element.value);
                }
                return $(element).valid();
            },
            rules: {
                name: 'required',
                email: 'required'
            },
            messages: {
                name: 'tên thể loại không được để trống',
                email: 'tên thể loại không được để trống'
            },
            errorPlacement: function (error, element) {
                element.attr('title', error.text());
            },
            success: function (element) {
                $(element).removeAttr('title');
            }
        });
    }

    //#endregion

    /**
     * -----------------------------------------------
     * Gọi api lấy danh sách departments và possitions
     * Author: LTQUAN (20/10/2020)
     * -----------------------------------------------
     * */
    fetchDataComponent() {
        commonJS.makeSelectOptions($("#form-data select[fetch]"));
    }

    refreshFormDialog() {
        super.refreshFormDialog();
        $('#role-id').val([]).change();
    }
}
