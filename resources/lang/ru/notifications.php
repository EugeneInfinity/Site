<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы для сообщений пользователю
    |--------------------------------------------------------------------------
    |
    */
    'message_data_invalid' => 'Полученные данные не прошли валидацию', //The given data was invalid

    'store' => [
        'success' => 'Данные успешо сохранены!',
    ],
    'update' => [
        'success' => 'Данные успешно обновлены!',
    ],
    'destroy' => [
        'success' => 'Данные успешно удалены!',
        'error' => 'Ошибка удаления. Возможно у вас нет прав или доступа!',
        'error_children' => 'Ошибка удаления! Для удаления текущего элемента, нужно удалить все его дочернии элементы.',
    ],
    'operation' => [
        'success' => 'Операция успешно выполнена',
        'error' => 'Ошибка выполнения операции',
    ],

    'file' => [
        'store' => [
            'success' => 'Файл успешно загружен!',
            'error' => 'Ошибка загрузки файла. Возможно вы превысили свой лимит',
        ],
        'destroy' => [
            'success' => 'Файл успешно удален!',
            'error' => 'Ошибка удаления файла. Возможно указанного файла не существует'
        ],
    ],

    //'Отлично!' => 'Excellent!',
    //'Ошибка!' => 'Failure!',
    //'Предупреждение!' => 'Warning!',
    //'Инфориация!' => 'Information!',

    'excellent' => 'Отлично!',
    'failure' => 'Failure!',
    'warning' => 'Warning!',
    'information' => 'Information!',
];
