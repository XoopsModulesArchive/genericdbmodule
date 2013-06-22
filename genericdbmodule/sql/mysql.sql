CREATE TABLE xgdb_data (
    did INT UNSIGNED NOT NULL AUTO_INCREMENT,
    add_uid INT UNSIGNED NOT NULL,
    add_date DATETIME NOT NULL,
    xgdb_string VARCHAR(255),
    xgdb_number INT,
    xgdb_cbox VARCHAR(255),
    xgdb_radio VARCHAR(255),
    xgdb_file VARCHAR(255),
    xgdb_image VARCHAR(255),
    xgdb_tarea TEXT,
    xgdb_xtarea VARCHAR(255),
    xgdb_select VARCHAR(255),
    xgdb_mselect VARCHAR(255),
    xgdb_date DATE,
    PRIMARY KEY(did)
) ENGINE=MyISAM;

CREATE TABLE xgdb_his (
    hid INT UNSIGNED NOT NULL AUTO_INCREMENT,
    did INT UNSIGNED NOT NULL,
    operation VARCHAR(255) NOT NULL,
    update_uid INT UNSIGNED NOT NULL,
    update_date DATETIME NOT NULL,
    xgdb_string VARCHAR(255),
    xgdb_number INT,
    xgdb_cbox VARCHAR(255),
    xgdb_radio VARCHAR(255),
    xgdb_file VARCHAR(255),
    xgdb_image VARCHAR(255),
    xgdb_tarea TEXT,
    xgdb_xtarea VARCHAR(255),
    xgdb_select VARCHAR(255),
    xgdb_mselect VARCHAR(255),
    xgdb_date DATE,
    PRIMARY KEY(hid)
) ENGINE=MyISAM;

