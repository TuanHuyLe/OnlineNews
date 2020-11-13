$(document).ready(function () {
    const newJs = new New();
});

class New extends Base {
    constructor() {
        super(api.NEW_API, message.news);
    }

    initEvents() {
        super.initEvents();
        $("#image-input").on("change", function () {
            let reader = new FileReader();
            reader.onload = function (e) {
                $("input[name='image_base64']").val(e.target.result.split(",")[1]);
            }
            reader.readAsDataURL(this.files[0])
            let fileName = $(this).val().split("\\").pop();
            $("#image-name").html(fileName);
            $("input[name='image_name']").val(fileName);
        });
    }

    /**
     * Thêm các sự kiện của news
     * Author: LTQUAN (10/11/2020)
     */
    setInputDialog(obj) {
        super.setInputDialog(obj);
        $('#content').val(obj['content']);
        tinyMCE.activeEditor.setContent(obj['content']);
    }

    refreshFormDialog(){
        super.refreshFormDialog();
        tinyMCE.activeEditor.setContent('');
        $("#image-name").html('');
    }

    //#region Validate sử dụng lib validation

    /**
     * Khởi tạo validate employee
     * Author: LTQuan (01/10/2020)
     */
    initValidate() {
        // Custom rule email valid
        // $.validator.addMethod("validEmail", function (value, element) {
        //     return this.optional(element) || /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
        // }, 'Please enter a valid email address.');
        $("#form-data").validate({
            onfocusout: function (element) {
                if (element.tagName === "TEXTAREA" || (element.tagName === "INPUT" && element.type !== "password")) {
                    element.value = $.trim(element.value);
                }
                return $(element).valid();
            },
            rules: {
                title: 'required',
            },
            messages: {
                title: 'tên thể loại không được để trống'
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
}
