<?php

return [
    'actions' => [
        'create' => 'Crear',
        'edit' => 'Editar',
        'view' => 'Ver',
        'delete' => 'Eliminar',
        'save' => 'Guardar',
        'cancel' => 'Cancelar',
        'attach' => 'Adjuntar',
        'detach' => 'Separar',
        'associate' => 'Asociar',
        'dissociate' => 'Disociar',
        'reorder' => 'Reordenar',
        'replicate' => 'Replicar',
        'restore' => 'Restaurar',
        'force_delete' => 'Eliminar permanentemente',
        'bulk_delete' => 'Eliminar seleccionados',
        'bulk_force_delete' => 'Eliminar permanentemente seleccionados',
        'bulk_restore' => 'Restaurar seleccionados',
    ],

    'messages' => [
        'saved' => 'Guardado',
        'deleted' => 'Eliminado',
        'attached' => 'Adjuntado',
        'detached' => 'Separado',
        'associated' => 'Asociado',
        'dissociated' => 'Disociado',
        'reordered' => 'Reordenado',
        'replicated' => 'Replicado',
        'restored' => 'Restaurado',
        'force_deleted' => 'Eliminado permanentemente',
        'bulk_deleted' => 'Seleccionados eliminados',
        'bulk_restored' => 'Seleccionados restaurados',
    ],

    'form' => [
        'actions' => [
            'save' => 'Guardar',
            'cancel' => 'Cancelar
        '],
    ],

    'table' => [
        'actions' => [
            'edit' => 'Editar',
            'view' => 'Ver',
            'delete' => 'Eliminar',
            'restore' => 'Restaurar',
            'force_delete' => 'Eliminar permanentemente',
        ],
        'bulk_actions' => [
            'delete' => 'Eliminar seleccionados',
            'restore' => 'Restaurar seleccionados',
            'force_delete' => 'Eliminar permanentemente seleccionados',
        ],
        'filters' => [
            'indicator' => 'Filtro:',
            'indicators' => 'Filtros:',
            'remove_all' => 'Eliminar todos',
        ],
        'selection_indicator' => 'Seleccionado',
        'selection_indicators' => 'Seleccionados',
        'empty_state' => [
            'label' => 'No se encontraron registros',
            'description' => 'Crea un :model para empezar.',
        ],
        'pagination' => [
            'label' => 'Paginación',
            'overview' => 'Mostrando :first a :last de :total resultados',
            'fields' => [
                'records_per_page' => [
                    'label' => 'Registros por página',
                ],
            ],
        ],
        'records_per_page' => 'Registros por página',
        'search' => [
            'placeholder' => 'Buscar',
        ],
    ],

    'pages' => [
        'create' => [
            'heading' => 'Crear :label',
            'actions' => [
                'create' => 'Crear',
                'create_and_create_another' => 'Crear y crear otro',
            ],
        ],
        'edit' => [
            'heading' => 'Editar :label',
            'actions' => [
                'save' => 'Guardar cambios',
            ],
        ],
        'view' => [
            'heading' => 'Ver :label',
        ],
        'dashboard' => [
            'title' => 'Panel de control',
        ],
    ],

    'resources' => [
        'label' => 'Recurso',
        'labels' => 'Recursos',
    ],

    'layout' => [
        'actions' => [
            'logout' => 'Cerrar sesión',
        ],
        'forms' => [
            'search' => [
                'placeholder' => 'Buscar',
            ],
        ],
        'navigation' => [
            'toggle' => 'Alternar navegación',
        ],
        'sidebar' => [
            'toggle' => 'Alternar barra lateral',
        ],
    ],

    'notifications' => [
        'title' => 'Notificación',
        'titles' => [
            'error' => 'Error',
            'success' => 'Éxito',
            'warning' => 'Advertencia',
        ],
    ],

    'validation' => [
        'unique' => 'El :attribute ya ha sido tomado.',
    ],
];
