const es = {
    auth: {
        login: {
            email: "Email",
            password: "Password",
            rememberMe: "Recordarme",
            forgotPassword: "¿Olvidaste tu password?",
            notRegistered: "¿No estás registrado?"
        },
        forgotPassword: {
            message:
                "¿Olvidaste tu password? No hay problema. Sólo dinos tu dirección de email y te enviaremos un link de reseteo de password que te permitirá elegir uno nuevo. ",
            confirm: "Enviar link de reseteo"
        },
        register: {
            name: "Nombre",
            email: "Email",
            password: "Password",
            confirmPassword: "Confirmar password",
            alreadyRegistered: "¿Ya estás registrado?",
            register: "Registrar"
        }
    },
    appBar: {
        logout: "Cerrar sesión"
    },
    indexPage: {
        title: "Productos",
        tableColumns: {
            name: "Nombre",
            description: "Descripción",
            price: "Precio",
            quantity: "Cantidad",
            owner: "Dueño",
            actions: "Acciones"
        },
        tablePagination: {
            no_results_found: "Sin resultados",
            previous: "Anterior",
            next: "siguiente",
            to: "a",
            of: "de",
            results: "resultados"
        },
        deleteConfirmation: {
            title: "Remover producto | Remover productos",
            message:
                '¿Está seguro que desea eliminar el producto "{product_name}"? | ¿Está seguro que desea eliminar los siguiente productos?'
        }
    },
    modalActions: {
        edit: "Editar",
        delete: "Eliminar",
        close: "Cerrar",
        cancel: "Cancelar",
        save: "Guardar"
    },
    rowActions: {
        show: "Ver",
        history: "Historial",
        update: "Editar",
        delete: "Eliminar"
    },
    tableActions: {
        add: "Agregar",
        bulkAdd: "Adición masiva",
        bulkUpdate: "Edición masiva",
        bulkDelete: "Eliminación masiva"
    },
    form: {
        name: "Nombre",
        description: "Descripción",
        price: "Precio",
        quantity: "Cantidad",
        image: "Imagen del producto",
        selectedImage: "Imagen seleccionada"
    },
    formModal: {
        createProduct: "Crear producto | Crear productos", // Singular and plural support!
        editProduct: "Editar producto | Editar productos", // Singular and plural support!
        requiredObs: "* Indica campo requerido",
        addAnotherProduct: "Agregar otro producto"
    },
    historyModal: {
        title: 'Historial de "{product_name}"'
    },
    historyTable: {
        quantity: "Cantidad",
        date: "Fecha",
        modifiedBy: "Modificado por"
    },
    removeFormButton: {
        tooltip: "Remover este formulario"
    },
    showModal: {
        showProduct: "Ver producto",
        editProduct: "Editar producto",
        requiredObs: "* Indica campo requerido",
        deleteConfirmation: {
            title: "Remover producto",
            message:
                '¿Está seguro que desea remover el producto "{product_name}"?',
            proceed: "Si, remover",
            cancel: "Cancelar"
        }
    }
};

export default es;
