<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Mesej-mesej berikut digunakan oleh kelas validator.
    |
    */

    'accepted' => ':attribute mesti diterima.',
    'accepted_if' => ':attribute mesti diterima apabila :other adalah :value.',
    'active_url' => ':attribute bukan URL yang sah.',
    'after' => ':attribute mesti tarikh selepas :date.',
    'after_or_equal' => ':attribute mesti tarikh selepas atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh mengandungi huruf.',
    'alpha_dash' => ':attribute hanya boleh mengandungi huruf, nombor, sengkang dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh mengandungi huruf dan nombor.',
    'array' => ':attribute mesti berupa tatasusunan.',
    'before' => ':attribute mesti tarikh sebelum :date.',
    'before_or_equal' => ':attribute mesti tarikh sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':attribute mesti mempunyai antara :min dan :max item.',
        'file' => ':attribute mesti antara :min dan :max kilobait.',
        'numeric' => ':attribute mesti antara :min dan :max.',
        'string' => ':attribute mesti antara :min dan :max aksara.',
    ],
    'boolean' => 'Medan :attribute mesti benar atau salah.',
    'confirmed' => 'Pengesahan :attribute tidak sepadan.',
    'current_password' => 'Kata laluan tidak betul.',
    'date' => ':attribute bukan tarikh yang sah.',
    'date_equals' => ':attribute mesti tarikh yang sama dengan :date.',
    'date_format' => ':attribute tidak sepadan dengan format :format.',
    'decimal' => ':attribute mesti mempunyai :decimal tempat perpuluhan.',
    'declined' => ':attribute mesti ditolak.',
    'declined_if' => ':attribute mesti ditolak apabila :other adalah :value.',
    'different' => ':attribute dan :other mesti berbeza.',
    'digits' => ':attribute mesti :digits digit.',
    'digits_between' => ':attribute mesti antara :min dan :max digit.',
    'dimensions' => ':attribute mempunyai dimensi imej yang tidak sah.',
    'distinct' => 'Medan :attribute mempunyai nilai berganda.',
    'doesnt_end_with' => ':attribute tidak boleh berakhir dengan salah satu daripada: :values.',
    'doesnt_start_with' => ':attribute tidak boleh bermula dengan salah satu daripada: :values.',
    'email' => ':attribute mesti alamat e-mel yang sah.',
    'ends_with' => ':attribute mesti berakhir dengan salah satu daripada: :values.',
    'enum' => ':attribute yang dipilih tidak sah.',
    'exists' => ':attribute yang dipilih tidak sah.',
    'file' => ':attribute mesti berupa fail.',
    'filled' => 'Medan :attribute mesti mempunyai nilai.',
    'gt' => [
        'array' => ':attribute mesti mempunyai lebih daripada :value item.',
        'file' => ':attribute mesti melebihi :value kilobait.',
        'numeric' => ':attribute mesti melebihi :value.',
        'string' => ':attribute mesti melebihi :value aksara.',
    ],
    'gte' => [
        'array' => ':attribute mesti mempunyai :value item atau lebih.',
        'file' => ':attribute mesti melebihi atau sama dengan :value kilobait.',
        'numeric' => ':attribute mesti melebihi atau sama dengan :value.',
        'string' => ':attribute mesti melebihi atau sama dengan :value aksara.',
    ],
    'hex_color' => 'Medan :attribute mesti warna perenambelasan yang sah.',
    'image' => ':attribute mesti berupa imej.',
    'in' => ':attribute yang dipilih tidak sah.',
    'in_array' => 'Medan :attribute tidak wujud dalam :other.',
    'integer' => ':attribute mesti integer.',
    'ip' => ':attribute mesti alamat IP yang sah.',
    'ipv4' => ':attribute mesti alamat IPv4 yang sah.',
    'ipv6' => ':attribute mesti alamat IPv6 yang sah.',
    'json' => ':attribute mesti rentetan JSON yang sah.',
    'list' => 'Medan :attribute mesti berupa senarai.',
    'lowercase' => ':attribute mesti huruf kecil.',
    'lt' => [
        'array' => ':attribute mesti mempunyai kurang daripada :value item.',
        'file' => ':attribute mesti kurang daripada :value kilobait.',
        'numeric' => ':attribute mesti kurang daripada :value.',
        'string' => ':attribute mesti kurang daripada :value aksara.',
    ],
    'lte' => [
        'array' => ':attribute tidak boleh mempunyai lebih daripada :value item.',
        'file' => ':attribute mesti kurang daripada atau sama dengan :value kilobait.',
        'numeric' => ':attribute mesti kurang daripada atau sama dengan :value.',
        'string' => ':attribute mesti kurang daripada atau sama dengan :value aksara.',
    ],
    'mac_address' => ':attribute mesti alamat MAC yang sah.',
    'max' => [
        'array' => ':attribute tidak boleh mempunyai lebih daripada :max item.',
        'file' => ':attribute tidak boleh melebihi :max kilobait.',
        'numeric' => ':attribute tidak boleh melebihi :max.',
        'string' => ':attribute tidak boleh melebihi :max aksara.',
    ],
    'max_digits' => ':attribute tidak boleh mempunyai lebih daripada :max digit.',
    'mimes' => ':attribute mesti fail jenis: :values.',
    'mimetypes' => ':attribute mesti fail jenis: :values.',
    'min' => [
        'array' => ':attribute mesti mempunyai sekurang-kurangnya :min item.',
        'file' => ':attribute mesti sekurang-kurangnya :min kilobait.',
        'numeric' => ':attribute mesti sekurang-kurangnya :min.',
        'string' => ':attribute mesti sekurang-kurangnya :min aksara.',
    ],
    'min_digits' => ':attribute mesti mempunyai sekurang-kurangnya :min digit.',

    'multiple_of' => ':attribute mesti gandaan :value.',
    'not_in' => ':attribute yang dipilih tidak sah.',
    'not_regex' => 'Format :attribute tidak sah.',
    'numeric' => ':attribute mesti nombor.',
    'password' => [
        'letters' => ':attribute mesti mengandungi sekurang-kurangnya satu huruf.',
        'mixed' => ':attribute mesti mengandungi sekurang-kurangnya satu huruf besar dan satu huruf kecil.',
        'numbers' => ':attribute mesti mengandungi sekurang-kurangnya satu nombor.',
        'symbols' => ':attribute mesti mengandungi sekurang-kurangnya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Sila pilih :attribute yang berbeza.',
    ],
    'present' => 'Medan :attribute mesti wujud.',
    'prohibited' => 'Medan :attribute dilarang.',
    'prohibited_if' => 'Medan :attribute dilarang apabila :other adalah :value.',
    'prohibited_unless' => 'Medan :attribute dilarang kecuali :other berada dalam :values.',
    'prohibits' => 'Medan :attribute melarang :other daripada wujud.',
    'regex' => 'Format :attribute tidak sah.',
    'required' => 'Medan :attribute diperlukan.',
    'required_array_keys' => 'Medan :attribute mesti mengandungi entri untuk: :values.',
    'required_if' => 'Medan :attribute diperlukan apabila :other adalah :value.',
    'required_if_accepted' => 'Medan :attribute diperlukan apabila :other diterima.',
    'required_unless' => 'Medan :attribute diperlukan kecuali :other berada dalam :values.',
    'required_with' => 'Medan :attribute diperlukan apabila :values wujud.',
    'required_with_all' => 'Medan :attribute diperlukan apabila :values wujud.',
    'required_without' => 'Medan :attribute diperlukan apabila :values tidak wujud.',
    'required_without_all' => 'Medan :attribute diperlukan apabila tiada :values wujud.',
    'same' => ':attribute dan :other mesti sepadan.',
    'size' => [
        'array' => ':attribute mesti mengandungi :size item.',
        'file' => ':attribute mesti :size kilobait.',
        'numeric' => ':attribute mesti :size.',
        'string' => ':attribute mesti :size aksara.',
    ],
    'starts_with' => ':attribute mesti bermula dengan salah satu daripada: :values.',
    'string' => ':attribute mesti berupa teks.',
    'timezone' => ':attribute mesti zon masa yang sah.',
    'unique' => ':attribute telah digunakan.',
    'uploaded' => ':attribute gagal dimuat naik.',
    'uppercase' => ':attribute mesti huruf besar.',
    'url' => ':attribute mesti URL yang sah.',
    'ulid' => ':attribute mesti ULID yang sah.',
    'uuid' => ':attribute mesti UUID yang sah.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */
    'attributes' => [
        'name' => 'Nama',
        'email' => 'E-mel',
        'password' => 'Kata Laluan',
        'roles' => 'Peranan',
        'pejabat_pendidikan_id' => 'Pejabat Pendidikan',
        'sektor_id' => 'Sektor',
        'unit_id' => 'Unit',
        'pengerusi' => 'Pengerusi',
        'kategori' => 'Kategori',
        'tarikh' => 'Tarikh',
        'hari' => 'Hari',
        'masa' => 'Masa',
        'tempat' => 'Tempat',
        'dicatat_oleh' => 'Dicatat Oleh',
        'jawatan_pencatat' => 'Jawatan Pencatat',
        'disahkan_oleh' => 'Disahkan Oleh',
        'jawatan_pengesah' => 'Jawatan Pengesah',
        'isu' => 'Isu',
        'fokus' => 'Fokus',
        'tindakan' => 'Tindakan',
    ],
];
