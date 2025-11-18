// Espera a que el DOM esté completamente cargado para ejecutar el script
document.addEventListener('DOMContentLoaded', () => {

    // --- 1. ESTADO DE LA APLICACIÓN ---
    // Equivalente a `useState` en React
    let state = {
        notebooks: [
            { id: 1, title: 'Proyecto Personal', notes: 12, color: 'warning', lastModified: '2024-11-15', description: 'Ideas y planificación' },
            { id: 2, title: 'Estudios', notes: 8, color: 'primary', lastModified: '2024-11-14', description: 'Apuntes de cursos' },
            { id: 3, title: 'Trabajo', notes: 25, color: 'success', lastModified: '2024-11-13', description: 'Reuniones y tareas' }
        ],
        showModal: false,
        editingNotebook: null,
        searchTerm: '',
        formData: {
            title: '',
            description: '',
            color: 'warning'
        }
    };

    // --- 2. CONSTANTES ---
    const colors = [
        { value: 'warning', label: 'Amarillo', class: 'bg-warning' },
        { value: 'primary', label: 'Azul', class: 'bg-primary' },
        { value: 'success', label: 'Verde', class: 'bg-success' },
        { value: 'danger', label: 'Rojo', class: 'bg-danger' },
        { value: 'info', label: 'Celeste', class: 'bg-info' }
    ];

    // --- 3. SELECTORES DEL DOM ---
    // Referencias a los elementos HTML que vamos a manipular
    const grid = document.getElementById('notebooks-grid');
    const emptyState = document.getElementById('empty-state');
    const searchInput = document.getElementById('search-input');
    const notebookCountText = document.getElementById('notebook-count-text');
    const newNotebookBtn = document.getElementById('new-notebook-btn');
    
    // Elementos del Modal
    const modalOverlay = document.getElementById('modal-overlay');
    const modalContent = document.getElementById('modal-content');
    const modalTitle = document.getElementById('modal-title');
    const notebookForm = document.getElementById('notebook-form');
    const formTitle = document.getElementById('form-title');
    const formDescription = document.getElementById('form-description');
    const colorOptionsContainer = document.getElementById('color-options-container');
    const modalCancelBtn = document.getElementById('modal-cancel-btn');
    const modalSubmitBtn = document.getElementById('modal-submit-btn');

    // --- 4. FUNCIONES DE RENDERIZADO ---
    // Función principal que actualiza toda la UI basada en el estado
    // Esta es la clave para simular la "reactividad"
    function render() {
        renderNotebookGrid();
        renderModal();
        renderNotebookCount();
        
        // ¡Importante! Llama a lucide.createIcons() después de renderizar
        // para que los <i> tags se conviertan en SVGs
        lucide.createIcons();
    }

    // Renderiza el contador de cuadernos
    function renderNotebookCount() {
        const count = state.notebooks.length;
        notebookCountText.textContent = `${count} ${count === 1 ? 'cuaderno' : 'cuadernos'} en total`;
    }

    // Renderiza la cuadrícula de cuadernos
    function renderNotebookGrid() {
        // 1. Filtrar cuadernos
        const filteredNotebooks = state.notebooks.filter(nb =>
            nb.title.toLowerCase().includes(state.searchTerm.toLowerCase()) ||
            nb.description.toLowerCase().includes(state.searchTerm.toLowerCase())
        );

        // 2. Limpiar la cuadrícula actual
        grid.innerHTML = '';

        // 3. Mostrar/ocultar estado vacío
        if (filteredNotebooks.length === 0) {
            emptyState.classList.remove('hidden');
            grid.classList.add('hidden');
        } else {
            emptyState.classList.add('hidden');
            grid.classList.remove('hidden');

            // 4. Crear y añadir cada tarjeta
            filteredNotebooks.forEach(notebook => {
                const cardHTML = `
                    <div class="notebook-card border-${notebook.color}">
                        <div class="notebook-header">
                            <div class="notebook-icon bg-${notebook.color}">
                                <i data-lucide="book" size="24"></i>
                            </div>
                            <div style="flex: 1;">
                                <h3 class="notebook-title">${notebook.title}</h3>
                            </div>
                        </div>
                        
                        <p class="notebook-description">${notebook.description}</p>
                        
                        <div class="notebook-meta">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i data-lucide="file-text" size="16"></i>
                                <span>${notebook.notes} notas</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i data-lucide="calendar" size="16"></i>
                                <span>${notebook.lastModified}</span>
                            </div>
                        </div>

                        <div class="notebook-actions">
                            <button class="btn-edit" data-id="${notebook.id}">
                                <i data-lucide="edit-2" size="16"></i>
                                Editar
                            </button>
                            <button class="btn-danger" data-id="${notebook.id}">
                                <i data-lucide="trash-2" size="16"></i>
                                Eliminar
                            </button>
                        </div>
                    </div>
                `;
                // Añadimos el HTML al final de la cuadrícula
                grid.insertAdjacentHTML('beforeend', cardHTML);
            });
        }
    }

    // Renderiza el modal (mostrar/ocultar y poblar datos)
    function renderModal() {
        if (state.showModal) {
            modalOverlay.classList.remove('hidden');

            // Actualizar contenido del modal
            const isEditing = !!state.editingNotebook;
            modalTitle.textContent = isEditing ? 'Editar Cuaderno' : 'Nuevo Cuaderno';
            modalSubmitBtn.textContent = isEditing ? 'Guardar Cambios' : 'Crear Cuaderno';

            // Poblar formulario
            formTitle.value = state.formData.title;
            formDescription.value = state.formData.description;

            // Renderizar selector de color
            renderColorSelector();

        } else {
            modalOverlay.classList.add('hidden');
        }
    }
    
    // Renderiza las opciones de color dentro del modal
    function renderColorSelector() {
        colorOptionsContainer.innerHTML = ''; // Limpiar opciones
        colors.forEach(color => {
            const isSelected = state.formData.color === color.value;
            const colorDiv = document.createElement('div');
            colorDiv.className = `color-option ${color.class} ${isSelected ? 'selected' : ''}`;
            colorDiv.title = color.label;
            colorDiv.dataset.value = color.value; // Guardamos el valor en un data-attribute
            colorOptionsContainer.appendChild(colorDiv);
        });
    }

    // --- 5. MANEJADORES DE LÓGICA (Equivalente a tus funciones) ---

    // Cierra el modal y resetea el estado del formulario
    function closeModal() {
        state.showModal = false;
        state.editingNotebook = null;
        state.formData = { title: '', description: '', color: 'warning' };
        // Re-renderizar la UI
        render();
    }

    // Abre el modal para crear un nuevo cuaderno
    function openNewModal() {
        state.showModal = true;
        state.editingNotebook = null;
        state.formData = { title: '', description: '', color: 'warning' };
        render();
        formTitle.focus(); // Pone el foco en el primer input
    }
    
    // Maneja el envío del formulario (Crear o Editar)
    function handleSubmit(e) {
        e.preventDefault(); // Evita que la página se recargue

        // Formatear la fecha actual
        const today = new Date().toISOString().split('T')[0];

        if (state.editingNotebook) {
            // --- Lógica de Edición ---
            state.notebooks = state.notebooks.map(nb =>
                nb.id === state.editingNotebook.id
                    ? { ...nb, ...state.formData, lastModified: today }
                    : nb
            );
        } else {
            // --- Lógica de Creación ---
            const newNotebook = {
                id: Date.now(), // ID único simple
                ...state.formData,
                notes: 0,
                lastModified: today
            };
            state.notebooks = [...state.notebooks, newNotebook];
        }
        
        closeModal(); // Cierra el modal y re-renderiza todo
    }

    // Maneja la apertura del modal para editar
    function handleEdit(id) {
        const notebook = state.notebooks.find(nb => nb.id === id);
        if (!notebook) return;

        state.editingNotebook = notebook;
        state.formData = {
            title: notebook.title,
            description: notebook.description,
            color: notebook.color
        };
        state.showModal = true;
        
        render();
        formTitle.focus();
    }

    // Maneja la eliminación de un cuaderno
    function handleDelete(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este cuaderno?')) {
            state.notebooks = state.notebooks.filter(nb => nb.id !== id);
            // Re-renderizar la UI
            render();
        }
    }

    // --- 6. EVENT LISTENERS ---

    // Botón "Nuevo Cuaderno"
    newNotebookBtn.addEventListener('click', openNewModal);

    // Botones del Modal
    modalCancelBtn.addEventListener('click', closeModal);
    // Cerrar al hacer clic fuera del contenido
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });

    // Formulario
    notebookForm.addEventListener('submit', handleSubmit);
    
    // Actualizar el estado 'formData' mientras el usuario escribe
    formTitle.addEventListener('input', (e) => {
        state.formData.title = e.target.value;
    });
    formDescription.addEventListener('input', (e) => {
        state.formData.description = e.target.value;
    });

    // Búsqueda (se actualiza al escribir)
    searchInput.addEventListener('input', (e) => {
        state.searchTerm = e.target.value;
        renderNotebookGrid(); // Solo necesitamos re-renderizar la cuadrícula
        lucide.createIcons(); // ...y los iconos dentro de ella
    });

    // --- DELEGACIÓN DE EVENTOS ---

    // Para los botones de color en el modal
    colorOptionsContainer.addEventListener('click', (e) => {
        const colorOption = e.target.closest('.color-option');
        if (colorOption) {
            const selectedColor = colorOption.dataset.value;
            state.formData.color = selectedColor;
            renderColorSelector(); // Re-renderizar solo los colores
        }
    });

    // Para los botones "Editar" y "Eliminar" en la cuadrícula
    grid.addEventListener('click', (e) => {
        // e.target.closest() busca el botón más cercano,
        // incluso si se hizo clic en el icono <i> dentro de él
        
        const editBtn = e.target.closest('.btn-edit');
        if (editBtn) {
            const id = Number(editBtn.dataset.id);
            handleEdit(id);
            return; // Salir para no procesar el de eliminar
        }

        const deleteBtn = e.target.closest('.btn-danger');
        if (deleteBtn) {
            const id = Number(deleteBtn.dataset.id);
            handleDelete(id);
        }
    });

    // --- 7. RENDERIZADO INICIAL ---
    // Llama a render() una vez al cargar la página
    render();
});