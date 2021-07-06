const en = {
    auth: {
        login: {
            email: 'Email',
            password: 'Password',
            rememberMe: "Remember me",
            forgotPassword: "Forgot your password?",
            notRegistered: "Not registered?"
        },
        forgotPassword: {
            message:
                "Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. ",
            confirm: "Email password reset link",
        },
        register: {
            name: 'Name',
            email: 'Email',
            password: 'Password',
            confirmPassword: 'Confirm password',
            alreadyRegistered: 'Already registered?',
            register: 'Register',
        },
    },
    appBar: {
        logout: "Log out"
    },
    indexPage: {
        title: "Products",
        tableColumns: {
            name: "Name",
            description: "Description",
            price: "Price",
            quantity: "Quantity",
            owner: "Owner",
            actions: "Actions"
        },
        tablePagination: {
            no_results_found: "No results",
            previous: "Previous",
            next: "Next",
            to: "to",
            of: "of",
            results: "results"
        },
        deleteConfirmation: {
            title: "Remove product | Remove products",
            message:
                'Are you sure you want to delete the product "{product_name}"? | Are you sure you want to remove the following products?'
        }
    },
    modalActions: {
        edit: "Edit",
        delete: "Delete",
        close: "Close",
        cancel: "Cancel",
        save: "Save"
    },
    rowActions: {
        show: "Show",
        history: "History",
        update: "Edit",
        delete: "Delete"
    },
    tableActions: {
        add: "Add",
        bulkAdd: "Bulk add",
        bulkUpdate: "Bulk edit",
        bulkDelete: "Bulk delete"
    },
    form: {
        name: "Name",
        description: "Description",
        price: "Price",
        quantity: "Quantity",
        image: "Product image",
        selectedImage: "Selected image"
    },
    formModal: {
        createProduct: "Create product | Create products", // Singular and plural support!
        editProduct: "Edit product | Edit products", // Singular and plural support!
        requiredObs: "* Indicates required field",
        addAnotherProduct: "Add another product"
    },
    historyModal: {
        title: 'History of "{product_name}"'
    },
    historyTable: {
        quantity: "Quantity",
        date: "Date",
        modifiedBy: "Modified by"
    },
    removeFormButton: {
        tooltip: "Remove this form"
    },
    showModal: {
        showProduct: "Show product",
        editProduct: "Edit product",
        requiredObs: "* Indicates required field",
        deleteConfirmation: {
            title: "Remove product",
            message:
                'Are you sure you want to delete the product "{product_name}"?',
            proceed: "Yes, remove",
            cancel: "Cancel"
        }
    }
};

export default en;
