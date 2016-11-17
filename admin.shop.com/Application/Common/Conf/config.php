<?php
return array(
	//'配置项'=>'配置值'
//    'SHOW_PAGE_TRACE'=>TRUE,
    'URL_MODEL' =>2,
    'BASE_URL'  =>'http://admin.shop.com/',
    'TMPL_PARSE_STRING'  =>[
        '__CSS__'   =>'/Public/css',
        '__JS__'   =>'/Public/js',
        '__IMG__'   =>'/Public/images',
        '__UPLOADIFY__'   =>'/Public/ext/uploadify',
        '__LAYER__'   =>'/Public/ext/layer',
        '__ZTREE__'     =>'/Public/ext/ztree',
        '__UEDITOR__'   =>'/Public/ext/ueditor',
    ],
    /* 数据库设置 */
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'project_shop',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'shop_',    // 数据库表前缀
    'DB_PARAMS'          	=>  array(), // 数据库连接参数
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'PAGE'                  =>[
        'SIZE'  =>5,
        'THEME' =>'%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%'
    ],

    'UPLOAD_SETTING'=>[
        'mimes'         =>  array('image/jpeg'), //允许上传的文件MiMe类型
        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  array('jpeg','jpg','jpe'), //允许上传的文件后缀
        'autoSub'       =>  true, //自动子目录保存文件
        'subName'       =>  array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath'      =>  './', //保存根路径
        'savePath'      =>  'Uploads/', //保存路径
        'saveName'      =>  array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
        'replace'       =>  false, //存在同名是否覆盖
        'hash'          =>  false, //是否生成hash编码
        'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
        'driver'        =>  'Qiniu', // 文件上传驱动
        'driverConfig'  =>  array(
            'secretKey'      => 'aXESHkcSM4BvVGFzcqq2dQTOxGUodM6NdoDz-bNs', //七牛服务器
            'accessKey'      => 'L5vzEaOLf3ZbeHD3XyXqQ6JEkq4KLXQtAyJOun1H', //七牛用户
            'domain'         => 'og6ews91g.bkt.clouddn.com/', //七牛域名
            'bucket'         => 'project-shop', //空间名称
            'timeout'        => 30, //超时时间
        ), // 上传驱动配置
    ],
    //权限验证的忽略列表
    'RBAC'=>[
        'IGNORES'=>[
            'Admin/Admin/login',
            'Admin/Captcha/show',
        ],
        'USER_IGNORES'=>[
            'Admin/Index/index',
            'Admin/Index/top',
            'Admin/Index/main',
            'Admin/Index/menu',
            'Admin/Admin/logout',
            'Admin/Upload/upload',
            'Admin/Editor/editor',
        ],
    ],
);