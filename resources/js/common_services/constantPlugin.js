/*//https://stackoverflow.com/questions/42662144/how-could-i-use-const-in-vue-template
//https://dev.to/nkoik/writing-a-very-simple-plugin-in-vuejs---example-8g8*/
import { PERMISSION_CONSTANTS } from "./permission/permission-constants";
const YOUR_CONSTS = {
    DATE_PICKER_CONST: "YYYY-MM-DD",
    DATE_CONST: "DD/MM/YYYY",
    DATE_FORMAT_CONST: "DD-MM-YYYY",
    TIME_CONST: "hh:mm A",
    DATE_TIME_CONST: "DD/MM/YYYY hh:mm A",
    DATE_TIME_WITH_SECONDS: "DD-MM-YYYY-hh:mm:s",
    DELETE_NOT_ALLOWED:
        "You cannot delete this group as users are associated with it.",
    CREATE_ACTION: "Inserted Successfully",
    UPDATE_ACTION: "Edited Successfully",
    DELETE_ACTION: "Deleted Successfully",

    REGISTER_SUCCESS:
        "Sucessfully registered. Please check your email for Verification",
    BTN_CANCEL: "Cancel",
    BTN_SUBMIT: "Submit",
    BTN_UPDATE: "Update",
    BTN_DELETE: "Delete",
    BTN_CLOSE: "Close",
    BTN_OK: "OK",
    BTN_SEND_MAIL: "Send Mail",
    DELETE_TITLE: "Delete Confirmation",
    FILE_UPLOAD: "File uploaded successfully",
    DOWNLOAD_SAMPLE_CSV: "Sample CSV downloaded successfully",
    DOWNLOAD_ZIP_CSV: "Sample Zip downloaded successfully",
    ROLE_TITLE: "Add Role",
    ROLE_DESC: "Please Enter Your Role",
    WARNING: "Are you sure you want to delete this item?",
    MULTIPLE_DELETE_WARNING_START: "Are you sure you want to delete ",
    MULTIPLE_DELETE_WARNING_END: " items?",
    EMAIL_SEND_MESSAGE: "Email sent successfully",
    RESET_PASSWORD: "Password reset successfully",
    CHANGED_PASSWORD: "Password changed successfully",
    NOIMAGE: "No Image Found",
    TXT_UPDATE: "Update",
    TXT_ADD: "Add",
    TXT_CREATE: "Create",
    PARENT_CATEGORY_DELETE:
        "Deleting this item will delete it's sub-categories as well. Are you sure you want to delete this item?",
    FILE_UPLOAD_MSG: "Drag and drop your csv file",
    ZIP_FILE_UPLOAD_MSG: "Drag and drop your zip",
    SELECT_FILE_LABEL: "Click here to upload csv file",
    SELECT_ZIP_LABEL: "Click here to upload zip",
    UPLOAD_CSV: "Upload csv files only",
    REWARD_NEGATIVE_POINTS: "Negative points are not allowed",
    FILE_SUBMIT: "Submit",
    VOUCHER_STATUS: "Voucher status updated successfully",
    VOUCHER_LINKS_DOWNLOAD_1ST_LINE_START: "You have generated ",
    VOUCHER_LINKS_DOWNLOAD_1ST_LINE_END: " vouchers",
    VOUCHER_LINKS_DOWNLOAD_2ND_LINE: "You can download the file",
    DRAG_AND_DROP_MSG: "Drop files to upload",
    CLICK_DOWNLOAD_BTN: "Click here to download",
    ADD_TOOLTIP: "Add",
    EDIT_TOOLTIP: "Edit",
    DELETE_TOOLTIP: "Delete",
    VIEW_TOOLTIP: "View",
    SEND_VOUCHER_MAIL: "Send Voucher Link",
    EXPORT_TOOLTIP: "Export",
    BTN_SKIP: "Skip for now",
    LOYREX_ADMIN_LOGO: "/images/admin-logo-auth.png",
    LOYREX_LOGO: "/images/loyrex-logo.jpeg",
    APPLY_FILTER_BUTTON: "Apply",
    RESET_FILTER_BUTTON: "Reset",
    NO_DATA_MSG: "No data available",
    EDIT_IMAGE_TOOLTIP: "Edit Images",
    DROPDOWN_PER_PAGE: 10,
    DELETE_PRODUCT_IMAGE: "Image deleted successfully.",
    ADD_PRODUCT_IMAGE: "Image added successfully.",
    ADMIN_DELETE_WARNING: "Master Admin role can not be deleted.",
    ORDER_STATUS_UPDATE_BTN: "Update Status",
    ORDER_DELIVERY_UPDATE_BTN: "Update Delivery Details",
    IMPORT_TAB_TEXT: "Bulk Management",

    AWS_S3_SIGNER_URL: "aws-s3-signer-url",
    AWS_S3_POST_URL: "https://httpbin.org/post",
    FEATURE_IMG_EXIST:
        "The feature image is already uploaded, please delete existing feature image to upload new feature image.",

    ...PERMISSION_CONSTANTS
};

export default {
    install(Vue, options) {
        Vue.prototype.$getConst = key => {
            return YOUR_CONSTS[key];
        };
    }
};
