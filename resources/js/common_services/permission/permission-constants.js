export const PERMISSION_CONSTANTS = {
    //Start -> second level permissions
    USER: { module: "users", subModule: "" },
    USERGROUP: { module: "usergroups", subModule: "" },
    ADMINUSER: { module: "admin-users", subModule: "" },
    ROLE: { module: "roles", subModule: "" },
    PERMISSION: { module: "permissions", subModule: "" },
    BRAND: { module: "brands", subModule: "" },
    CATEGORY: { module: "categories", subModule: "" },
    PRODUCT: { module: "product", subModule: "" },
    ORDER: { module: "orders", subModule: "" },
    VOUCHER: { module: "vouchers", subModule: "" },
    ROLEPERMISSION: { module: "permission-role-mappings", subModule: "" },
    DASHBOARD: { module: "dashboard", subModule: "" },
    CATALOGUE: { module: "managecatalogues", subModule: "" },
    CONTACTUS: { module: "managecontacts", subModule: "" },
    //End -> second level permissions

    //Start -> Permission Dialog
    PERMISSION_DIALOG_TITLE: "Permission Error",
    PERMISSION_DIALOG_MSG:
        "You don't have permission to access this module, please contact your Administrator!",
    PERMISSION_DIALOG_FUNCTIONALITY_MSG:
        "You don't have permission to access this functionality, please contact your Administrator!",
    //End - Permission Dialog

    CAN_ACCESS: "can-access",
    CAN_EDIT: "can-edit",
    CAN_DELETE: "can-delete"
};
