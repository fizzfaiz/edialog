<?php

/**
 * Permission Module Configuration
 * 
 * This file maps permission prefixes to display labels (Bahasa Melayu).
 * When you add a new module, just add its prefix and label here.
 * The system will auto-detect all permissions from the database and group them.
 * 
 * Permission naming convention: {action}-{module_name}
 * Example: view-dialog-prestasi, create-invois, edit-user
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Action Labels (Bahasa Melayu)
    |--------------------------------------------------------------------------
    |
    | These are the standard CRUD action prefixes and their display labels.
    | The system auto-extracts the action from permission names.
    | For example: "view-dialog-prestasi" → action "view" → label "Lihat"
    |
    */
    'action_labels' => [
        'view'     => 'Lihat',
        'create'   => 'Tambah',
        'edit'     => 'Edit',
        'delete'   => 'Padam',
        'manage'   => 'Urus',
        'feedback' => 'Maklum Balas',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Labels (Bahasa Melayu)
    |--------------------------------------------------------------------------
    |
    | Map module prefixes to their display labels.
    | When adding a new module, add an entry here:
    |   'prefix' => 'Nama Paparan',
    |
    | The system will auto-detect any modules not listed here
    | and display them using ucfirst() of the prefix.
    |
    */
    'module_labels' => [
        'role'     => 'Peranan (Roles)',
        'user'     => 'Pengguna (Users)',
        'dialog-prestasi'   => 'Dialog Prestasi',
        'sektor-unit'       => 'Sektor & Unit',
        'settings' => 'Tetapan Sistem',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Icons (Bootstrap Icons)
    |--------------------------------------------------------------------------
    |
    | Icons displayed next to each module section header.
    | Uses Bootstrap Icons class names.
    |
    */
    'module_icons' => [
        'role'            => 'bi-shield-lock',
        'user'            => 'bi-people-fill',
        'dialog-prestasi'          => 'bi-file-earmark-text',
        'sektor-unit'       => 'bi-diagram-3',
        'manage-settings' => 'bi-gear',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Icon
    |--------------------------------------------------------------------------
    |
    | Icon used for modules not explicitly mapped above.
    |
    */
    'default_icon' => 'bi-folder2-open',

];