CREATE TABLE xgdb_item (
    `iid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(255) NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `required` TINYINT(1) UNSIGNED NOT NULL,
    `show_gids` VARCHAR(255),
    `sequence` INT UNSIGNED NOT NULL,
    `search` TINYINT(1) UNSIGNED NOT NULL,
    `list` TINYINT(1) UNSIGNED NOT NULL,
    `add` TINYINT(1) UNSIGNED NOT NULL,
    `update` TINYINT(1) UNSIGNED NOT NULL,
    `detail` TINYINT(1) UNSIGNED NOT NULL,
    `site_search` TINYINT(1) UNSIGNED NOT NULL,
    `duplicate` TINYINT(1) UNSIGNED NOT NULL,
    `search_desc` TEXT,
    `show_desc` TEXT,
    `input_desc` TEXT,
    `disp_cond` TINYINT(1) UNSIGNED,
    `value_type` VARCHAR(255),
    `value_range_min` INT,
    `value_range_max` INT,
    `default` TEXT,
    `size` INT UNSIGNED,
    `max_length` INT UNSIGNED,
    `search_cond` TINYINT(1) UNSIGNED,
    `options` TEXT,
    `option_br` TINYINT(1) UNSIGNED,
    `rows` INT UNSIGNED,
    `cols` INT UNSIGNED,
    `html` TINYINT(1) UNSIGNED,
    `smily` TINYINT(1) UNSIGNED,
    `xcode` TINYINT(1) UNSIGNED,
    `image` TINYINT(1) UNSIGNED,
    `br` TINYINT(1) UNSIGNED,
    `max_file_size` INT UNSIGNED,
    `max_image_size` INT UNSIGNED,
    `allowed_exts` TEXT,
    `allowed_mimes` TEXT,
    `xgdb_name` VARCHAR(255),
    PRIMARY KEY(iid)
) ENGINE=MyISAM;

#                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   name,           caption,                type,      required, show_gids, sequence, search, list, add, update, detail, site_search, duplicate, search_desc, show_desc,   input_desc,disp_cond, value_type, value_range_min, value_range_max, default, size, max_length, search_cond, options,                                                                                       option_br, rows, cols, html, smily, xcode, image, br,   max_file_size, max_image_size, allowed_exts,          allowed_mimes,                                                xgdb_name
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_string',  'Text Box String A',    'text',    1,        NULL,      10,       1,      1,    1,   1,      1,      1,           1,         '',          '',          '',        0,         'string',   NULL,            NULL,            '',      32,   255,        1,           NULL,                                                                                          NULL,      NULL, NULL, NULL, NULL,  NULL,  NULL,  NULL, NULL,          NULL,           NULL,                  NULL,                                                         NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_number',  'Text Box Number A',    'number',  1,        NULL,      10,       1,      1,    1,   1,      1,      1,           1,         '',          '',          '',        0,         'int',      0,               9999,            '',      4,    4,          NULL,        NULL,                                                                                          NULL,      NULL, NULL, NULL, NULL,  NULL,  NULL,  NULL, NULL,          NULL,           NULL,                  NULL,                                                         NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_cbox',    'Check Box A',          'cbox',    0,        NULL,      20,       1,      1,    1,   1,      1,      1,           0,         '',          '',          '',        0,         'string',   NULL,            NULL,            '',      NULL, NULL,       1,           "Check Box 1|Check Box 1\nCheck Box 2|Check Box 2\nCheck Box 3|Check Box 3",                   1,         NULL, NULL, NULL, NULL,  NULL,  NULL,  NULL, NULL,          NULL,           NULL,                  NULL,                                                         NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_radio',   'Radio Button A',       'radio',   1,        NULL,      30,       1,      1,    1,   1,      1,      1,           0,         '',          '',          '',        0,         'string',   NULL,            NULL,            '',      NULL, NULL,       NULL,        "Radio Button 1|Radio Button 1\nRadio Button 2|Radio Button 2\nRadio Button 3|Radio Button 3", 0,         NULL, NULL, NULL, NULL,  NULL,  NULL,  NULL, NULL,          NULL,           NULL,                  NULL,                                                         NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_select',  'Pulldown Menu A',      'select',  0,        NULL,      40,       1,      1,    1,   1,      1,      1,           0,         '',          '',          '',        0,         'string',   NULL,            NULL,            '',      NULL, NULL,       NULL,        "Menu 1|Menu 1\nMenu 2|Menu 2\nMenu 3|Menu 3",                                                 NULL,      NULL, NULL, NULL, NULL,  NULL,  NULL,  NULL, NULL,          NULL,           NULL,                  NULL,                                                         NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_mselect', 'List Box A',           'mselect', 1,        NULL,      50,       1,      1,    1,   1,      1,      1,           0,         '',          '',          '',        0,         'string',   NULL,            NULL,            '',      3,    NULL,       1,           "List Box 1|List Box 1\nList Box 2|List Box 2\nList Box 3|List Box 3",                         NULL,      NULL, NULL, NULL, NULL,  NULL,  NULL,  NULL, NULL,          NULL,           NULL,                  NULL,                                                         NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_tarea',   'Text Area A',          'tarea',   0,        NULL,      60,       1,      1,    1,   1,      1,      1,           0,         '',          '',          '',        0,         NULL,       NULL,            NULL,            '',      32,   255,        NULL,        NULL,                                                                                          NULL,      5,    32,   0,    0,     0,     0,     1,    NULL,          NULL,           NULL,                  NULL,                                                         NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_xtarea',  'BB Code Text Area A',  'xtarea',  1,        NULL,      70,       1,      1,    1,   1,      1,      1,           0,         '',          '',          '',        0,         NULL,       NULL,            NULL,            '',      32,   255,        NULL,        NULL,                                                                                          NULL,      5,    32,   0,    1,     1,     1,     1,    NULL,          NULL,           NULL,                  NULL,                                                         NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_file',    'File A',               'file',    0,        NULL,      80,       1,      1,    1,   1,      1,      1,           0,         '',          '',          '',        0,         NULL,       NULL,            NULL,            NULL,    32,   255,        1,           NULL,                                                                                          NULL,      NULL, NULL, NULL, NULL,  NULL,  NULL,  NULL, 100,           NULL,           "pdf",                 "application/pdf\napplication/x-pdf",                         NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_image',   'Image A',              'image',   1,        NULL,      90,       1,      1,    1,   1,      1,      1,           0,         '',          '',          '',        0,         NULL,       NULL,            NULL,            NULL,    32,   255,        1,           NULL,                                                                                          NULL,      NULL, NULL, NULL, NULL,  NULL,  NULL,  NULL, 100,           600,            "jpg\njpeg\ngif\npng", "image/jpeg\nimage/gif\nimage/png\nimage/x-png\nimage/pjpeg", NULL);
INSERT INTO xgdb_item (`name`, `caption`, `type`, `required`, `show_gids`, `sequence`, `search`, `list`, `add`, `update`, `detail`, `site_search`, `duplicate`, `search_desc`, `show_desc`, `input_desc`, `disp_cond`, `value_type`, `value_range_min`, `value_range_max`, `default`, `size`, `max_length`, `search_cond`, `options`, `option_br`, `rows`, `cols`, `html`, `smily`, `xcode`, `image`, `br`, `max_file_size`, `max_image_size`, `allowed_exts`, `allowed_mimes`, `xgdb_name`) VALUES('xgdb_date',    'Date A',               'date',    0,        NULL,      100,      1,      1,    1,   1,      1,      1,           0,         '',          '',          '',        0,         NULL,       NULL,            NULL,            NULL,    NULL, NULL,       NULL,        NULL,                                                                                          NULL,      NULL, NULL, NULL, NULL,  NULL,  NULL,  NULL, NULL,          NULL,           NULL,                  NULL,                                                         NULL);
