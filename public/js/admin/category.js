$(document).ready(function () {
    const employee = new Category();
});

class Category extends Base {
    constructor() {
        super(api.CATEGORY_API, message.categories);
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
           },
           messages: {
               name: 'tên thể loại không được để trống'
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
