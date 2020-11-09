$(document).ready(function () {
    const employee = new Category();
});

class Category extends Base {
    constructor() {
        super(api.CATEGORY_API, message.employee);
    }

    //#region Validate sử dụng lib validation

    /**
     * Khởi tạo validate employee
     * Author: LTQuan (01/10/2020)
     * */
    //initValidate() {
    //    // Custom rule email valid
    //    $.validator.addMethod("validEmail", function (value, element) {
    //        return this.optional(element) || /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
    //    }, 'Please enter a valid email address.');
    //    $("#form-data").validate({
    //        onfocusout: function (element) {
    //            if (element.tagName === "TEXTAREA" || (element.tagName === "INPUT" && element.type !== "password")) {
    //                element.value = $.trim(element.value);
    //            }
    //            return $(element).valid();
    //        },
    //        rules: {
    //            EmployeeCode: 'required',
    //            EmployeeName: {
    //                required: true,
    //                minlength: 6
    //            },
    //            //dateOfBrith: 'required',
    //            Salary: {
    //                //required: true,
    //                number: true
    //            },
    //            Email: {
    //                required: true,
    //                validEmail: true
    //            },
    //            PossitionID: {
    //                required: true
    //            },
    //            DepartmentID: {
    //                required: true
    //            }
    //        },
    //        messages: {
    //            EmployeeCode: 'mã nhân viên không được để trống',
    //            EmployeeName: {
    //                required: 'tên nhân viên không được để trống',
    //                minlength: 'tên nhân viên quá ngắn'
    //            },
    //            //dateOfBrith: 'ngày sinh không được bỏ trống',
    //            Salary: {
    //                //required: 'tiền lương không được để trống',
    //                number: 'tiền lương không hợp lệ'
    //            },
    //            Email: {
    //                required: 'email không được bỏ trống',
    //                validEmail: 'email không hợp lệ'
    //            },
    //            PossitionID: {
    //                required: 'vui lòng chọn vị trí'
    //            },
    //            DepartmentID: {
    //                required: 'vui lòng chọn phòng ban'
    //            }
    //        },
    //        errorPlacement: function (error, element) {
    //            element.attr('title', error.text());
    //        },
    //        success: function (element) {
    //            $(element).removeAttr('title');
    //        }
    //    });
    //}

    //#endregion

    /**
     * ---------------------------------------------------------------------------------
     * Mở form thêm mới nhân viên và gọi api lấy mã nhân viên theo mã nhân viên lớn nhất
     * Author: LTQUAN (19/10/2020)
     * ---------------------------------------------------------------------------------
     * */
    onAdd() {
        this.method = "GET";
        this.onShowLoading();
        $.ajax({
            url: `${this.url}/code`,
            method: this.method,
            dataType: 'text',
        }).done(res => {
            if (res) {
                // Tránh sự kiện on blur validate cho các trường (*) khi thực hiện cất & thêm
                $("input[name='EmployeeCode']").focus();
                super.onRefreshFormDialog();

                // Gán code lớn nhất trong hệ thống trả về khi thêm mới
                $("input[name='EmployeeCode']").val(res);
            }
        }).fail(err => {
        }).always(() => {
            this.onHideLoading();
            super.onAdd();
        })
    }

    /**
     * -----------------------------------------------
     * Gọi api lấy danh sách departments và possitions
     * Author: LTQUAN (20/10/2020)
     * -----------------------------------------------
     * */
    fetchDataComponent() {
        commonJS.makeSelectOptions($("#form-data select[fetch]"));
    }

    initEvents() {
        super.initEvents();

        // Gán sự kiện so input salary để thẻ span VND có cùng màu border
        $("input[name='Salary']").focus(function () {
            $("#label-vnd").addClass('focus');
        }).focusout(function () {
            $("#label-vnd").removeClass('focus');
        });

        // Kiểm tra keyCode có phải number ở thẻ input Salary hay không
        let isNumber = true;

        // Format giá tiền ở thẻ input Salary
        $("input[name='Salary']").on({
            keypress: function (e) {
                if (e.keyCode < 48 || e.keyCode > 57) {
                    isNumber = false;
                    e.preventDefault()
                } else
                    isNumber = true;
            },
            keyup: function (e) {
                if (isNumber)
                    commonJS.formatCurrencySalary($(this));
                else
                    e.preventDefault();
            }
        });

        $("#btn-load-img").click(this.onSelectedImage);

        $("#btn-delete-img").click(this.onDeleteImage);

        $(".avatar-picture").hover(function () {
            $(this).find("button").fadeIn(200);
        }, function () {
            $(this).find("button").fadeOut(200);
        });

        $(".avatar-picture").find("button").hide();
    }

    /**
     * Mở form chọn ảnh và load ảnh lên thẻ img
     * @param {any} e
     * Author: LTQUAN (20/10/2020)
     */
    onSelectedImage(e) {
        e.preventDefault();
        var fileInput = $("#file-avatar");
        if (fileInput !== null) {
            //Open file dialog to choose image
            fileInput.trigger("click");
            fileInput.change(function () {
                if (this.files && this.files[0]) {
                    //When choose image complete using FileReader to convert image to Base64 string
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("#img-avatar").attr('src', e.target.result);
                        $("input[name='Base64Image']").val(e.target.result.split(",")[1]);
                        $("input[name='AvatarUrl']").val("");
                    }
                    reader.readAsDataURL(this.files[0])
                }
            });
        }
    }

    /**
     * Xóa ảnh trên thẻ img
     * @param {any} e
     * Author: LTQUAN (20/10/2020)
     */
    onDeleteImage(e) {
        e.preventDefault();
        $("#img-avatar").attr("src", "/content/img/Images/user-anonymus.png");
        $("input[name='Base64Image']").val("");
        $("input[name='AvatarUrl']").val("");
    }

    /**
     * Override lại để load ảnh form employee
     * @param {any} obj
     * Author: LTQUAN (20/10/2020)
     */
    setInputDialog(obj) {
        super.setInputDialog(obj);
        let avatarUrl = $("input[name='AvatarUrl']").val();
        if (avatarUrl)
            $("#img-avatar").attr("src", avatarUrl);
    }

    /**
     * Override lại để ẩn img form employee
     * @param {any} obj
     * Author: LTQUAN (20/10/2020)
     */
    onHideDialog() {
        super.onHideDialog();
        setTimeout(() => {
            $("#img-avatar").attr("src", "/content/img/Images/user-anonymus.png");
        }, 400);
    }

    /**
     * Override từ base
     * Author: LTQUAN (20/10/2020)
     * */
    onRefreshFormDialog() {
        super.onRefreshFormDialog();
        $("#img-avatar").attr("src", "/content/img/Images/user-anonymus.png");
    }
}
