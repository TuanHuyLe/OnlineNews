$(document).ready(function () {
    const role = new Role();
});

class Role extends Base {
    constructor() {
        super(api.ROLE_API, message.categories);
    }

    initEvents() {
        super.initEvents();
        $('.cb-all').on('change', function (){
            if ($(this).prop('checked'))
                $('.permission_ids > option').prop('selected', true).change();
            else
                $('.permission_ids > option').prop('selected', false).change();
        });
    }

    /**
     * Khởi tạo gán event select2 cho select
     * Author: LTQUAN (15/11/2020)
     */
    setSelect2(){
        $('#form-data .permission_ids').select2({
            placeholder: 'Chọn chức năng',
            // allowClear: true
        });
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
                code: 'required'
            },
            messages: {
                name: 'tên quyền hạn không được để trống',
                code: 'mã quyền hạn không được để trống'
            },
            errorPlacement: function (error, element) {
                element.attr('title', error.text());
            },
            success: function (element) {
                $(element).removeAttr('title');
            }
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

            obj['permission_ids'] = $('.permission_ids').toArray().reduce((result, item) => {
                return result.concat($(item).val());
            }, []);

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

    /**
     * -----------------------------------------------
     * Gọi api lấy danh sách permissions
     * Author: LTQUAN (20/10/2020)
     * -----------------------------------------------
     * */
    fetchDataComponent() {
        $.ajax({
            url: `${api.PERMISSION_API}/list`,
            dataType: 'json'
        }).done(res => {
            for (let i = 0; i < res.length; i++) {
                if (res[i]['parent_id'] === 0) {
                    let htmlChildren = $(`
                        <div class="form-group">
                            <label>${res[i].name}</label>
                            <select id="permission-${res[i]['id']}" multiple class="form-control permission_ids"></select>
                        </div>
                    `);
                    let htmlOptions = '';
                    for (let j=0; j<res.length; j++){
                        if (res[j]['parent_id'] === res[i]['id']){
                            htmlOptions += `
                                <option value="${res[j]['id']}">${res[j]['name']}</option>
                            `
                        }
                    }
                    htmlChildren.find('select').append(htmlOptions);
                    $('#form-data .card-body .row .col-md-12').append(htmlChildren);
                }
            }
            this.setSelect2();
        }).fail(err => console.log(err));
    }

    setInputDialog(obj) {
        super.setInputDialog(obj);
        $('.permission_ids').val(obj['permission_ids']).change()
    }

    refreshFormDialog() {
        super.refreshFormDialog();
        $('.permission_ids').val([]).change();
        $('.cb-all').prop('checked', false);
    }
}
